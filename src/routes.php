<?php
return [
    [
        'POST',
        '/transactions',
        [
            'App\Controllers\TransactionController',
            'queueTransaction'
        ]
    ]
];