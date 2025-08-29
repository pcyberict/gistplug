<?php

class NewsManager {
    private static $db = null;
    
    private static function getDatabase() {
        if (self::$db === null) {
            self::$db = Database::getInstance()->getConnection();
        }
        return self::$db;
    }
    
    public static function loadNews() {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles ORDER BY created_at DESC");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convert boolean values for compatibility
            foreach ($results as &$article) {
                $article['featured'] = (bool)$article['featured'];
                $article['allow_comments'] = (bool)$article['allow_comments'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("NewsManager::loadNews() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function addArticle($article) {
        try {
            $db = self::getDatabase();
            
            // Generate unique ID and slug if not provided
            if (empty($article['slug'])) {
                $article['slug'] = self::generateSlug($article['title']);
            }
            
            $sql = "INSERT INTO news_articles (
                title, slug, excerpt, content, category, tags, status, 
                featured, allow_comments, featured_image, additional_images, 
                seo_title, seo_description, publish_date, author, created_at, updated_at
            ) VALUES (
                :title, :slug, :excerpt, :content, :category, :tags, :status,
                :featured, :allow_comments, :featured_image, :additional_images,
                :seo_title, :seo_description, :publish_date, :author, 
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            )";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':title' => $article['title'],
                ':slug' => $article['slug'],
                ':excerpt' => $article['excerpt'] ?? '',
                ':content' => $article['content'],
                ':category' => $article['category'],
                ':tags' => $article['tags'] ?? '',
                ':status' => $article['status'] ?? 'draft',
                ':featured' => $article['featured'] ? 1 : 0,
                ':allow_comments' => $article['allow_comments'] ? 1 : 0,
                ':featured_image' => $article['featured_image'] ?? '',
                ':additional_images' => $article['additional_images'] ?? '',
                ':seo_title' => $article['seo_title'] ?? '',
                ':seo_description' => $article['seo_description'] ?? '',
                ':publish_date' => $article['publish_date'] ?? null,
                ':author' => $article['author'] ?? 'Admin'
            ]);
            
            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("NewsManager::addArticle() error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getPublishedNews() {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE status = 'published' ORDER BY created_at DESC");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convert boolean values for compatibility
            foreach ($results as &$article) {
                $article['featured'] = (bool)$article['featured'];
                $article['allow_comments'] = (bool)$article['allow_comments'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("NewsManager::getPublishedNews() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getFeaturedNews() {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE status = 'published' AND featured = true ORDER BY created_at DESC");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convert boolean values for compatibility
            foreach ($results as &$article) {
                $article['featured'] = (bool)$article['featured'];
                $article['allow_comments'] = (bool)$article['allow_comments'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("NewsManager::getFeaturedNews() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getNewsByCategory($category) {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE status = 'published' AND category = :category ORDER BY created_at DESC");
            $stmt->execute([':category' => $category]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convert boolean values for compatibility
            foreach ($results as &$article) {
                $article['featured'] = (bool)$article['featured'];
                $article['allow_comments'] = (bool)$article['allow_comments'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("NewsManager::getNewsByCategory() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getNewsById($id) {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $result['featured'] = (bool)$result['featured'];
                $result['allow_comments'] = (bool)$result['allow_comments'];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("NewsManager::getNewsById() error: " . $e->getMessage());
            return null;
        }
    }
    
    public static function getNewsBySlug($slug) {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE slug = :slug");
            $stmt->execute([':slug' => $slug]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $result['featured'] = (bool)$result['featured'];
                $result['allow_comments'] = (bool)$result['allow_comments'];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("NewsManager::getNewsBySlug() error: " . $e->getMessage());
            return null;
        }
    }
    
    public static function updateArticle($id, $updates) {
        try {
            $db = self::getDatabase();
            
            // Build dynamic update query
            $setClause = [];
            $params = [':id' => $id];
            
            foreach ($updates as $field => $value) {
                $setClause[] = "$field = :$field";
                $params[":$field"] = $value;
            }
            
            // Always update the updated_at timestamp
            $setClause[] = "updated_at = CURRENT_TIMESTAMP";
            
            $sql = "UPDATE news_articles SET " . implode(', ', $setClause) . " WHERE id = :id";
            $stmt = $db->prepare($sql);
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("NewsManager::updateArticle() error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function deleteArticle($id) {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("DELETE FROM news_articles WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("NewsManager::deleteArticle() error: " . $e->getMessage());
            return false;
        }
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
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT DISTINCT category FROM news_articles WHERE category IS NOT NULL AND category != '' ORDER BY category");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return array_filter($results); // Remove empty values
        } catch (PDOException $e) {
            error_log("NewsManager::getCategories() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getTags() {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT tags FROM news_articles WHERE tags IS NOT NULL AND tags != ''");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $allTags = [];
            foreach ($results as $tagString) {
                if (!empty($tagString)) {
                    $tags = explode(',', $tagString);
                    $tags = array_map('trim', $tags);
                    $allTags = array_merge($allTags, $tags);
                }
            }
            
            return array_unique(array_filter($allTags));
        } catch (PDOException $e) {
            error_log("NewsManager::getTags() error: " . $e->getMessage());
            return [];
        }
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
    
    public static function getDraftNews() {
        try {
            $db = self::getDatabase();
            $stmt = $db->prepare("SELECT * FROM news_articles WHERE status = 'draft' ORDER BY created_at DESC");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convert boolean values for compatibility
            foreach ($results as &$article) {
                $article['featured'] = (bool)$article['featured'];
                $article['allow_comments'] = (bool)$article['allow_comments'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("NewsManager::getDraftNews() error: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getNewsStats() {
        try {
            $db = self::getDatabase();
            
            $stats = [];
            
            // Total articles
            $stmt = $db->prepare("SELECT COUNT(*) FROM news_articles");
            $stmt->execute();
            $stats['total'] = $stmt->fetchColumn();
            
            // Published articles
            $stmt = $db->prepare("SELECT COUNT(*) FROM news_articles WHERE status = 'published'");
            $stmt->execute();
            $stats['published'] = $stmt->fetchColumn();
            
            // Draft articles
            $stmt = $db->prepare("SELECT COUNT(*) FROM news_articles WHERE status = 'draft'");
            $stmt->execute();
            $stats['drafts'] = $stmt->fetchColumn();
            
            // Featured articles
            $stmt = $db->prepare("SELECT COUNT(*) FROM news_articles WHERE featured = true");
            $stmt->execute();
            $stats['featured'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("NewsManager::getNewsStats() error: " . $e->getMessage());
            return ['total' => 0, 'published' => 0, 'drafts' => 0, 'featured' => 0];
        }
    }
}