<template>
    <AppLayout>
        <Head title="Редактировать статью" />
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">Редактировать статью</h1>
                <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название статьи</label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.title }"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                    </div>
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Содержание статьи</label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.content }"
                        ></textarea>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Фото статьи</label>
                        <input
                            type="file"
                            id="image"
                            @input="form.image = $event.target.files[0]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.image }"
                        />
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
                        <div v-if="imagePreview" class="mt-2">
                            <img :src="imagePreview" :alt="form.title" class="w-32 h-32 object-cover rounded" />
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-8">
                        <Link
                            :href="route('articles.show', article.id)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                        >
                            Отмена
                        </Link>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                            :disabled="form.processing"
                        >
                            Сохранить
                        </button>
                        <button
                            v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                            type="button"
                            @click="deleteArticle"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 ml-4"
                        >
                            Удалить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    article: Object
});

const page = usePage();

const form = useForm({
    title: page.props.old?.title ?? props.article.title,
    content: page.props.old?.content ?? props.article.content,
    image: null
});

const imagePreview = computed(() => {
    if (form.image) {
        return URL.createObjectURL(form.image);
    }
    return props.article.image_url;
});

const submit = () => {
    const data = new FormData();
    data.append('title', form.title);
    data.append('content', form.content);
    if (form.image) {
        data.append('image', form.image);
    }
    data.append('_method', 'put');

    Inertia.post(route('articles.update', props.article.id), data, {
        onSuccess: () => {
            form.image = null;
        }
    });
};

const deleteArticle = () => {
    if (confirm('Вы уверены, что хотите удалить эту статью?')) {
        form.delete(route('articles.destroy', props.article.id));
    }
};
</script> 