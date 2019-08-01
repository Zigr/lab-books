<?php

/*
 * URL pattern
 * 
 */

return [
    'APP_ENV' => 'dev',
    'database' => [
        'adapter' => [
            /** MySQLi */
            'driver' => 'Mysqli',
            'database' => 'frameworks',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8'
        /** SQLite */
        /*
          'driver'   => 'Pdo_Sqlite',
          'database' => 'books.db',
         */

        /** Pdo_Mysql */
        /*
          'driver' => 'Pdo_Mysql',
          'dsn' => 'mysql:host=localhost;dbname=frameworks',
          'username' => '',
          'password' => '',
          'options' => array(
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
          ),
         */
        ],
        /** Table prefix to create tables in db scheme */
        'table_prefix' => 'lab_',
    ],
    // ['uri','defaults','requirements','options','host','schemes','methods','condition'] 
    'routes' => [
        'index' => [
            'uri' => '/',
            'defaults' => ['controller' => 'App\\Controller\\IndexController@index'],
            'requirements' => [],
            'options' => [],
            'requirements' => [],
            'host' => '',
            'schemes' => [],
            'methods' => ['GET'],
            'condition' => ''
        ],
        'book_list' => [
            'uri' => '/books',
            'defaults' => ['controller' => 'App\\Controller\\IndexController@books'],
            'methods' => ['GET'],
        ],
        'book_search' => [
            'uri' => '/books/{search_string}',
            'requirements' => ['search_string' => '.*'],
            'defaults' => ['controller' => 'App\\Controller\\IndexController@books'],
            'methods' => ['GET'],
        ],
        'book_edit' => [
            'uri' => '/book/{id}',
            'defaults' => ['controller' => 'App\\Controller\\IndexController@book', 'id' => ''],
            'requirements' => ['id' => '(\d+)?'],
            'methods' => ['GET', 'POST','DELETE']
        ],
        'publisher_edit' => [
            'uri' => '/publisher/{id}',
            'defaults' => ['controller' => 'App\\Controller\\PublisherController@publisher', 'id' => ''],
            'requirements' => ['id' => '(\d+)'],
            'methods' => ['GET', 'POST', 'DELETE']
        ],
        'publisher_delete' => [
            'uri' => '/publisher/delete/{id}',
            'requirements' => ['id' => '(\d+)'],
            'defaults' => ['controller' => 'App\\Controller\\PublisherController@delete'],
            'methods' => ['POST']
        ],
        'publisher_list' => [
            'uri' => '/publishers/{search_string}',
            'requirements' => ['search_string' => '.*'],
            'defaults' => ['controller' => 'App\\Controller\\PublisherController@publishers', 'search_string' => ''],
            'methods' => ['GET'],
        ],
        'author_edit' => [
            'uri' => '/author/{id}',
            'defaults' => ['controller' => 'App\\Controller\\AuthorController@author', 'id' => ''],
            'requirements' => ['id' => '(\d+)?'],
            'methods' => ['GET', 'POST', 'DELETE']
        ],
        'author_list' => [
            'uri' => '/authors/{search_string}',
            'requirements' => ['search_string' => '.*'],
            'defaults' => ['controller' => 'App\\Controller\\AuthorController@authors', 'search_string' => ''],
            'methods' => ['GET'],
        ],
        'category_edit' => [
            'uri' => '/category/{id}',
            'defaults' => ['controller' => 'App\\Controller\\CategoryController@category', 'id' => ''],
            'requirements' => ['id' => '(\d+)?'],
            'methods' => ['GET', 'POST', 'DELETE']
        ],
        'category_ajax' => [
            'uri' => '/category/ajax',
            'defaults' => ['controller' => 'App\\Controller\\CategoryController@categoryAjax'],
            'methods' => ['GET'],
        ],
        'file_process' => [
            'uri' => '/file/{id}',
            'defaults' => ['controller' => 'App\\Controller\\FileController@process', 'id' => ''],
            'requirements' => ['id' => '(\d+)?'],
            'methods' => ['POST', 'DELETE']
        ],
        'file_display' => [
            'uri' => '/image/{template}/{filename}',
            'defaults' => [
                'controller' => 'App\\Controller\\FileController@display'
            ],
            'requirements' => [
                'template' => 'small|medium|large',
                'filename' => '[ \w\.]{32,40}',
            ],
            'methods' => ['GET']
        ],
        'migration' => [
            'uri' => '/migration/{which}',
            'defaults' => [
                'controller' => 'App\\Controller\\IndexController@migrate',
                'which' => ''],
            'requirements' => ['which' => 'up|down|seed|truncate'],
            'methods' => ['GET'],
        ],
    ],
    'list_pages' => [
        'per_page' => 20,
    ],
    'image' => [
        'max_upload_size' => 100000,
        'upload_path' => APPPATH . DS . 'storage/files',
    ],
];

