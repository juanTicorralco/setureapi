<?php

class RouetesController{
    
    /*  ruta principal */

    public function index(){
        include "routes/route.php";
    }

    static public function dbPrincipal(){
        return "seture";
    }

    static public function tableProtected(){
        $table = ["users","disputes","messages","orders","sales"];
        return $table;
    }

    static public function validacionCampos($cmpo, $tipo){
        if(preg_match('/^[A-Za-z0-9]{1,}$/', $cmpo) && $tipo == "tabla"){
            return $cmpo;
        }else if(preg_match('/^[A-Za-z0-9_,*]{1,}$/', $cmpo) && $tipo == "campo"){
            return $cmpo;
        }else if(preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\.\\,\\@\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $cmpo) && $tipo == "global"){
            return $cmpo;
        }else if(preg_match('/^[A-Za-z]{1,}$/', $cmpo) && $tipo == "simple"){
            return $cmpo;
        }else if(preg_match('/^[0-9]{1,}$/', $cmpo) && $tipo == "numero"){
            return $cmpo;
        } else{
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
        if($code == "userResponse"){
            $json = array(
                'status' => 400,
                "result" => "you are no autorized for make this request"
            );
            echo json_encode($json, http_response_code($json["status"]));
        }
        if($code == "tokenExpire"){
            $json = array(
                'status' => 303,
                'result' => "Error: the token has expired"
            );
            echo json_encode($json, http_response_code($json['status']));
        }
        if($code == "tokenNoAutorize"){
            $json = array(
                'status' => 400,
                "result" => "Error: this user no autorized"
            );
            echo json_encode($json, http_response_code($json["status"]));
        }
        if($code == "tokenNeedLogin"){
            $json = array(
                'status' => 400,
                "result" => "You need login"
            );
            echo json_encode($json, http_response_code($json["status"]));
        }
        if($code == "columsNoDB"){
            $json = array(
                'status' => 400,
                'result' => "Error: Fields in the form do not match the database"
            );
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
}