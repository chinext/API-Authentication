<?php

return [
    'database' => [
        'name' => 'patricia-test',
        'username' => 'root',
        'password' => 'password',
        'connection' => 'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
    'jwt' => [
        'key' => 'sample_key',
        'iss' => 'http://example.com',
        'aud' => 'http://example.org',
        'iat' => 1356999524,
        'nbf' => 1357000000        
    ],
    'folder'=>'/patricia-api-authentication/' // use when hosting on a sub directory or localhost other set empty 

];







 
