<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ArticleView;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->with('author')
            ->latest()
            ->paginate(10);

        return Inertia::render('Articles/Index', [
            'articles' => $articles->through(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'image_url' => $article->image_url,
                    'views' => $article->views,
                    'created_at' => $article->created_at,
                    'author' => [
                        'first_name' => $article->author->first_name,
                        'last_name' => $article->author->last_name,
                    ]
                ];
            })
        ]);
    }

    public function show(Article $article, Request $request)
    {
        $user = $request->user();
        $ip = $request->ip();

        $alreadyViewed = ArticleView::query()
            ->where('article_id', $article->id)
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('ip_address', $ip))
            ->exists();

        if (!$alreadyViewed) {
            ArticleView::create([
                'article_id' => $article->id,
                'user_id' => $user?->id,
                'ip_address' => $user ? null : $ip,
            ]);
            $article->increment('views');
        }

        return Inertia::render('Articles/Show', [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
                'image_url' => $article->image_url,
                'views' => $article->views,
                'created_at' => $article->created_at,
                'author' => [
                    'first_name' => $article->author->first_name,
                    'last_name' => $article->author->last_name,
                ]
            ]
        ]);
    }

    public function edit(Article $article)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'У вас нет прав для редактирования статей');
        }

        return Inertia::render('Articles/Edit', [
            'article' => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'У вас нет прав для редактирования статей');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $article->title = $validated['title'];
        $article->content = $validated['content'];

        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно есть
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->save();

        return redirect()->route('articles.show', $article)->with('success', 'Статья успешно обновлена');
    }

    public function create()
    {
        return Inertia::render('Articles/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $article = new Article($validated);
        $article->user_id = auth()->id();
        $article->status = 'published';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }
        $article->save();
        return redirect()->route('articles.index')->with('success', 'Статья успешно опубликована');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            \Storage::disk('public')->delete($article->image);
        }
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Статья удалена');
    }
} 