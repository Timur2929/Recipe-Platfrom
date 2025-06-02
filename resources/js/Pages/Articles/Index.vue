<template>
    <AppLayout>
        <Head title="Статьи" />

        <div class="articles-container">
            <div class="articles-header">
                <div class="header-content">
                    <h1 class="page-title">Статьи</h1>
                    <p class="page-subtitle">Полезные материалы и советы от наших авторов</p>
                </div>
                
                <Link 
                    v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                    :href="route('admin.articles.create')"
                    class="add-article-btn"
                >
                    <i class="fas fa-plus"></i>
                    Добавить статью
                </Link>
            </div>

            <div v-if="articles.data.length > 0" class="articles-grid">
                <div v-for="article in articles.data" :key="article.id" class="article-card">
                    <Link :href="route('articles.show', article.id)" class="article-link">
                        <div class="article-image">
                           <img 
                            :src="article.image || '/images/article-placeholder.jpg'" 
                            :alt="article.title"
                            class="article-img"
                                >
                            <div class="article-badge">Статья</div>
                        </div>
                        
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="meta-item">
                                    <i class="far fa-calendar"></i>
                                    {{ formatDate(article.created_at) }}
                                </span>
                                <span class="meta-item">
                                    <i class="far fa-eye"></i>
                                    {{ article.views }} просмотров
                                </span>
                            </div>
                            
                            <h3 class="article-title">{{ article.title }}</h3>
                            
                            <div class="read-more">
                                Читать статью
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">
                    <i class="far fa-newspaper"></i>
                </div>
                <h2>Статей пока нет</h2>
                <p>Здесь будут появляться полезные статьи о кулинарии и не только</p>
                
                <Link 
                    v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                    :href="route('admin.articles.create')"
                    class="create-article-btn"
                >
                    Создать первую статью
                </Link>
            </div>

            <Pagination v-if="articles.data.length > 0" :links="articles.links" class="pagination" />
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    articles: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};
</script>

<style scoped>
.articles-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.articles-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.page-subtitle {
    font-size: 16px;
    color: #718096;
    max-width: 600px;
}

.add-article-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background-color: #4CAF50;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.add-article-btn:hover {
    background-color: #45a049;
    transform: translateY(-2px);
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.article-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.article-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.article-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.article-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.article-card:hover .article-img {
    transform: scale(1.05);
}

.article-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    color: #4CAF50;
}

.article-content {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.article-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 14px;
    color: #718096;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.article-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
    line-height: 1.4;
}

.read-more {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4CAF50;
    font-weight: 500;
    transition: color 0.2s;
}

.article-card:hover .read-more {
    color: #3d8b40;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    max-width: 500px;
    margin: 0 auto;
}

.empty-icon {
    font-size: 60px;
    color: #e2e8f0;
    margin-bottom: 20px;
}

.empty-state h2 {
    font-size: 22px;
    color: #2d3748;
    margin-bottom: 15px;
    font-weight: 600;
}

.empty-state p {
    color: #718096;
    margin-bottom: 30px;
    line-height: 1.5;
}

.create-article-btn {
    display: inline-block;
    padding: 12px 28px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s;
    font-weight: 500;
}

.create-article-btn:hover {
    background: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(74, 222, 128, 0.2);
}

.pagination {
    margin-top: 40px;
}

@media (max-width: 768px) {
    .articles-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 28px;
    }
}
</style>