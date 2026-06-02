<?php
// Configuration générale
define('APP_NAME', 'GéMoires');
define('APP_URL', 'http://localhost/gemoires');
define('APP_VERSION', '1.0');

// Rôles utilisateurs
define('ROLE_DIRECTEUR', 'directeur');
define('ROLE_PROF', 'professeur');
define('ROLE_DIPLOME', 'diplome');
define('ROLE_CONSULTANT', 'consultant');
define('ROLE_ADMIN', 'admin');

// Upload
define('MAX_FILE_SIZE', 20 * 1024 * 1024); // 20MB
define('UPLOAD_PATH', __DIR__ . '/../storage/memoires/');
define('ALLOWED_TYPES', ['pdf', 'doc', 'docx']);