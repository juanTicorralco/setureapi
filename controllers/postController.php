<?php 

use Firebase\JWT\JWT;

class PostController{
    /* BRING the list of the columns of the table to change */
    static public function getColumnsData($table, $db){
        $response= PostModel::getColumnsData($table, $db);
        return $response;
    } 
    
    /* POST petition for create data */ 
    public function postData($table, $data){

        $response= PostModel :: postData($table, $data);
        
        if(is_array($response)){
            $return= new PostController();
            $return -> responseData($response, "POST", null);
        }else{
            $json = array (
                "status" => 404,
                "result" => "no found",
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }
    }

    /* POST petition for create register */ 
    public function postRegister($table, $data){
        if(isset($data["password_user"]) && $data["password_user"] != null){
            $crypt= crypt($data["password_user"], '$2a$07$pdgtwzaldisoqrtrswqpxzasdte$');
            $data["password_user"]= $crypt;
            $response= PostModel :: postData($table, $data);
            if(is_array($response)){
                $return= new PostController();
                $return -> responseData($response, "POST", null);
            }else{
                $json = array (
                    "status" => 404,
                    "result" => "no found",
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }

    /* POST petition for login users */ 
    public function postLogin($table, $data){
        if(isset($data["email_user"]) && isset($data["password_user"])){
            $response = GetModel::getFilterData($table, "email_user", $data["email_user"],null, null, null, null, "picture_user, displayname_user, username_user, country_user, city_user, phone_user, address_user, method_user, wishlist_user, date_created_user, date_updated_user, rol_user, password_user, id_user, email_user, token_user, token_exp_user, verificated_user, map_user");

            if(!empty($response)){
                /* password hash */
                $crypt= crypt($data["password_user"], '$2a$07$pdgtwzaldisoqrtrswqpxzasdte$');
                if($response[0]->password_user == $crypt){

                    /* create JWT */
                    $time = time();
                    $key = "amkdheto1j4g32k6j4k3b5j6b4ndcjmf67dncurfswxfrr";

                    $token = array(
                        "iat" => $time, //time start of token 
                        "exp" => $time + (60*60*24), //time of expire the token (1 day)
                        'data' => [
                            "id" => $response[0]->id_user,
                            "email" => $response[0]->email_user
                        ]
                    );

                    $jwt = JWT::encode($token, $key, "HS256");

                    /* update the database with the token user */
                    $data = array(
                        "token_user" => $jwt,
                        "token_exp_user" => $token["exp"]
                    );

                    $update = PutModel::putData($table, $data, $response[0]->id_user, "id_user");
                    
                    if($update=="The Process was Successfull"){

                        $response[0]->token_user = $jwt;
                        $response[0]->token_exp_user=$token["exp"];

                        $return= new PostController();
                        $return -> responseData($response, "POST", null);
                    }
                }else {
                    $response=null;
                    $return= new PostController();
                    $return -> responseData($response, "POST", "Wrong Password");
                }
            }else {
                $response=null;
                $return= new PostController();
                $return -> responseData($response, "POST", "Wrong Email");
            }
        }else{
            $response=null;
            $return= new PostController();
            $return -> responseData($response, "POST", "Wrong cs");
        }
    }

    /* response of de data */
    public function responseData($response, $metodh, $error){
        if(!empty($response)){

            /* we remove the password from the response */
            if(isset($response[0]->password_user)){
                unset($response[0]->password_user);
            }
            $json = array (
                "status" => 200,
                "result" => $response
            );
        }else{
            if($error != null){
                $json = array (
                    "status" => 404,
                    "result" => $error
                );
            }else {
                $json = array (
                    "status" => 404,
                    "result" => "no found",
                    "metodh" => $metodh
                );
            }
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

}