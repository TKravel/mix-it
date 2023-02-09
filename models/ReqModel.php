<?php

require __DIR__ . '/BaseModel.php';
require __DIR__ . '/drinkNames.php';

/**
 * ReqModel Class
 * 
 * Model class for fetching drink data from a third party API
 */
class ReqModel extends BaseModel {

    /**
     * Fetches landing page popular drink data from API, parses data to only return drink id, drink name, and drink img url
     *
     * @return array drink data for 10 popular drinks
     */
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

    /**
     * Fetches new drink data for landing page. Parses data returning only drink id, drink name, and drink img URL
     *
     * @return array drink data for 10 newest drinks
     */
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

    /**
     * Fetch drink data for single drink by id. Package only needed data and return it
     *
     * @param string $id drink id
     * @return array drink data
     */
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

    /**
     * Fetch single drink data by drink name. Package and return only needed properties
     *
     * @param string $name drink name
     * @return void
     */
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

    /**
     * Fetch all popular drinks and only return drink id, drink name, and drink img URL
     *
     * @return array popular drinks
     */
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

    /**
     * Fetch all new drinks and only return drink id, drink name, and drink img URL
     *
     * @return array new drinks
     */
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

    /**
     * Fetch drinks by category returning only drink id, drink name, drink img URL
     *
     * @param string $category name of category
     * @return void
     */
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

    /**
     * Fetches random drink, packages only the needed data before returning it
     *
     * @return array random drink data
     */
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

    /**
     * fetches similar drinks based off of top two ingredients. Attempts to return 10 drinks at most, filtering out the main drink passed in
     *
     * @param array $ingredents top two ingredients
     * @param string $name original drink name
     * @return array similar drinks
     */
    public function getSimilarDrinks($ingredents, $name){
        foreach($ingredents as $ingredent){
            if(str_contains($ingredent, " ")){
                $ingredent = str_replace(" ", "_", $ingredent);
            }
        }

        $endRoute = "/filter.php";
        $params = ["i"=>join(",", $ingredents)];

        // first search for drinks with both top ingredents
        $callData = $this->makeRequest($endRoute, $params);
        // remove current drink from similar list
        foreach($callData['drinks'] as $key => $drink){
            if($drink['strDrink'] == $name){
                unset($callData['drinks'][$key]);
            }
        }

        // if count is less then 10 continue search but one use top ingredent
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

    /**
     * Return array of drink names used for auto complete
     *
     * @param string $inputStr search field current value
     * @return array drink names that begin with passed in param
     */
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