<?php

use App\Core\App;
use App\Core\Database\{QueryBuilder, Connection};

date_default_timezone_set('Africa/Lagos'); 

App::bind('config', require 'config.php');

App::bind('db_connection', Connection::make(App::get('config')['database']) );

App::bind('database', new QueryBuilder(
    Connection::make(App::get('config')['database'])
));

App::bind('jwt', App::get('config')['jwt'] );

App::bind('folder', App::get('config')['folder'] );