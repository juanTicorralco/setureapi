<?php

class PutController{
    /* PUT petition for modificated data */ 
    public function putData($table, $data, $id, $nameId){
        $response= PutModel :: putData($table, $data, $id, $nameId);
        if($response == "The Process was Successfull"){
            $return= new PutController();
            $return -> responseData($response, "PUT");
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found",
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }
    }

    /* GET Petition with Filter */
    static public function getFilterData($table, $linkTo, $equalTo,$orderBy, $orderMode, $startAt, $endAt, $select){
        $response = GetModel::getFilterData($table, $linkTo, $equalTo,$orderBy, $orderMode, $startAt, $endAt, $select);
        return $response;
    }

    /* response of de data */
    public function responseData($response, $metodh){
        if(!empty($response)){
            $json = array (
                "status" => 200,
                "result" => $response
            );
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found",
                "metodh" => $metodh
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }
}