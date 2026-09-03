-- ============================================================
-- NIZARI Rachid - Moroccan Calligrapher Website
-- Database: nizari_calligraphy
-- Version: 1.0.0
-- Created: 2024
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `nizari_calligraphy`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `nizari_calligraphy`;

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `preferred_language` varchar(5) NOT NULL DEFAULT 'ar',
  `login_attempts` int NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: roles (Spatie Permission)
-- ============================================================
CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: settings
-- ============================================================
CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(100) NOT NULL DEFAULT 'general',
  `type` varchar(50) NOT NULL DEFAULT 'string',
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: pricing_config
-- ============================================================
CREATE TABLE `pricing_config` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `price_per_cm` decimal(10,2) NOT NULL DEFAULT 5.00,
  `digital_copy_price` decimal(10,2) NOT NULL DEFAULT 50.00,
  `shipping_local_price` decimal(10,2) NOT NULL DEFAULT 30.00,
  `shipping_international_price` decimal(10,2) NOT NULL DEFAULT 150.00,
  `currency` varchar(10) NOT NULL DEFAULT 'MAD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: material_prices
-- ============================================================
CREATE TABLE `material_prices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_fr` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'cm²',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: categories
-- ============================================================
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_fr` varchar(255) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'artwork',
  `sort_order` int NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: artworks
-- ============================================================
CREATE TABLE `artworks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `main_image` varchar(255) NOT NULL,
  `gallery_images` json DEFAULT NULL,
  `calligraphy_style` enum('moroccan','andalusian','naskh','thuluth','kufic','ruqah','diwani','other') NOT NULL DEFAULT 'moroccan',
  `material` enum('paper','wood','glass','plaster','canvas','leather','fabric','digital','other') NOT NULL DEFAULT 'paper',
  `width_cm` decimal(8,2) DEFAULT NULL,
  `height_cm` decimal(8,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_for_sale` tinyint(1) NOT NULL DEFAULT 0,
  `is_sold` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
  `views` int NOT NULL DEFAULT 0,
  `likes` int NOT NULL DEFAULT 0,
  `text_content` text DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `artworks_slug_unique` (`slug`),
  KEY `artworks_user_id_foreign` (`user_id`),
  KEY `artworks_category_id_foreign` (`category_id`),
  CONSTRAINT `artworks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `artworks_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: orders
-- ============================================================
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_city` varchar(100) DEFAULT NULL,
  `customer_country` varchar(100) DEFAULT NULL,
  `order_type` enum('artwork','name_writing','verse_hadith','institution','home_decor','digital_copy','custom') NOT NULL DEFAULT 'custom',
  `text_to_write` text DEFAULT NULL,
  `width_cm` decimal(8,2) DEFAULT NULL,
  `height_cm` decimal(8,2) DEFAULT NULL,
  `material` enum('paper','wood','glass','plaster','canvas','leather','fabric','digital','other') DEFAULT NULL,
  `calligraphy_style` enum('moroccan','andalusian','naskh','thuluth','kufic','ruqah','diwani','other') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reference_images` json DEFAULT NULL,
  `delivery_method` enum('digital','postal','pickup') NOT NULL DEFAULT 'digital',
  `delivery_address` text DEFAULT NULL,
  `artwork_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `material_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `labor_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `digital_copy_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'MAD',
  `payment_method` enum('paypal','stripe','bank_transfer','manual','cash') DEFAULT NULL,
  `payment_status` enum('pending','paid','partial','refunded','cancelled') NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) DEFAULT NULL,
  `status` enum('new','confirmed','in_progress','ready','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: products
-- ============================================================
CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_fr` varchar(255) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `type` enum('artwork','digital','course','pdf_book','certificate','other') NOT NULL DEFAULT 'artwork',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'MAD',
  `stock` int NOT NULL DEFAULT 0,
  `unlimited_stock` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_downloadable` tinyint(1) NOT NULL DEFAULT 0,
  `download_file` varchar(255) DEFAULT NULL,
  `status` enum('draft','active','out_of_stock','archived') NOT NULL DEFAULT 'active',
  `width_cm` decimal(8,2) DEFAULT NULL,
  `height_cm` decimal(8,2) DEFAULT NULL,
  `weight_kg` decimal(8,3) DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: courses
-- ============================================================
CREATE TABLE `courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `type` enum('individual','group','online','offline','hybrid') NOT NULL DEFAULT 'online',
  `level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `calligraphy_style` enum('moroccan','andalusian','naskh','thuluth','kufic','all') NOT NULL DEFAULT 'all',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration_hours` int DEFAULT NULL,
  `max_students` int DEFAULT NULL,
  `enrolled_count` int NOT NULL DEFAULT 0,
  `status` enum('draft','active','completed','cancelled') NOT NULL DEFAULT 'active',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `meeting_link` varchar(500) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `curriculum` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`),
  KEY `courses_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: blog_posts
-- ============================================================
CREATE TABLE `blog_posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt_ar` text DEFAULT NULL,
  `excerpt_fr` text DEFAULT NULL,
  `excerpt_en` text DEFAULT NULL,
  `content_ar` longtext DEFAULT NULL,
  `content_fr` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `status` enum('draft','published','scheduled','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int NOT NULL DEFAULT 0,
  `tags` json DEFAULT NULL,
  `meta_seo` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_author_id_foreign` (`author_id`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: contact_messages
-- ============================================================
CREATE TABLE `contact_messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('new','read','replied','archived') NOT NULL DEFAULT 'new',
  `admin_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `contact_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: faqs
-- ============================================================
CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_ar` varchar(500) NOT NULL,
  `question_fr` varchar(500) DEFAULT NULL,
  `question_en` varchar(500) DEFAULT NULL,
  `answer_ar` text NOT NULL,
  `answer_fr` text DEFAULT NULL,
  `answer_en` text DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: testimonials
-- ============================================================
CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `client_country` varchar(100) DEFAULT NULL,
  `client_avatar` varchar(255) DEFAULT NULL,
  `testimonial_ar` text NOT NULL,
  `testimonial_fr` text DEFAULT NULL,
  `testimonial_en` text DEFAULT NULL,
  `rating` int NOT NULL DEFAULT 5,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: audit_logs
-- ============================================================
CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: migrations (Laravel)
-- ============================================================
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default Super Admin (password: Fatima@1977 - bcrypt hashed)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`, `force_password_change`, `preferred_language`, `created_at`, `updated_at`)
VALUES (1, 'NIZARI Rachid', 'nizari@nizari.net',
  '$2y$12$YEVXBJqhfFFlQVHFr5D45.NvD4BAbNmzqJVU6VpikGqM4uDrE1vZ6',
  'active', 1, 'ar', NOW(), NOW());

-- Roles
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', NOW(), NOW()),
(2, 'admin', 'web', NOW(), NOW()),
(3, 'editor', 'web', NOW(), NOW()),
(4, 'teacher', 'web', NOW(), NOW()),
(5, 'customer', 'web', NOW(), NOW());

-- Assign super-admin role to first user
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- Default Settings
INSERT INTO `settings` (`key`, `value`, `group`, `type`, `is_public`, `created_at`, `updated_at`) VALUES
('site_name_ar', 'رشيد النزاري - خطاط مغربي', 'general', 'string', 1, NOW(), NOW()),
('site_name_fr', 'NIZARI Rachid - Calligraphe Marocain', 'general', 'string', 1, NOW(), NOW()),
('site_name_en', 'NIZARI Rachid - Moroccan Calligrapher', 'general', 'string', 1, NOW(), NOW()),
('site_description_ar', 'خطاط وفنان مغربي من مراكش متخصص في الخط المغربي والعربي الكلاسيكي', 'general', 'string', 1, NOW(), NOW()),
('contact_email', 'contact@nizari.net', 'contact', 'string', 1, NOW(), NOW()),
('contact_phone', '+212 6XX XXX XXX', 'contact', 'string', 1, NOW(), NOW()),
('contact_address_ar', 'مراكش، المغرب', 'contact', 'string', 1, NOW(), NOW()),
('facebook_url', '', 'social', 'string', 1, NOW(), NOW()),
('instagram_url', '', 'social', 'string', 1, NOW(), NOW()),
('youtube_url', '', 'social', 'string', 1, NOW(), NOW()),
('whatsapp_number', '', 'social', 'string', 1, NOW(), NOW()),
('default_currency', 'MAD', 'payment', 'string', 1, NOW(), NOW()),
('maintenance_mode', '0', 'general', 'boolean', 0, NOW(), NOW());

-- Default Pricing
INSERT INTO `pricing_config` (`price_per_cm`, `digital_copy_price`, `shipping_local_price`, `shipping_international_price`, `currency`, `created_at`, `updated_at`)
VALUES (5.00, 50.00, 30.00, 150.00, 'MAD', NOW(), NOW());

-- Material Prices
INSERT INTO `material_prices` (`name_ar`, `name_fr`, `name_en`, `price_per_unit`, `unit`, `active`, `created_at`, `updated_at`) VALUES
('ورق', 'Papier', 'Paper', 0.50, 'cm²', 1, NOW(), NOW()),
('خشب', 'Bois', 'Wood', 2.00, 'cm²', 1, NOW(), NOW()),
('زجاج', 'Verre', 'Glass', 3.00, 'cm²', 1, NOW(), NOW()),
('جبس', 'Plâtre', 'Plaster', 2.50, 'cm²', 1, NOW(), NOW()),
('قماش', 'Toile', 'Canvas', 1.50, 'cm²', 1, NOW(), NOW()),
('جلد', 'Cuir', 'Leather', 4.00, 'cm²', 1, NOW(), NOW());

-- Categories
INSERT INTO `categories` (`name_ar`, `name_fr`, `name_en`, `slug`, `type`, `sort_order`, `active`, `created_at`, `updated_at`) VALUES
('الخط المغربي', 'Calligraphie Marocaine', 'Moroccan Calligraphy', 'moroccan-calligraphy', 'artwork', 1, 1, NOW(), NOW()),
('الخط الأندلسي', 'Calligraphie Andalouse', 'Andalusian Calligraphy', 'andalusian-calligraphy', 'artwork', 2, 1, NOW(), NOW()),
('الخط العربي الكلاسيكي', 'Calligraphie Arabe Classique', 'Classical Arabic Calligraphy', 'classical-arabic', 'artwork', 3, 1, NOW(), NOW()),
('لوحات الديكور', 'Tableaux Décoratifs', 'Decorative Artworks', 'decorative', 'artwork', 4, 1, NOW(), NOW()),
('آيات قرآنية', 'Versets Coraniques', 'Quranic Verses', 'quranic-verses', 'artwork', 5, 1, NOW(), NOW()),
('أسماء وعبارات', 'Noms et Expressions', 'Names and Phrases', 'names-phrases', 'artwork', 6, 1, NOW(), NOW());

-- FAQs
INSERT INTO `faqs` (`question_ar`, `question_fr`, `question_en`, `answer_ar`, `answer_fr`, `answer_en`, `sort_order`, `active`, `created_at`, `updated_at`) VALUES
(
  'كم يستغرق إنجاز لوحة فنية؟',
  'Combien de temps faut-il pour réaliser une œuvre?',
  'How long does it take to complete an artwork?',
  'يتراوح وقت الإنجاز بين 3 إلى 14 يوم عمل حسب حجم اللوحة وتعقيدها. نلتزم دائماً بالمواعيد المتفق عليها.',
  'Le délai de réalisation varie entre 3 et 14 jours ouvrables selon la taille et la complexité de l\'œuvre.',
  'Completion time ranges from 3 to 14 business days depending on the size and complexity of the artwork.',
  1, 1, NOW(), NOW()
),
(
  'هل يمكن طلب عمل فني مخصص؟',
  'Peut-on commander une œuvre personnalisée?',
  'Can I order a custom artwork?',
  'نعم، نقبل جميع الطلبات المخصصة. يمكنك اختيار النص والأسلوب والمقاسات والمادة حسب رغبتك.',
  'Oui, nous acceptons toutes les commandes personnalisées. Vous pouvez choisir le texte, le style, les dimensions et le matériau.',
  'Yes, we accept all custom orders. You can choose the text, style, dimensions, and material according to your wishes.',
  2, 1, NOW(), NOW()
),
(
  'ما هي طرق الدفع المتاحة؟',
  'Quels sont les modes de paiement disponibles?',
  'What payment methods are available?',
  'نقبل الدفع عبر PayPal وStripe والتحويل البنكي والدفع اليدوي بعد موافقة الإدارة.',
  'Nous acceptons les paiements via PayPal, Stripe, virement bancaire et paiement manuel après approbation.',
  'We accept payments via PayPal, Stripe, bank transfer, and manual payment after admin approval.',
  3, 1, NOW(), NOW()
),
(
  'هل تقدمون خدمة الشحن الدولي؟',
  'Proposez-vous la livraison internationale?',
  'Do you offer international shipping?',
  'نعم، نشحن إلى جميع دول العالم. تختلف تكلفة الشحن حسب الوجهة والأبعاد.',
  'Oui, nous livrons dans le monde entier. Les frais varient selon la destination et les dimensions.',
  'Yes, we ship worldwide. Shipping costs vary by destination and dimensions.',
  4, 1, NOW(), NOW()
),
(
  'هل يمكن الحصول على نسخة رقمية؟',
  'Peut-on obtenir une copie numérique?',
  'Can I get a digital copy?',
  'نعم، نوفر خدمة تحويل الأعمال اليدوية إلى نسخة رقمية عالية الدقة (PNG/PDF/SVG) يمكن طباعتها بأي حجم.',
  'Oui, nous proposons un service de conversion des œuvres en copie numérique haute résolution (PNG/PDF/SVG).',
  'Yes, we offer a service to convert handmade works into high-resolution digital copies (PNG/PDF/SVG).',
  5, 1, NOW(), NOW()
);

-- Testimonials
INSERT INTO `testimonials` (`client_name`, `client_country`, `testimonial_ar`, `testimonial_fr`, `testimonial_en`, `rating`, `is_featured`, `active`, `created_at`, `updated_at`) VALUES
('أحمد المنصوري', 'المغرب', 'لوحة رائعة وخط جميل جداً، تفوق توقعاتي. أنصح الجميع بالتعامل مع الأستاذ رشيد.', 'Un tableau magnifique et une belle calligraphie, au-delà de mes attentes.', 'A wonderful painting and very beautiful calligraphy, beyond my expectations.', 5, 1, 1, NOW(), NOW()),
('Fatima Zahra', 'France', 'طلبت لوحة بمناسبة الزواج وكانت رائعة. الشحن سريع والتغليف ممتاز.', 'J\'ai commandé un tableau pour un mariage, il était magnifique. Livraison rapide et emballage excellent.', 'I ordered a painting for a wedding and it was magnificent. Fast shipping and excellent packaging.', 5, 1, 1, NOW(), NOW()),
('محمد الريفي', 'السعودية', 'أفضل خطاط تعاملت معه. يفهم المطلوب ويعطي نتيجة فائقة الجودة.', 'Le meilleur calligraphe avec qui j\'ai travaillé. Il comprend les besoins et livre un résultat excellent.', 'The best calligrapher I have worked with. He understands the requirements and delivers excellent quality.', 5, 1, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- END OF SQL FILE
-- nizari_calligraphy.sql
-- ============================================================
