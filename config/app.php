<?php

return [
    'name'       => getenv('APP_NAME') ?: 'Couples Private Website',
    'env'        => getenv('APP_ENV') ?: 'local',
    'url'        => getenv('APP_URL') ?: 'http://localhost/love/public',
    'timezone'   => 'UTC',
    'upload_dir' => __DIR__ . '/../public/uploads',
    'max_file_size' => (int)(getenv('UPLOAD_MAX_SIZE') ?: 10485760), // 10 MB
    'allowed_extensions' => explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'jpg,jpeg,png,webp'),
];
