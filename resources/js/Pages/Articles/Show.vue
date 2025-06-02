<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    article: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const deleteArticle = () => {
    if (confirm('Вы уверены, что хотите удалить эту статью?')) {
        Inertia.delete(route('articles.destroy', props.article.id));
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="article.title" />

        <div class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div v-if="article.image_url" class="w-full h-64 bg-gray-100 flex items-center justify-center">
                        <img :src="article.image_url" :alt="article.title" class="object-cover w-full h-full" />
                    </div>
                    <div v-else class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-300 text-6xl">
                        <i class="far fa-image"></i>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-3xl font-bold mb-4">{{ article.title }}</h1>
                            <button
                                v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                                @click="deleteArticle"
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 ml-4 h-12"
                            >
                                Удалить статью
                            </button>
                        </div>
                        <div class="flex items-center space-x-6 text-gray-500 text-sm mb-6">
                            <span>
                                <i class="far fa-user mr-1"></i>
                                {{ article.author.first_name }} {{ article.author.last_name }}
                            </span>
                            <span>
                                <i class="far fa-calendar mr-1"></i>
                                {{ formatDate(article.created_at) }}
                            </span>
                            <span>
                                <i class="far fa-eye mr-1"></i>
                                {{ article.views }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <div class="prose max-w-none text-gray-800">
                                <div v-html="article.content"></div>
                            </div>
                            <Link 
                                v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                                :href="route('articles.edit', article.id)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                            >
                                Редактировать
                            </Link>
                        </div>
                        <Link 
                            :href="route('articles.index')" 
                            class="text-blue-600 hover:text-blue-800 flex items-center"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Назад к списку статей
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.prose {
    font-size: 1.1rem;
    line-height: 1.7;
}
</style> 