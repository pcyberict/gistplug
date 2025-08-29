<?php

class NewsManager {
    private static $news_file = 'news.json';
    
    public static function loadNews() {
        if (!file_exists(self::$news_file)) {
            return [];
        }
        
        $json = file_get_contents(self::$news_file);
        $news = json_decode($json, true);
        
        if (!is_array($news)) {
            return [];
        }
        
        // Sort by date (most recent first)
        usort($news, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $news;
    }
    
    public static function saveNews($news) {
        file_put_contents(self::$news_file, json_encode($news, JSON_PRETTY_PRINT));
    }
    
    public static function addArticle($article) {
        $news = self::loadNews();
        
        // Generate unique ID
        $article['id'] = uniqid();
        $article['created_at'] = date('Y-m-d H:i:s');
        $article['updated_at'] = date('Y-m-d H:i:s');
        
        // Auto-generate slug if not provided
        if (empty($article['slug'])) {
            $article['slug'] = self::generateSlug($article['title']);
        }
        
        // Add to beginning of array (most recent first)
        array_unshift($news, $article);
        
        self::saveNews($news);
        return $article['id'];
    }
    
    public static function getPublishedNews() {
        $news = self::loadNews();
        return array_filter($news, function($article) {
            return $article['status'] === 'published';
        });
    }
    
    public static function getFeaturedNews() {
        $news = self::getPublishedNews();
        return array_filter($news, function($article) {
            return !empty($article['featured']);
        });
    }
    
    public static function getNewsByCategory($category) {
        $news = self::getPublishedNews();
        return array_filter($news, function($article) use ($category) {
            return $article['category'] === $category;
        });
    }
    
    public static function getNewsById($id) {
        $news = self::loadNews();
        foreach ($news as $article) {
            if ($article['id'] === $id) {
                return $article;
            }
        }
        return null;
    }
    
    public static function getNewsBySlug($slug) {
        $news = self::loadNews();
        foreach ($news as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }
        return null;
    }
    
    public static function updateArticle($id, $updates) {
        $news = self::loadNews();
        for ($i = 0; $i < count($news); $i++) {
            if ($news[$i]['id'] === $id) {
                $news[$i] = array_merge($news[$i], $updates);
                $news[$i]['updated_at'] = date('Y-m-d H:i:s');
                self::saveNews($news);
                return true;
            }
        }
        return false;
    }
    
    public static function deleteArticle($id) {
        $news = self::loadNews();
        $filtered = array_filter($news, function($article) use ($id) {
            return $article['id'] !== $id;
        });
        
        if (count($filtered) < count($news)) {
            self::saveNews(array_values($filtered));
            return true;
        }
        return false;
    }
    
    private static function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (self::getNewsBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    public static function getCategories() {
        $news = self::loadNews();
        $categories = array_unique(array_column($news, 'category'));
        return array_filter($categories); // Remove empty values
    }
    
    public static function getTags() {
        $news = self::loadNews();
        $allTags = [];
        
        foreach ($news as $article) {
            if (!empty($article['tags'])) {
                $tags = explode(',', $article['tags']);
                $tags = array_map('trim', $tags);
                $allTags = array_merge($allTags, $tags);
            }
        }
        
        return array_unique(array_filter($allTags));
    }
    
    public static function formatTimeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'Just now';
        if ($time < 3600) return floor($time/60) . 'm ago';
        if ($time < 86400) return floor($time/3600) . 'h ago';
        if ($time < 2592000) return floor($time/86400) . 'd ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        return floor($time/31536000) . ' years ago';
    }
}