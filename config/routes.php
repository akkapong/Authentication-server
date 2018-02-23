<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
// $router = new Phalcon\Mvc\Router();

// $router->addGet("/basic", "Index::basic");
// $router->addGet("/basic-list", "Index::basicList");
// $router->addGet("/test-mongo", "test::mongoInsert");

// return $router;

use Phalcon\Mvc\Router\Group as RouterGroup;

$router->removeExtraSlashes(true);

$router->setDefaults(array(
    'namespace'  => 'Core\Controllers',
    'controller' => 'error',
    'action'     => 'page404'
));

//==========Route for api==========
$authen = new RouterGroup(array(
    'namespace' => 'Authens\Controllers'
));

$authen->setPrefix("/api");

//==== Start : authen Section ====//
$authen->addGet('/authens', [
    'controller' => 'authen',
    'action'     => 'getAuthen',
]);

$authen->addGet('/authens/{id}', [
    'controller' => 'authen',
    'action'     => 'getAuthendetail',
    'params'     => 1
]);

$authen->addPost('/authens', [
    'controller' => 'authen',
    'action'     => 'postAuthen',
]);

$authen->addPost('/authens/{id}', [
    'controller' => 'authen',
    'action'     => 'updateAuthen',
    'params'     => 1
]);

$authen->addDelete('/authens/{id}', [
    'controller' => 'authen',
    'action'     => 'deleteAuthen',
    // 'params'     => 1
]);
//==== End : authen Section ====//

//=== Start: manage access token ===//
$authen->addGet('/request_token/{id}', [
    'controller' => 'accesstoken',
    'action'     => 'getAccesstoken',
    'params'     => 1
]);

$authen->addGet('/check_token', [
    'controller' => 'accesstoken',
    'action'     => 'getCheckaccesstoken',
]);

$authen->addPost('/refresh_token', [
    'controller' => 'accesstoken',
    'action'     => 'postRefreshtoken',
]);
//=== End: manage access token ===//


//=== Start: manage crypto ===//
$authen->addGet('/encrypt/{id}', [
    'controller' => 'crypto',
    'action'     => 'getEncrypt',
    'params'     => 1
]);

$authen->addGet('/decrypt/{id}', [
    'controller' => 'crypto',
    'action'     => 'getDecrypt',
    'params'     => 1
]);
//=== End: manage crypto ===//

$router->mount($authen);

return $router;
