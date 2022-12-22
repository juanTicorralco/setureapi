<?php

class DeleteController{
    /* Petition DELETE for eliminate data */
    public function deleteData($table, $id, $nameId){
        $response = DeleteModel :: deleteData($table, $id, $nameId);
        if($response == "The Process was Successfull"){
            $return = new DeleteController ();
            $return -> responseData($response, "DELET");
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found",
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }
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