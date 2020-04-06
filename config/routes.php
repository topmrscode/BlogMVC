<?php

// Routes related to the user
$router->use('GET', '/user/create', new App\Controllers\UserController(), 'createView');
$router->use('POST', '/user/create', new App\Controllers\UserController(), 'create');
$router->use('GET', '/user/delete', new App\Controllers\UserController(), 'delete');
$router->use('GET', '/user/verify', new App\Controllers\UserController(), 'validatedEmail');

// Routes related to the session
$router->use('GET', '/session/create', new App\Controllers\SessionController(), 'createView');
$router->use('POST', '/session/create', new App\Controllers\SessionController(), 'create');
$router->use('GET', '/session/delete', new App\Controllers\SessionController(), 'delete');

// Routes related to static pages
$router->use('GET', '/', new App\Controllers\ArticleController(), 'indexArticle');

// Routes related to the admin
$router->use('GET', '/admin', new App\Controllers\AdminController(), 'index');

// A FAIRE
$router->use('GET', '/admin/users', new App\Controllers\AdminController(), 'users');
$router->use('GET', '/admin/category', new App\Controllers\AdminController(), 'categories');

// CES TROIS LA N'ONT PAS DE VUE PARCE QU'ILS FONT UNE ACTION (BAN, ACTIVE...) PUIS REDIRIGE VERS /admin/users
$router->use('GET', '/admin/users/activate', new App\Controllers\AdminController(), 'activateUser');
$router->use('GET', '/admin/users/bann', new App\Controllers\AdminController(), 'bannUser');
$router->use('GET', '/admin/users/delete', new App\Controllers\AdminController(), 'deleteUser');
$router->use('GET', '/admin/users/change_group', new App\Controllers\AdminController(), 'changeGroupUser');

// Routes related to admin pages
$router->use('POST', '/admin/users/create', new App\Controllers\AdminController(), 'createUser');

// reoute related to admin category page => redirige vers admin/category
$router->use('POST', '/admin/category/create', new App\Controllers\AdminController(), 'createCategory');
$router->use('POST', '/admin/category/modify', new App\Controllers\AdminController(), 'modifyCategory');
$router->use('GET', '/admin/category/delete', new App\Controllers\AdminController(), 'deleteCategory');

// Routes related to articles
$router->use('GET', '/article/index', new App\Controllers\ArticleController(), 'indexArticle');

$router->use('GET', '/article/manage', new App\Controllers\ArticleController(), 'manageArticle');
$router->use('POST', '/article/create', new App\Controllers\ArticleController(), 'createArticle');

$router->use('GET', '/article/view', new App\Controllers\ArticleController(), 'viewArticle');

// CES DEUX LA N'ONT PAS DE VUE PARCE QU'ILS FONT UNE ACTION PUIS REDIRIGE VERS /article/manage
$router->use('GET', '/article/delete', new App\Controllers\ArticleController(), 'deleteArticle');
$router->use('POST', '/article/modify', new App\Controllers\ArticleController(), 'modifyArticle');

// Routes related to COMMENTS nont pas de vue car redirige vers article/view

$router->use('POST', '/comment/create', new App\Controllers\CommentController(), 'createComment');
$router->use('GET', '/comment/delete', new App\Controllers\CommentController(), 'deleteComment');
$router->use('POST', '/comment/modify', new App\Controllers\CommentController(), 'modifyComment');


// Routes related to Category 

