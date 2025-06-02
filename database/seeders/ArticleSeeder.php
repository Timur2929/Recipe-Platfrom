<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Бешбармак?',
                'content' => 'Национальное блюдо тюркских народов, которое представляет собой отварное мясо,
                 приправленное луковым соусом, зеленью и смешанное с самодельной лапшей',
                'image' => 'app/public/images/articles/beshbarmak.jpg',
                'status' => 'published',
            ],
            [
                'title' => 'Откуда взялась пицца?',
                'content' => 'Удивительная история возникновения блюда',
                'image' => 'app/public/images/articles/pizaathat.jpg',
                'status' => 'published',
            ],
            [
                'title' => 'Лазанья Домашняя',
                'content' => 'Замечательная лазанья на вашем столе',
                'image' => 'app/public/images/articles/lasagna.jpg',
                'status' => 'published',
            ],
        ];

        $userId = \App\Models\User::first()->id;
        foreach ($articles as $article) {
            Article::create([
                'title' => $article['title'],
                'content' => $article['content'],
                'image' => $article['image'],
                'status' => $article['status'],
                'user_id' => $userId,
            ]);
        }
    }
} 