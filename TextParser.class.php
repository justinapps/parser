<?php
/*
Justinas Ulevicius
09/07/2016

The following class takes a user's 
search sentence which is then parsed and broken 
down into parameters such as 
the area, price range, number of bedrooms, 
and the search type (buy or rent).
*/


class TextParser{

    private $key = '2b1415daec4d50f49958b68ad024e453373766fc';
    private $DaftAPI;
    private $searchString;
    private $searchArr;
    private $rentOrSale;
    private $min_price;
    private $max_price;
    private $areas;
    private $bedrooms;
    private $min_bedrooms;
    private $max_bedrooms;


    // Constructor.
    public function __construct($searchString){
        $this->searchString = $searchString;
        // In case user enters an odd case of capitalization (eg. all caps).
        $this->searchArr = explode(' ', ucwords(strtolower($searchString)));
        
        $this->DaftAPI = new SoapClient(
            "http://api.daft.ie/v2/wsdl.xml",
            array('features' => SOAP_SINGLE_ELEMENT_ARRAYS)
        );
    }
    

    // Decide if user wants to buy or rent.
    public function buyOrSell(){
        $rentArray = array('1' => 'Let', '2' => 'Rental', '3' => 'Rent', '4' => 'Rentals', '5' => 'Renting');
        $sellArray = array('1' => 'Buy', '2' => 'Sale', '3' => 'Sell', '4' => 'Selling');

        foreach($this->searchArr as $elem){
            if(in_array($elem, $rentArray)){
                return $this->rentOrSale = 'To Let';
            }
            else if(in_array($elem, $sellArray)){
                return $this->rentOrSale = 'For Sale';
            }
        }
    }
    

    /* 
    Grab areas from the API and check if
    user input contains any of these.
    */
    public function getArea(){
        $allAreas = $this->DaftAPI->areas(array(
            'api_key'   => $this->key,
            'area_type' => "area"
        ));

        $areasArr = array();
        foreach($allAreas->areas as $area){
            if(in_array($area->name, $this->searchArr)){
                return intval($area->id);
            }
        }
    }


    // Get the prices - at least 200 euro.
    public function getPrice(){
        $prices = array();
        foreach($this->searchArr as $elem){
            if(is_numeric($elem) and ($elem >= 200)){
                array_push($prices, $elem);
            }
        }
        if(count($prices)===1){
            $this->min_price = $prices[0];
            return intval($this->min_price);
        }
        elseif(count($prices)===2){
            $this->min_price = min($prices);
            $this->max_price = max($prices);
            return intval($this->min_price);
        }
    }
    

    public function getmaxPrice(){
        return intval($this->max_price);
    }
    

    // Get bedrooms - up to 7 beds.
    public function getBdrms(){
        $beds = array();
        foreach($this->searchArr as $bedrooms){
            if(is_numeric($bedrooms) and ($bedrooms >= 1) and ($bedrooms <= 7)){
                array_push($beds, $bedrooms);
            }
        }
        if(count($beds)===1){
            return intval($this->$bedrooms = $beds[0]);
        }
        elseif(count($beds)===2){
            $this->min_bedrooms = min($beds);
            $this->max_bedrooms = max($beds);
        }
    }


    public function getminBeds(){
        return $this->min_bedrooms;
    }


    public function getmaxBeds(){
        return intval($this->max_bedrooms);
    }


    /*
    Create the query array containing user
    specified parameters that is to be passed
    to the API.
    */
    public function getQuery(){
        $queryArray = array();
        $parameters = array();
        $this->areas = array();
        $area = $this->getArea();
        array_push($this->areas, $area);

        $queryArray['areas'] = $this->areas;
        $queryArray['min_price'] = $this->getPrice();
        $queryArray['max_price'] = $this->getmaxPrice();
        $queryArray['bedrooms'] = $this->getBdrms();
        $queryArray['min_bedrooms'] = $this->getminBeds();
        $queryArray['max_bedrooms'] = $this->getmaxBeds();

        $parameters['api_key'] = $this->key;
        $parameters['query'] = $queryArray;

        return $parameters;
    }


    // Calls the API with the user's search preferences.
    public function getResults(){
        $parameters = array();
        $parameters = $this->getQuery();

        $this->rentOrSale = $this->buyOrSell();

        if($this->rentOrSale === 'For Sale'){
            $response = $this->DaftAPI->search_sale($parameters);
            $results = $response->results;
            return $results;
        }
        else{
            $response = $this->DaftAPI->search_rental($parameters);
            $results = $response->results;
            return $results;
        }
    }
}
