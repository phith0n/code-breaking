<?php
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\File;

$router->get('/', function (Request $request) use ($router) {
    return view('index');
});

$router->get('/server/editor', 'EditorController@main');
$router->post('/server/editor', 'EditorController@main');
