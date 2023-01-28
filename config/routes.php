<?php

require __DIR__ . '/../controllers/ReqController.php';


$app->get('/', ReqController::class . ':landing');
$app->get('/temp', ReqController::class . ':helloTemplate');
$app->get('/testEndpoints', ReqController::class . ":testEndpoints");
$app->get('/drinks/details/{id}', ReqController::class . ":drinkById");
$app->get('/drink/{name}', ReqController::class . ":drinkByName");
$app->get('/drinks/random', ReqController::class . ":randomDrink");
$app->get('/drinks/popular', ReqController::class . ":getPopularDrinks");
$app->get('/drinks/new', ReqController::class . ":getNewDrinks");
$app->get('/drinks/category/{category}', ReqController::class . ":getCategoryPage");
$app->get('/drinks/category/search/{category}', ReqController::class . ":getCategoryData");
$app->POST('/drinks/similar', ReqController::class . ":getSimilarDrinks");
$app->POST('/drinks/autocomplete', ReqController::class . ":autoComplete");