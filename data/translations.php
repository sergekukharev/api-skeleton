<?php


return [
    'testTranslation' => [
        'id' => 'testTranslation',
        'translations' => [
            'en_US' => [
                'locale' => '',
                'value' => 'this is a test translation',
            ],
            'pt_BR' => [
                'locale' => '',
                'value' => 'esta é uma tradução de teste',
            ],
            'uk_UA' => [
                'locale' => '',
                'value' => 'це тестовий переклад',
            ],
        ],
    ],
    'withInterpolation' => [
        'id' => 'withInterpolation',
        'translations' => [
            'en_US' => [
                'locale' => '',
                'value' => 'Hello, %1',
            ],
            'pt_BR' => [
                'locale' => '',
                'value' => 'Oi, %1',
            ],
            'uk_UA' => [
                'locale' => '',
                'value' => 'Привіт, %1',
            ],
        ],
    ]
];
