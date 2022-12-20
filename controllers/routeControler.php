<?php

class RouetesController{
    
    /*  ruta principal */

    public function index(){
        include "routes/route.php";
    }

    static public function dbPrincipal(){
        return "wesharp2";
    }

    static public function tableProtected(){
        $table = ["users","disputes","messages","orders","sales"];
        return $table;
    }

    static public function validacionCampos($cmpo, $tipo){
        if(preg_match('/^[A-Za-z0-9]{1,}$/', $cmpo) && $tipo == "tabla"){
            return $cmpo;
        }else if(preg_match('/^[A-Za-z0-9_,]{1,}$/', $cmpo) && $tipo == "select"){
            return $cmpo;
        }else{
            return "invalidate";
        }
    }

    public function StatusResponse($code){
        if($code == "badResponse"){
            $json = array(
                'status' => 400,
                'result' => "no found"
            );
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
}