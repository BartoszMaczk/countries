<?php
return [
    'updateOwnProfile' => [
        'type' => 2,
        'description' => 'Update own profile',
        'ruleName' => 'isAuthor',
    ],
    'createCountry' => [
        'type' => 2,
        'description' => 'Create a country',
    ],
    'author' => [
        'type' => 1,
        'children' => [
            'createCountry',
            'updateOwnProfile',
            'deleteOwnProfile',
        ],
    ],
    'updateCountry' => [
        'type' => 2,
        'description' => 'Update country',
    ],
    'deleteCountry' => [
        'type' => 2,
        'description' => 'Delete country',
    ],
    'acceptCountry' => [
        'type' => 2,
        'description' => 'Accept country',
    ],
    'updateUser' => [
        'type' => 2,
        'description' => 'Update User',
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'delete User',
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'updateCountry',
            'deleteCountry',
            'acceptCountry',
            'updateUser',
            'deleteUser',
            'author',
        ],
    ],
    'deleteOwnProfile' => [
        'type' => 2,
        'description' => 'Delete own profile',
        'ruleName' => 'isAuthor',
    ],
];
