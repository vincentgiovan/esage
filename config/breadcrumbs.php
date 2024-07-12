<?php

return [
    'home' => [
        'title' => 'Home',
        'route' => 'home',
    ],
    'dashboard' => [
        'title' => 'Dashboard',
        'parent' => 'home',
        'route' => 'dashboard',
    ],
    'deliveryorder-index' => [
        'title' => 'Delivery Orders',
        'parent' => 'dashboard',
        'route' => 'deliveryorder-index',
    ],
    'deliveryorder-create' => [
        'title' => 'Create',
        'parent' => 'deliveryorder-index',
        'route' => 'deliveryorder-create',
    ],
    'deliveryorder-edit' => [
        'title' => 'Edit :id',
        'parent' => 'deliveryorder-index',
        'route' => 'deliveryorder-edit',
        'params' => ['id'],
    ],
    // Add other breadcrumbs similarly
];
