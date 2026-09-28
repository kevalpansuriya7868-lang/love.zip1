<?php

return [
    'POST /api/chat/send'            => ['ChatApiController', 'send'],
    'GET /api/chat/messages'         => ['ChatApiController', 'messages'],
    'POST /api/chat/delete'          => ['ChatApiController', 'delete'],
    'POST /api/chat/read'            => ['ChatApiController', 'read'],

    'POST /api/photos/upload'        => ['PhotoApiController', 'upload'],
    'POST /api/photos/delete'        => ['PhotoApiController', 'delete'],
    
    'POST /api/photos/folders/create'=> ['PhotoApiController', 'createFolder'],
    'POST /api/photos/folders/delete'=> ['PhotoApiController', 'deleteFolder'],

    'POST /api/memories/delete'      => ['MemoryApiController', 'delete'],

    'GET /api/notifications/unread'  => ['NotificationApiController', 'getUnread'],
    'POST /api/notifications/read-all'=> ['NotificationApiController', 'markAllRead'],
];
