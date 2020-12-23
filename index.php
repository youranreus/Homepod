<?php
//Api Center
//2020.10.17
//youranreus
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
include 'vendor/autoload.php';
use \NoahBuscher\Macaw\Macaw;
Macaw::get('/', 'App\X\X@hello');
Macaw::get('/X/(:any)', 'App\X\X@go');
Macaw::get('/wiki/(:any)', 'App\Wiki\Wiki@go');
Macaw::dispatch();
