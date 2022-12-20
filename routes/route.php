<?php
$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

/* when it doesnt have any petition to the api*/
if (count($routesArray) == 0) {
    $json = array(
        "status" => 404,
        "result" => "not found"
    );

    echo json_encode($json, http_response_code($json["status"]));
    return;
}else {                          /* when it has a petition to the api */
    /* petition GET */
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {

        // peticion get con autorization administrative
        foreach(RouetesController::tableProtected() as $key => $value){
            if($value != "users"){
                if(explode("?", $routesArray[1])[0] == $value || (isset($_GET["rel"]) && explode(",", $_GET["rel"])[0] == $value)){
                    if(isset($_GET["token"])){
                        if($_GET["token"] != "tokenGlobal"){
                            $user = GetModel::getFilterData("users","token_user",$_GET["token"],null,null,null,null,"token_exp_user");
                            if(!empty($user)){
                                $time = time();
                                if($user[0]->token_exp_user < $time){
                                    $json = array(
                                        'status' => 303,
                                        'result' => "Error: the token has expired"
                                    );
                                    echo json_encode($json, http_response_code($json['status']));
                                    return;
                                }
                            }else{
                                $json = array(
                                    'status' => 400,
                                    "result" => "Error: this user no autorized"
                                );
                                echo json_encode($json, http_response_code($json["status"]));
                                return;   
                            }
                        }
                    }else{
                        $json = array(
                            'status' => 400,
                            "result" => "You need login"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }
        }

        /* GET Petition with filter */
        if (isset($_GET['linkTo']) && isset($_GET['equalTo']) && !isset($_GET['rel']) && !isset($_GET['type'])) {

             // peticion get con autorization administrative
             foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }

            /* GET Order Tables  */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and end at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }
            $response = new GetController();
            $response->getFilterData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routesArray[1])[0] == "relations" && !isset($_GET['linkTo']) && !isset($_GET['equalTo'])) {
            
            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }

            /* Get Petition of relation tables not filter */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and ent at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }
            $response = new GetController();
            $response->getRelData($_GET["rel"], $_GET["type"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routesArray[1])[0] == "relations" && isset($_GET['linkTo']) && isset($_GET['equalTo'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }

            /* Get Petition of relation tables with filter */
            /* GET Order Tables  */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {

                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {

                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and ent at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {

                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {

                $startAt = null;
                $endAt = null;
            }
            $response = new GetController();
            $response->getRelFilterData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
        } else if (isset($_GET['linkTo']) && isset($_GET['search'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }
            
            /* get petition for search */
            /* GET Order Tables  */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {

                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {

                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and ent at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {

                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {

                $startAt = null;
                $endAt = null;
            }

            if (explode("?", $routesArray[1])[0] == "relations" && isset($_GET["rel"]) && isset($_GET["type"])) {

                $response = new GetController();
                $response->getSearchRelData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
            } else {

                $response = new GetController();
                $response->getSearchData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
            }
        } else if (isset($_GET['linkTo']) && isset($_GET['between1']) && isset($_GET['between2']) && isset($_GET['filterTo']) && isset($_GET['inTo'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }
            
            /* get petition for between */
            /* GET Order Tables  */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {

                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {

                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and ent at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {

                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {

                $startAt = null;
                $endAt = null;
            }

            if (explode("?", $routesArray[1])[0] == "relations" && isset($_GET["rel"]) && isset($_GET["type"])) {

                $response = new GetController();
                $response->getBetweenRelData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET['between1'], $_GET['between2'], $_GET['filterTo'], $_GET['inTo'], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
            } else {

                $response = new GetController();
                $response->getBetweenData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET['between1'], $_GET['between2'], $_GET['filterTo'], $_GET['inTo'], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
            }
        } else {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["rol"])){
                        $linkTo = "username_user";
                        $equalTo = $_GET["rol"];
                        $response = GetModel::getFilterData("users",$linkTo,$equalTo,null,null,null,null,"rol_user");
                        if(count($response) > 0){
                            if($response[0]->rol_user != "admin"){
                                $json = array(
                                    'status' => 400,
                                    "result" => "you are no autorized for make this request"
                                );
                                echo json_encode($json, http_response_code($json["status"]));
                                return;
                            }
                        }else{
                            $json = array(
                                'status' => 400,
                                "result" => "you are no autorized for make this request"
                            );
                            echo json_encode($json, http_response_code($json["status"]));
                            return;
                        }
                    }else{
                        $json = array(
                            'status' => 400,
                            "result" => "you are no autorized for make this request"
                        );
                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                }
            }

            /* GET Petition not filter */
            /* GET Order Tables  */
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }
            /* GET star and ent at  */
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }
            if(!isset($_GET["select"])){
                $json = array(
                    'status' => 400,
                    'result' => "no found"
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
            
            $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
            $selected = RouetesController::validacionCampos($_GET["select"], "select");
           
            if($tabla == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }
            if($selected == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }

            $response = new GetController();
            $response->getData($tabla, $orderBy, $orderMode, $startAt, $endAt, $selected);
        }
    }
    // /* petition POST */
    // if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

    //     /* BRING the list of the columns of the table to change */
    //     $columns = array();
    //     $dbPrincipal = RouetesController::dbPrincipal();
    //     $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $dbPrincipal);

    //     foreach ($response as $key => $value) {
    //         array_push($columns, $value->item);
    //     }
    //     array_shift($columns);
    //     array_pop($columns);

    //     if (isset($_POST)) {
    //         /* validate that the variables in the PUT fields match the column names in the database */
    //         $count = 0;
    //         foreach (array_keys($_POST) as $key => $value) {
    //             $count = array_search($value, $columns);
    //         }

    //         if ($count > 0) {

    //             /* we give to response of controller for user register */
    //             if (isset($_GET["register"]) && $_GET["register"] == true) {

    //                 /* we give response of the controller for insert data in a table */
    //                 $response = new PostController();
    //                 $response->postRegister(explode("?", $routesArray[1])[0], $_POST);
    //             } else if (isset($_GET["login"]) && $_GET["login"] == true) {

    //                 /* we give response of the controller for insert data in a table */
    //                 $response = new PostController();
    //                 $response->postLogin(explode("?", $routesArray[1])[0], $_POST);
    //             } else if (isset($_GET["token"])) {

    //                 /* Agregamos ecepcion para actualizar sin autorizacion */
    //                 if ($_GET["token"] == "no") {
    //                     if (isset($_GET["except"])) {
    //                         $num = 0;
    //                         foreach ($columns as $key => $value) {
    //                             $num++;
    //                             /* buscamos coincidencias con la ecepcion */
    //                             if ($value == $_GET["except"]) {
    //                                 /* we give response of the controller for insert data in a table */
    //                                 $response = new PostController();
    //                                 $response->postData(explode("?", $routesArray[1])[0], $_POST);
    //                                 return;
    //                             }
    //                         }
    //                         /* cuando no se encuentra coincidencia */
    //                         if ($num == count($columns)) {
    //                             $json = array(
    //                                 'status' => 400,
    //                                 'result' => "Error: the exception does not match the database"
    //                             );
    //                             echo json_encode($json, http_response_code($json["status"]));
    //                             return;
    //                         }
    //                     } else {
    //                         /* cuando no se envia una excepcion */
    //                         if ($num == count($columns)) {
    //                             $json = array(
    //                                 'status' => 400,
    //                                 'result' => "Error: there is no exception"
    //                             );
    //                             echo json_encode($json, http_response_code($json["status"]));
    //                             return;
    //                         }
    //                     }
    //                 } else {


    //                     /* we bring the user according to the tok */
    //                     $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");

    //                     if (!empty($user)) {
    //                         /* validate that the token has not expired */
    //                         $time = time();
    //                         if ($user[0]->token_exp_user > $time) {

    //                             /* we give response of the controller for insert data in a table */
    //                             $response = new PostController();
    //                             $response->postData(explode("?", $routesArray[1])[0], $_POST);
    //                         } else {

    //                             $json = array(
    //                                 'status' => 303,
    //                                 'result' => "Error: the token has expired"
    //                             );
    //                             echo json_encode($json, http_response_code($json["status"]));
    //                             return;
    //                         }
    //                     } else {

    //                         $json = array(
    //                             'status' => 400,
    //                             'result' => "Error: The user is not authorized"
    //                         );
    //                         echo json_encode($json, http_response_code($json["status"]));
    //                         return;
    //                     }
    //                 }
    //             } else {

    //                 $json = array(
    //                     'status' => 400,
    //                     'result' => "Error: Authorization required"
    //                 );
    //                 echo json_encode($json, http_response_code($json["status"]));
    //                 return;
    //             }
    //         } else {
    //             $json = array(
    //                 'status' => 400,
    //                 'result' => "Error: Fields in the form do not match the database"
    //             );
    //             echo json_encode($json, http_response_code($json["status"]));
    //             return;
    //         }
    //     }
    // }
    // /* petition PUT */
    // if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT") {

    //     /* we ask about the id */
    //     if (isset($_GET["id"]) && isset($_GET["nameId"])) {
    //         /* Validated to exist id */
    //         $table = explode("?", $routesArray[1])[0];
    //         $linkTo = $_GET["nameId"];
    //         $equalTo = $_GET["id"];
    //         $orderBy = null;
    //         $orderMode = null;
    //         $startAt = null;
    //         $endAt = null;
    //         $select = $_GET["nameId"];

    //         $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);

    //         if ($response) {
    //             $data = array();
    //             parse_str(file_get_contents('php://input'), $data);

    //             /* BRING the list of the columns of the table to change */
    //             $columns = array();
    //             $dbPrincipal = RouetesController::dbPrincipal();
    //             $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $dbPrincipal);

    //             foreach ($response as $key => $value) {
    //                 array_push($columns, $value->item);
    //             }
    //             /* we remove the first and last index */
    //             array_shift($columns);
    //             array_pop($columns);
    //             array_pop($columns);

    //             /* validate that the variables in the PUT fields match the column names in the database */
    //             $count = 0;
    //             foreach (array_keys($data) as $key => $value) {
    //                 $count = array_search($value, $columns);
    //             }
    //             if ($count > 0) {

    //                 if (isset($_GET["token"])) {
    //                     /* Agregamos ecepcion para actualizar sin autorizacion */
    //                     if ($_GET["token"] == "no") {
    //                         if (isset($_GET["except"])) {
    //                             $num = 0;
    //                             foreach ($columns as $key => $value) {
    //                                 $num++;
    //                                 /* buscamos coincidencias con la ecepcion */
    //                                 if ($value == $_GET["except"]) {
    //                                     /* We request controller response to edit any table */
    //                                     $response = new PutController();
    //                                     $response->putData(explode("?", $routesArray[1])[0], $data, $_GET["id"], $_GET["nameId"]);
    //                                     return;
    //                                 }
    //                             }
    //                             /* cuando no se encuentra coincidencia */
    //                             if ($num == count($columns)) {
    //                                 $json = array(
    //                                     'status' => 400,
    //                                     'result' => "Error: the exception does not match the database"
    //                                 );
    //                                 echo json_encode($json, http_response_code($json["status"]));
    //                                 return;
    //                             }
    //                         } else {
    //                             /* cuando no se envia una excepcion */
    //                             if ($num == count($columns)) {
    //                                 $json = array(
    //                                     'status' => 400,
    //                                     'result' => "Error: there is no exception"
    //                                 );
    //                                 echo json_encode($json, http_response_code($json["status"]));
    //                                 return;
    //                             }
    //                         }
    //                     } else {

    //                         /* we bring the user according to the tok */
    //                         $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");

    //                         if (!empty($user)) {

    //                             /* validate that the token has not expired */
    //                             $time = time();
    //                             if ($user[0]->token_exp_user > $time) {

    //                                 /* We request controller response to edit any table */
    //                                 $response = new PutController();
    //                                 $response->putData(explode("?", $routesArray[1])[0], $data, $_GET["id"], $_GET["nameId"]);
    //                             } else {

    //                                 $json = array(
    //                                     'status' => 303,
    //                                     'result' => "Error: the token has expired"
    //                                 );
    //                                 echo json_encode($json, http_response_code($json["status"]));
    //                                 return;
    //                             }
    //                         } else {

    //                             $json = array(
    //                                 'status' => 400,
    //                                 'result' => "Error: The user is not authorized"
    //                             );
    //                             echo json_encode($json, http_response_code($json["status"]));
    //                             return;
    //                         }
    //                     }
    //                 } else {

    //                     $json = array(
    //                         'status' => 400,
    //                         'result' => "Error: Authorization required"
    //                     );
    //                     echo json_encode($json, http_response_code($json["status"]));
    //                     return;
    //                 }
    //             } else {
    //                 $json = array(
    //                     'status' => 400,
    //                     'result' => "Error: Fields in the form do not match the database"
    //                 );
    //                 echo json_encode($json, http_response_code($json["status"]));
    //                 return;
    //             }
    //         } else {
    //             $json = array(
    //                 'status' => 400,
    //                 'result' => "Error: The id is not found in the database"
    //             );
    //             echo json_encode($json, http_response_code($json["status"]));
    //             return;
    //         }
    //     }
    // }
    // /* petition DELETE */
    // if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE") {

    //     /* we ask about the id */
    //     if (isset($_GET["id"]) && isset($_GET["nameId"])) {
    //         /* Validated to exist id */
    //         $table = explode("?", $routesArray[1])[0];
    //         $linkTo = $_GET["nameId"];
    //         $equalTo = $_GET["id"];
    //         $orderBy = null;
    //         $orderMode = null;
    //         $startAt = null;
    //         $endAt = null;
    //         $select = $_GET["nameId"];

    //         $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);

    //         if ($response) {

    //             if (isset($_GET["token"])) {
    //                 /* we bring the user according to the tok */
    //                 $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");

    //                 if (!empty($user)) {

    //                     /* validate that the token has not expired */
    //                     $time = time();
    //                     if ($user[0]->token_exp_user > $time) {

    //                         $response = new DeleteController();
    //                         $response->deleteData(explode("?", $routesArray[1])[0], $_GET["id"], $_GET["nameId"]);
    //                     } else {

    //                         $json = array(
    //                             'status' => 303,
    //                             'result' => "Error: the token has expired"
    //                         );
    //                         echo json_encode($json, http_response_code($json["status"]));
    //                         return;
    //                     }
    //                 } else {

    //                     $json = array(
    //                         'status' => 400,
    //                         'result' => "Error: The user is not authorized"
    //                     );
    //                     echo json_encode($json, http_response_code($json["status"]));
    //                     return;
    //                 }
    //             } else {

    //                 $json = array(
    //                     'status' => 400,
    //                     'result' => "Error: Authorization required"
    //                 );
    //                 echo json_encode($json, http_response_code($json["status"]));
    //                 return;
    //             }
    //         } else {
    //             $json = array(
    //                 'status' => 400,
    //                 'result' => "Error: The id is not found in the database"
    //             );
    //             echo json_encode($json, http_response_code($json["status"]));
    //             return;
    //         }
    //     }
    // }
}
