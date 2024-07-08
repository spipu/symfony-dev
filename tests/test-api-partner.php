<?php

require_once __DIR__ . '/inc/init.php';

$api = new EngineApiPartner(
    'https://symfonydev.lxc/api',
    'b45dbcb30ceb5e7b82ab589334833762472e5ff53ba68cbfc67cd0047934fc77',
    'c3c4980cee649badabc215b43a1e880b55136591b0fa0b6454b1b112be1ba456'
);

$api->get('/hello_world', ['name' => 'Foo']);
$api->get('/version');
$api->post(
    '/test/1',
    ['name' => 'Foo'],
    json_encode([
        'required_rows' => [
            [
                'test_boolean'  => 0,
                'test_datetime' => date('Y-m-d H:i:s'),
                'test_integer'  => 45,
                'test_number'   => 201,
                'test_string'   => 'spipu',
            ]
        ],
        'default_rows'=> [
            []
        ]
    ])
);
