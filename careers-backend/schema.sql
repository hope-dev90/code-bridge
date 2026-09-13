-- Codebridge careers application system
-- Run this once against the existing TYPO3 database (codebrig_codebrige)
-- via phpMyAdmin / cPanel "Databases" before careers-apply.php is used.

-- ── Content management: blog posts, job postings, gallery photos ──
-- Lets the team publish new content through the admin dashboard
-- (careers-admin.php) instead of editing HTML by hand.

CREATE TABLE IF NOT EXISTS codebridge_blog_posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  excerpt VARCHAR(400) NOT NULL,
  body MEDIUMTEXT NULL,
  image_filename VARCHAR(255) NULL,
  badge VARCHAR(40) NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS codebridge_jobs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  category ENUM('dev','uiux','aiml') NOT NULL DEFAULT 'dev',
  job_type ENUM('job','internship') NOT NULL DEFAULT 'job',
  meta_line VARCHAR(200) NULL,
  description TEXT NULL,
  requirements TEXT NULL,
  status ENUM('active','closed') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_status_type (status, job_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS codebridge_gallery (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  tag VARCHAR(80) NULL,
  image_filename VARCHAR(255) NOT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS codebridge_reels (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  tag VARCHAR(80) NULL,
  youtube_id VARCHAR(20) NOT NULL,
  image_filename VARCHAR(255) NOT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS codebridge_job_applications (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  position VARCHAR(120) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  company VARCHAR(150) NULL,
  date_of_birth DATE NULL,
  location VARCHAR(150) NULL,
  linkedin_url VARCHAR(255) NULL,
  institution1 VARCHAR(190) NULL,
  degree1 VARCHAR(100) NULL,
  field_of_study1 VARCHAR(150) NULL,
  graduation_year1 SMALLINT UNSIGNED NULL,
  institution2 VARCHAR(190) NULL,
  degree2 VARCHAR(100) NULL,
  skills VARCHAR(400) NULL,
  note TEXT NULL,
  cv_filename VARCHAR(255) NULL,
  cover_letter_filename VARCHAR(255) NULL,
  status ENUM('new','reviewed','shortlisted','rejected','hired') NOT NULL DEFAULT 'new',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_position (position),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
