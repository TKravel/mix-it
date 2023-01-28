<?php

require __DIR__ . '/BaseModel.php';
require __DIR__ . '/drinkNames.php';

class ReqModel extends BaseModel {

    public function getLandingPopularData(){
        $endRoute = '/popular.php';
        $params = null;
        $callData = $this->makeRequest($endRoute, $params);
        $drinks = array_slice($callData['drinks'], 0,10, true);
        $parsedData = [];

        foreach($drinks as $drink){
            $parsedData[] = ['id'=>$drink['idDrink'], 'name'=>$drink['strDrink'], 'img'=>$drink['strDrinkThumb'] . '/preview'];
        };

        return $parsedData;
    }

    public function getLandingLatestData(){
        $endRoute = '/latest.php';
        $params = null;
        $callData = $this->makeRequest($endRoute, $params);
        $drinks = array_slice($callData['drinks'], 0,10, true);
        $parsedData = [];

        foreach($drinks as $drink){
            $parsedData[] = ['id'=>$drink['idDrink'], 'name'=>$drink['strDrink'], 'img'=>$drink['strDrinkThumb'] . '/preview'];
        };

        return $parsedData;
    }

    public function getDrinkById($id){
        $endRoute = "/lookup.php";
        $params = ['i'=>$id];

        $callData = $this->makeRequest($endRoute, $params);
        $drink = $callData['drinks'][0];

        $parsedData = [
            'id'=>$drink['idDrink'],
            'name'=>$drink["strDrink"],
            'type'=>$drink["strAlcoholic"],
            'category'=>$drink["strCategory"],
            'img'=>$drink["strDrinkThumb"],
            'instructions'=>$drink["strInstructions"],
            'glass'=>$drink["strGlass"],
            'tags'=>explode(',', $drink["strTags"]),
        ];

        $ingredents = [];
        for($i = 1; $i < 16; $i++){
            $ingredientKey = 'strIngredient' . $i;
            $amountKey = 'strMeasure' . $i;
            if($drink[$ingredientKey] !== null){
                    $ingredents[] = [$drink[$amountKey],$drink[$ingredientKey]];
            }
        }
        $parsedData['ingredents'] = $ingredents;
        return $parsedData;
    }

    public function getDrinkByName($name){
        $endRoute = "//search.php";
        $params = ['s'=>$name];

        $callData = $this->makeRequest($endRoute, $params);
        $drink = $callData['drinks'][0];

        $parsedData = [
            'id'=>$drink['idDrink'],
            'name'=>$drink["strDrink"],
            'type'=>$drink["strAlcoholic"],
            'category'=>$drink["strCategory"],
            'img'=>$drink["strDrinkThumb"],
            'instructions'=>$drink["strInstructions"],
            'glass'=>$drink["strGlass"],
            'tags'=>explode(',', $drink["strTags"]),
        ];

        $ingredents = [];
        for($i = 1; $i < 16; $i++){
            $ingredientKey = 'strIngredient' . $i;
            $amountKey = 'strMeasure' . $i;
            if($drink[$ingredientKey] !== null){
                    $ingredents[] = [$drink[$amountKey],$drink[$ingredientKey]];
            }
        }
        $parsedData['ingredents'] = $ingredents;
        return $parsedData;
    }

    public function getPopularDrinks(){
        $endRoute = '/popular.php';
        $params = null;
        $callData = $this->makeRequest($endRoute, $params);
        $drinks = $callData['drinks'];
        $parsedData = [];

        foreach($drinks as $drink){
            $parsedData[] = ['id'=>$drink['idDrink'], 'name'=>$drink['strDrink'], 'img'=>$drink['strDrinkThumb'] . '/preview'];
        };

        return $parsedData;
    }

    public function getNewDrinks(){
        $endRoute = '/latest.php';
        $params = null;
        $callData = $this->makeRequest($endRoute, $params);
        $drinks = $callData['drinks'];
        $parsedData = [];

        foreach($drinks as $drink){
            $parsedData[] = ['id'=>$drink['idDrink'], 'name'=>$drink['strDrink'], 'img'=>$drink['strDrinkThumb'] . '/preview'];
        };

        return $parsedData;
    }

    public function getDrinksByCategory($category){
        $endRoute = '/filter.php';
        $category = str_replace("+", "/", $category);
        $params = ['c'=>$category];
        $callData = $this->makeRequest($endRoute, $params);
        $drinks = $callData['drinks'];
        $parsedData = [];

        foreach($drinks as $drink){
            $parsedData[] = ['id'=>$drink['idDrink'], 'name'=>$drink['strDrink'], 'img'=>$drink['strDrinkThumb'] . '/preview'];
        };

        return $parsedData;
    }

    public function getRandomDrink(){
        $endRoute = '/random.php';
        $params = null;

        $callData = $this->makeRequest($endRoute, $params);
        $drink = $callData['drinks'][0];

        $parsedData = [
            'id'=>$drink['idDrink'],
            'name'=>$drink["strDrink"],
            'type'=>$drink["strAlcoholic"],
            'category'=>$drink["strCategory"],
            'img'=>$drink["strDrinkThumb"],
            'instructions'=>$drink["strInstructions"],
            'glass'=>$drink["strGlass"],
            'tags'=>explode(',', $drink["strTags"]),
        ];

        $ingredents = [];
        for($i = 1; $i < 16; $i++){
            $ingredientKey = 'strIngredient' . $i;
            $amountKey = 'strMeasure' . $i;
            if($drink[$ingredientKey] !== null){
                    $ingredents[] = [$drink[$amountKey],$drink[$ingredientKey]];
            }
        }
        $parsedData['ingredents'] = $ingredents;
        return $parsedData;
    }

    public function getSimilarDrinks($ingredents, $name){
        foreach($ingredents as $ingredent){
            if(str_contains($ingredent, " ")){
                $ingredent = str_replace(" ", "_", $ingredent);
            }
        }

        $endRoute = "/filter.php";
        $params = ["i"=>join(",", $ingredents)];

        $callData = $this->makeRequest($endRoute, $params);
        // remove current drink from similar list
        foreach($callData['drinks'] as $key => $drink){
            if($drink['strDrink'] == $name){
                unset($callData['drinks'][$key]);
            }
        }

        if(count($callData['drinks']) < 10){
            $fillData = $this->makeRequest($endRoute, ["i"=>$ingredents[0]]);
            // remove current drink from similar list
            foreach($fillData['drinks'] as $key => $drink){
                if($drink['strDrink'] == $name){
                    unset($fillData['drinks'][$key]);
                }
            }
            $callData['drinks'] = array_merge($callData['drinks'], $fillData['drinks']);
            return array_slice($callData['drinks'], 0, 10);
        }

        return array_slice($callData['drinks'], 0, 10);
    }

    public function getAutoData($inputStr){
        $drinkNames = include __DIR__ . '/drinkNames.php';
        $drinkList = [];
        foreach($drinkNames as $name){
            if(str_starts_with(strtolower($name), strtolower($inputStr))){
                $drinkList[] = $name;
            }
        }

        if(count($drinkList) > 10) return array_slice($drinkList, 0, 10);
        return $drinkList;
    }

}