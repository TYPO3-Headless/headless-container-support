<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Headless Container Support',
    'description' => 'Extension that adds container Support for Headless extension',
    'category' => 'plugin',
    'author' => 'Fabio Norbutat',
    'author_email' => 'fabio.norbutat@live.de',
    'state' => 'beta',
    'version' => '1.0.0-beta',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.99.9',
            'container' => '3.0.4-3.99.99',
            'headless' => '1.6.0-1.99.99',
        ]
    ],
];
