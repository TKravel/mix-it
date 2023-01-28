<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ReqModel.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Symfony\Component\HttpClient\HttpClient;

class ReqController extends BaseController {

    public function landing(Request $request, Response $response){
        $REQ_MODEL = new ReqModel();
        
        $popularData = $REQ_MODEL->getLandingPopularData();
        $latestData = $REQ_MODEL->getLandingLatestData();

        $args = ['popularDrinks'=>$popularData, 'latestDrinks'=>$latestData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/landingPage.twig', $args);
    }

    public function drinkById(Request $request, Response $response, $args){
        $REQ_MODEL = new ReqModel();
        $drinkId = $args['id'];

        $drinkData = $REQ_MODEL->getDrinkById($drinkId);

        $args = ['drink'=>$drinkData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/drinkDetails.twig', $args);
    }

    public function drinkByName(Request $request, Response $response, $args){
        $REQ_MODEL = new ReqModel();
        $name = $args['name'];

        $drinkData = $REQ_MODEL->getDrinkByName($name);

        $args = ['drink'=>$drinkData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/drinkDetails.twig', $args);
    }

    public function getPopularDrinks(Request $request, Response $response){
        $REQ_MODEL = new ReqModel();

        $drinkData = $REQ_MODEL->getPopularDrinks();

        $args = ['popularDrinks'=>$drinkData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/popularPage.twig', $args);
    }

    public function getNewDrinks(Request $request, Response $response){
        $REQ_MODEL = new ReqModel();

        $drinkData = $REQ_MODEL->getNewDrinks();

        $args = ['newDrinks'=>$drinkData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/latestPage.twig', $args);
    }

    public function getCategoryPage(Request $request, Response $response, $args){
        $cat = $args['category'];
        $displayableName = str_replace("_", " ", $cat);
        $displayableName = str_replace("+", "/", $displayableName);

        $args = ['dataset'=>$cat, 'headerText'=> $displayableName];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/categoryPage.twig', $args);
    }

    public function getCategoryData(Request $request, Response $response, $args){
        $category = $args['category'];

        $REQ_MODEL = new ReqModel();

        $drinkData = $REQ_MODEL->getDrinksByCategory($category);
        $body = $response->getBody();
        $body->write(json_encode($drinkData));
        return $response;
    }



    public function randomDrink(Request $request, Response $response){
        $REQ_MODEL = new ReqModel();

        $drinkData = $REQ_MODEL->getRandomDrink();
        $args = ['drink'=>$drinkData];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/drinkDetails.twig', $args);
    }

    public function getSimilarDrinks(Request $request, Response $response){
        $data = $request->getParsedBody();
        $REQ_MODEL = new ReqModel();

        $drinkData = $REQ_MODEL->getSimilarDrinks($data['ingredents'], $data['name']);
        $body = $response->getBody();
        $body->write(json_encode($drinkData));
        return $response;
    }

    public function autoComplete(Request $request, Response $response){
        $data = $request->getParsedBody();
        $REQ_MODEL = new ReqModel();

        $listData = $REQ_MODEL->getAutoData($data['userInput']);
        $body = $response->getBody();
        $body->write(json_encode($listData));
        return $response;
    }

}