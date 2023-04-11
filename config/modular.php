<?php
return [
    'path' => base_path() . '/app/Modules',
    'base_namespace' => 'App\Modules',
    'groupWithoutPrefix' => 'Pub',

    'groupMidleware' => [
        'Admin' => [
            'web' => ['auth'],
            'api' => ['auth:api'],
        ]
    ],

    'modules' => [
        'Admin' => [
            'TaskComment',
            'Task',
            'Analytics',
            'Analitics',
            'LeadComment',
            'LeadCommetn',
            'Lead',
            'Sources',
            'Role',
            'Menu',
            'Dashboard',
            'User',
        ],

        'Pub' => [
            'Auth'
        ],
    ]
];
