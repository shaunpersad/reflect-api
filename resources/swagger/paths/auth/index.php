<?php
return [
    '/v1/auth' => [
        'post' => [
            'tags' => ['auth'],
            'summary' => 'Logs in a user. Gives an access token.',
            'parameters' => [
                [
                    'name' => 'email',
                    'description' => "The user's email address",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'password',
                    'description' => "The user's password",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
            ],
            'responses' => [
                '200' => [
                    'description' => 'The resulting access token.',
                    '$ref' => '#/definitions/AccessToken'
                ]
            ]
        ]
    ],

    '/v1/auth/logout' => [
        'post' => [
            'tags' => ['auth'],
            'summary' => 'Logs a user out. Invalidates all access tokens.',
            'responses' => [
                '200' => [
                    'description' => 'The logged out user.',
                    '$ref' => '#/definitions/User'
                ]
            ]
        ]
    ],

    '/v1/auth/register' => [
        'post' => [
            'tags' => ['auth'],
            'summary' => 'Registers a user and logs them in.',
            'parameters' => [
                [
                    'name' => 'name',
                    'description' => "The user's full name",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'email',
                    'description' => "The user's email address",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'password',
                    'description' => "The user's chosen password",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'password_confirmation',
                    'description' => "A repeat of the user's chosen password",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
            ],
            'responses' => [
                '200' => [
                    'description' => 'The resulting access token.',
                    '$ref' => '#/definitions/AccessToken'
                ]
            ]
        ]
    ],


    '/v1/auth/request-password-reset' => [
        'post' => [
            'tags' => ['auth'],
            'summary' => 'Sends a password reset email to the supplied email address.',
            'parameters' => [
                [
                    'name' => 'email',
                    'description' => "The user's email address",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'The logged out user.',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'email' => [
                                'type' => 'string'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],

    '/v1/auth/reset-password' => [
        'post' => [
            'tags' => ['auth'],
            'summary' => "Resets a user's password.",
            'parameters' => [
                [
                    'name' => 'token',
                    'description' => "The password reset token",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'email',
                    'description' => "The user's email address",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'password',
                    'description' => "The user's chosen password",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
                [
                    'name' => 'password_confirmation',
                    'description' => "A repeat of the user's chosen password",
                    'in' => 'formData',
                    'type' => 'string',
                    'required' => true
                ],
            ],
            'responses' => [
                '200' => [
                    'description' => 'The user whose password was reset.',
                    '$ref' => '#/definitions/User'
                ]
            ]
        ]
    ]
];