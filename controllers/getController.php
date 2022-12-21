<?php
class GetController {
    /* GET Petition Not Filter */
    public function getData($table, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getData($table, $orderBy, $orderMode, $startAt, $endAt, $select);
        
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* GET Petition with Filter */
    public function getFilterData($table, $linkTo, $equalTo,$orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getFilterData($table, $linkTo, $equalTo,$orderBy, $orderMode, $startAt, $endAt, $select);
       
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* GET Petition relation tables not Filter */
    public function getRelData($rel, $type,$orderBy, $orderMode, $startAt, $endAt, $select){
        $response = GetModel::getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt, $select);
        
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* GET Petition relation tables with Filter */
    public function getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);
        
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* get petition for search */
    public function getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select );
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* GET Petition relation tables with Filter Search */
    public function getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select);
    
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "result" => $response,
                "total" => count($response)
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* get petition for search */
    public function getBetweenData($table, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getBetweenData($table, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select );
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /* GET Petition relation tables with Filter Search */
    public function getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){
        
        $response = GetModel::getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select);
    
        if(!empty($response) && is_array($response)){
            $json = array (
                "status" => 200,
                "result" => $response,
                "total" => count($response)
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found"
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

}