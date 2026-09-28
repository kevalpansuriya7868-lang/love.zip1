<?php

return [
    // Landing & User Auth
    'GET /'                  => ['LandingController', 'index'],
    'GET /login'             => ['AuthController', 'showLogin'],
    'POST /login'            => ['AuthController', 'login'],
    'GET /logout'            => ['AuthController', 'logout'],
    'GET /forgot-password'   => ['AuthController', 'showForgotPassword'],
    'POST /forgot-password'  => ['AuthController', 'forgotPassword'],

    // Couple User Dashboard & Features
    'GET /dashboard'         => ['DashboardController', 'index'],
    'GET /chat'              => ['ChatController', 'index'],
    'GET /photos'            => ['PhotoController', 'index'],
    'GET /photos/download-all' => ['PhotoController', 'downloadAll'],
    'GET /memories'          => ['MemoryController', 'index'],
    'GET /memories/create'   => ['MemoryController', 'create'],
    'POST /memories/create'  => ['MemoryController', 'create'],
    'GET /timeline'          => ['TimelineController', 'index'],
    'POST /timeline/store'   => ['TimelineController', 'store'],
    'GET /notifications'     => ['NotificationController', 'index'],

    // Call Signaling API
    'POST /api/call/initiate' => ['CallController', 'initiate'],
    'POST /api/call/answer'   => ['CallController', 'answer'],
    'POST /api/call/reject'   => ['CallController', 'reject'],
    'POST /api/call/end'      => ['CallController', 'end'],
    'POST /api/call/candidate'=> ['CallController', 'candidate'],
    'GET /api/call/poll'      => ['CallController', 'poll'],

    // Admin Auth & Management
    'GET /admin/login'                  => ['AuthController', 'showAdminLogin'],
    'POST /admin/login'                 => ['AuthController', 'adminLogin'],
    'GET /admin/logout'                 => ['AuthController', 'adminLogout'],
    'GET /admin/dashboard'              => ['AdminDashboardController', 'index'],
    'GET /admin/couples'                => ['AdminCoupleController', 'index'],
    'GET /admin/couples/create'         => ['AdminCoupleController', 'create'],
    'POST /admin/couples/create'        => ['AdminCoupleController', 'create'],
    'GET /admin/couples/created-success'=> ['AdminCoupleController', 'successScreen'],
    'GET /admin/couples/edit'           => ['AdminCoupleController', 'edit'],
    'POST /admin/couples/edit'          => ['AdminCoupleController', 'edit'],
    'POST /admin/couples/delete'        => ['AdminCoupleController', 'delete'],
    'GET /admin/users'                  => ['AdminUserController', 'index'],
    'POST /admin/users/toggle'          => ['AdminUserController', 'toggleStatus'],
    'POST /admin/users/reset-password'  => ['AdminUserController', 'resetPassword'],
    'GET /admin/messages'               => ['AdminChatController', 'index'],
    'POST /admin/messages/delete'       => ['AdminChatController', 'deleteMessage'],
    'GET /admin/photos'                 => ['AdminPhotoController', 'index'],
    'POST /admin/photos/delete'         => ['AdminPhotoController', 'delete'],
    'GET /admin/settings'               => ['AdminSettingsController', 'index'],
    'POST /admin/settings'              => ['AdminSettingsController', 'update'],
    'GET /admin/activity'               => ['AdminActivityController', 'index'],
];
