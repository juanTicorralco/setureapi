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
                                    $statusCode = new RouetesController();
                                    $statusCode -> StatusResponse("tokenExpire");
                                    return;
                                }
                            }else{
                                $statusCode = new RouetesController();
                                $statusCode -> StatusResponse("tokenNoAutorize");
                                return;   
                            }
                        }
                    }else{
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("tokenNeedLogin");
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
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("badResponse");
                        return;
                    }
                }
            }

            /* GET Order Tables  */
            include_once "ordenable.php";
            $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
            $selected = RouetesController::validacionCampos($_GET["select"], "campo");
            $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
            $equalTo = RouetesController::validacionCampos(  $_GET["equalTo"], "global");
           
            if($tabla == "invalidate" || $selected == "invalidate" || $linkTo == "invalidate" || $equalTo == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }

            $response = new GetController();
            $response->getFilterData($tabla,$linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $selected);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routesArray[1])[0] == "relations" && !isset($_GET['linkTo']) && !isset($_GET['equalTo'])) {
            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("userResponse");
                        return;
                    }
                }
            }

            /* Get Petition of relation tables not filter */
            include_once "ordenable.php";
            $rel = RouetesController::validacionCampos($_GET["rel"], "campo");
            $type = RouetesController::validacionCampos($_GET["type"], "campo");
            $selected = RouetesController::validacionCampos($_GET["select"], "campo");
           
            if($rel == "invalidate" || $type == "invalidate" || $selected == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }
       
            $response = new GetController();
            $response->getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt, $selected);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routesArray[1])[0] == "relations" && isset($_GET['linkTo']) && isset($_GET['equalTo'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("userResponse");
                        return;
                    }
                }
            }

            /* Get Petition of relation tables with filter */
            /* GET Order Tables  */
            include_once "ordenable.php";
            $rel = RouetesController::validacionCampos($_GET["rel"], "campo");
            $type = RouetesController::validacionCampos($_GET["type"], "campo");
            $selected = RouetesController::validacionCampos($_GET["select"], "campo");
            $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
            $equalTo = RouetesController::validacionCampos(  $_GET["equalTo"], "global");
           
            if($rel == "invalidate" || $type == "invalidate" || $selected == "invalidate"|| $linkTo == "invalidate" || $equalTo == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }

            $response = new GetController();
            $response->getRelFilterData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt, $_GET["select"]);
        } else if (isset($_GET['linkTo']) && isset($_GET['search'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("userResponse");
                        return;
                    }
                }
            }
            
            /* get petition for search */
            /* GET Order Tables  */
            include_once "ordenable.php";

            if (explode("?", $routesArray[1])[0] == "relations" && isset($_GET["rel"]) && isset($_GET["type"])) {
                $rel = RouetesController::validacionCampos($_GET["rel"], "campo");
                $type = RouetesController::validacionCampos($_GET["type"], "campo");
                $selected = RouetesController::validacionCampos($_GET["select"], "campo");
                $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
                $search = RouetesController::validacionCampos(  $_GET["search"], "global");
               
                if($rel == "invalidate" || $type == "invalidate" || $selected == "invalidate"|| $linkTo == "invalidate" || $search == "invalidate"){
                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("badResponse");
                    return;
                }

                $response = new GetController();
                $response->getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $selected);
            } else {
                $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
                $selected = RouetesController::validacionCampos($_GET["select"], "campo");
                $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
                $search = RouetesController::validacionCampos(  $_GET["search"], "global");
               
                if($tabla == "invalidate" || $selected == "invalidate"|| $linkTo == "invalidate" || $search == "invalidate"){
                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("badResponse");
                    return;
                }

                $response = new GetController();
                $response->getSearchData($tabla, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $selected);
            }
        } else if (isset($_GET['linkTo']) && isset($_GET['between1']) && isset($_GET['between2']) && isset($_GET['filterTo']) && isset($_GET['inTo'])) {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["select"]) && $_GET["select"] == "*"){
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("userResponse");
                        return;
                    }
                }
            }
            
            /* get petition for between */
            /* GET Order Tables  */
            include_once "ordenable.php";

            if (explode("?", $routesArray[1])[0] == "relations" && isset($_GET["rel"]) && isset($_GET["type"])) {
                $rel = RouetesController::validacionCampos($_GET["rel"], "campo");
                $type = RouetesController::validacionCampos($_GET["type"], "campo");
                $selected = RouetesController::validacionCampos($_GET["select"], "campo");
                $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
                $filterTo =  RouetesController::validacionCampos( $_GET["filterTo"], "campo");
                $inTo = RouetesController::validacionCampos(  $_GET["inTo"], "global");
                $between1 = RouetesController::validacionCampos(  $_GET["between1"], "global");
                $between2 = RouetesController::validacionCampos(  $_GET["between2"], "global");
               
                if($rel == "invalidate" || $type == "invalidate" || $selected == "invalidate"|| $linkTo == "invalidate" || $between1 == "invalidate" || $between2 == "invalidate" || $filterTo == "invalidate" || $inTo == "invalidate"){
                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("badResponse");
                    return;
                }

                $response = new GetController();
                $response->getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $selected);
            } else {
                $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
                $selected = RouetesController::validacionCampos($_GET["select"], "campo");
                $linkTo =  RouetesController::validacionCampos( $_GET["linkTo"], "campo");
                $filterTo =  RouetesController::validacionCampos( $_GET["filterTo"], "campo");
                $inTo = RouetesController::validacionCampos(  $_GET["inTo"], "global");
                $between1 = RouetesController::validacionCampos(  $_GET["between1"], "global");
                $between2 = RouetesController::validacionCampos(  $_GET["between2"], "global");
               
                if($tabla == "invalidate" || $selected == "invalidate"|| $linkTo == "invalidate" || $between1 == "invalidate" || $between2 == "invalidate" || $filterTo == "invalidate" || $inTo == "invalidate"){
                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("badResponse");
                    return;
                }

                $response = new GetController();
                $response->getBetweenData($tabla, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $selected);
            }
        } else {

            // peticion get con autorization administrative
            foreach(RouetesController::tableProtected() as $key => $value){
                if(explode("?", $routesArray[1])[0] == $value){
                    if(isset($_GET["rol"])){
                        $linkTo = "username_user";
                        $users = RouetesController::validacionCampos($_GET["rol"], "simple");

                        if($users == "invalidate"){
                            $statusCode = new RouetesController();
                            $statusCode -> StatusResponse("userResponse");
                            return;
                        }
                        $equalTo = $users;
                        $response = GetModel::getFilterData("users",$linkTo,$equalTo,null,null,null,null,"rol_user");
                        if(count($response) > 0){
                            if($response[0]->rol_user != "admin"){
                                $statusCode = new RouetesController();
                                $statusCode -> StatusResponse("userResponse");
                                return;
                            }
                        }else{
                            $statusCode = new RouetesController();
                            $statusCode -> StatusResponse("userResponse");
                            return;
                        }
                    }else{
                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("userResponse");
                        return;
                    }
                }
            }

            /* GET Petition not filter */
            /* GET Order Tables  */
            include_once "ordenable.php";
            
            if(!isset($_GET["select"])){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }
            
            $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
            $selected = RouetesController::validacionCampos($_GET["select"], "campo");
           
            if($tabla == "invalidate" || $selected == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }

            $response = new GetController();
            $response->getData($tabla, $orderBy, $orderMode, $startAt, $endAt, $selected);
        }
    }
    /* petition POST */
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

        /* BRING the list of the columns of the table to change */
        $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
       
        if($tabla == "invalidate"){
            $statusCode = new RouetesController();
            $statusCode -> StatusResponse("badResponse");
            return;
        }

        $columns = array();
        $dbPrincipal = RouetesController::dbPrincipal();
        $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $dbPrincipal);

        foreach ($response as $key => $value) {
            array_push($columns, $value->item);
        }
        array_shift($columns);
        array_pop($columns);

        if (isset($_POST)) {
            /* validate that the variables in the PUT fields match the column names in the database */
            $count = 0;
            foreach (array_keys($_POST) as $key => $value) {
                $count = array_search($value, $columns);
            }
            
            if ($count > 0) {

                /* we give to response of controller for user register */
                if (isset($_GET["register"]) && $_GET["register"] == "true") {

                    /* we give response of the controller for insert data in a table */
                    $response = new PostController();
                    $response->postRegister(explode("?", $routesArray[1])[0], $_POST);
                } else if (isset($_GET["login"]) && $_GET["login"] == "true") {

                    /* we give response of the controller for insert data in a table */
                    $response = new PostController();
                    $response->postLogin(explode("?", $routesArray[1])[0], $_POST);
                }else if (isset($_GET["newslater"]) && $_GET["newslater"] == "true") {

                    /* we give response of the controller for insert data in a table */
                    $response = new PostController();
                    $response->postNewslater(explode("?", $routesArray[1])[0], $_POST);
                } else if (isset($_GET["token"])) {

                    /* Agregamos ecepcion para actualizar sin autorizacion */
                    if ($_GET["token"] == "no") {
                        if (isset($_GET["except"])) {
                            $num = 0;
                            foreach ($columns as $key => $value) {
                                $num++;
                                /* buscamos coincidencias con la ecepcion */
                                if ($value == $_GET["except"]) {
                                    /* we give response of the controller for insert data in a table */
                                    $response = new PostController();
                                    $response->postData(explode("?", $routesArray[1])[0], $_POST);
                                    return;
                                }
                            }
                            /* cuando no se encuentra coincidencia */
                            if ($num == count($columns)) {
                                $json = array(
                                    'status' => 400,
                                    'result' => "Error: the exception does not match the database"
                                );
                                echo json_encode($json, http_response_code($json["status"]));
                                return;
                            }
                        } else {
                            /* cuando no se envia una excepcion */
                            $json = array(
                                'status' => 400,
                                'result' => "Error: there is no exception"
                            );
                            echo json_encode($json, http_response_code($json["status"]));
                            return;
                        }
                    } else {


                        /* we bring the user according to the tok */
                        $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");

                        if (!empty($user)) {
                            /* validate that the token has not expired */
                            $time = time();
                            if ($user[0]->token_exp_user > $time) {

                                /* we give response of the controller for insert data in a table */
                                $response = new PostController();
                                $response->postData(explode("?", $routesArray[1])[0], $_POST);
                            } else {

                                $statusCode = new RouetesController();
                                $statusCode -> StatusResponse("tokenExpire");
                                return;
                            }
                        } else {

                            $statusCode = new RouetesController();
                            $statusCode -> StatusResponse("tokenNoAutorize");
                            return;
                        }
                    }
                } else {

                    $json = array(
                        'status' => 400,
                        'result' => "Error: Authorization required"
                    );
                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
            } else {
                $json = array(
                    'status' => 400,
                    'result' => "Error: Fields in the form do not match the database"
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }
    /* petition PUT */
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT") {

        /* we ask about the id */
        if (isset($_GET["id"]) && isset($_GET["nameId"])) {

            $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
            $selected = RouetesController::validacionCampos($_GET["nameId"], "campo");
            $linkT =  RouetesController::validacionCampos( $_GET["nameId"], "campo");
            $equalT = RouetesController::validacionCampos(  $_GET["id"], "numero");
           
            if($tabla == "invalidate" || $selected == "invalidate" || $linkT == "invalidate" || $equalT == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }
            /* Validated to exist id */
            $table = $tabla;
            $linkTo = $linkT;
            $equalTo = $equalT;
            $orderBy = null;
            $orderMode = null;
            $startAt = null;
            $endAt = null;
            $select = $selected;

            $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);
            
            if ($response && is_array($response)) {
                $data = array();
                parse_str(file_get_contents('php://input'), $data);

                /* BRING the list of the columns of the table to change */
                $columns = array();
                $dbPrincipal = RouetesController::dbPrincipal();
                $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $dbPrincipal);

                foreach ($response as $key => $value) {
                    array_push($columns, $value->item);
                }
                /* we remove the first and last index */
                array_shift($columns);
                array_pop($columns);
                array_pop($columns);

                /* validate that the variables in the PUT fields match the column names in the database */
                $count = 0;
                foreach (array_keys($data) as $key => $value) {
                    $count = array_search($value, $columns)+1;
                }
                
                if ($count > 0) {

                    if (isset($_GET["token"])) {
                        /* Agregamos ecepcion para actualizar sin autorizacion */
                        if ($_GET["token"] == "no") {
                            if (isset($_GET["except"])) {
                                $num = 0;
                                foreach ($columns as $key => $value) {
                                    $num++;
                                    /* buscamos coincidencias con la ecepcion */
                                    if ($value == $_GET["except"]) {
                                        /* We request controller response to edit any table */
                                        $response = new PutController();
                                        $response->putData(explode("?", $routesArray[1])[0], $data, $_GET["id"], $_GET["nameId"]);
                                        return;
                                    }
                                }
                                /* cuando no se encuentra coincidencia */
                                if ($num == count($columns)) {
                                    $statusCode = new RouetesController();
                                    $statusCode -> StatusResponse("badResponse");
                                    return;
                                }
                            } else {
                                /* cuando no se envia una excepcion */
                                    $statusCode = new RouetesController();
                                    $statusCode -> StatusResponse("badResponse");
                                    return;
                            }
                        } else {

                            /* we bring the user according to the tok */
                            $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");

                            if (!empty($user)) {

                                /* validate that the token has not expired */
                                $time = time();
                                if ($user[0]->token_exp_user > $time) {

                                    /* We request controller response to edit any table */
                                    $response = new PutController();
                                    $response->putData(explode("?", $routesArray[1])[0], $data, $_GET["id"], $_GET["nameId"]);
                                } else {

                                    $statusCode = new RouetesController();
                                    $statusCode -> StatusResponse("tokenExpire");
                                    return;
                                }
                            } else {

                                $statusCode = new RouetesController();
                                $statusCode -> StatusResponse("tokenNoAutorize");
                                return;
                            }
                        }
                    } else {

                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("tokenNoAutorize");
                        return;
                    }
                } else {
                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("columsNoDB");
                    return;
                }
            } else {
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("columsNoDB");
                return;
            }
        }
    }
    /* petition DELETE */
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE") {

        /* we ask about the id */
        if (isset($_GET["id"]) && isset($_GET["nameId"])) {
            $tabla = RouetesController::validacionCampos(explode("?", $routesArray[1])[0], "tabla");
            $selected = RouetesController::validacionCampos($_GET["nameId"], "campo");
            $linkT =  RouetesController::validacionCampos( $_GET["nameId"], "campo");
            $equalT = RouetesController::validacionCampos(  $_GET["id"], "numero");
           
            if($tabla == "invalidate" || $selected == "invalidate" || $linkT == "invalidate" || $equalT == "invalidate"){
                $statusCode = new RouetesController();
                $statusCode -> StatusResponse("badResponse");
                return;
            }
            /* Validated to exist id */

            $table = $tabla;
            $linkTo = $linkT;
            $equalTo = $equalT;
            $orderBy = null;
            $orderMode = null;
            $startAt = null;
            $endAt = null;
            $select = $selected;

            $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);

            if ($response && is_array($response)) {

                if (isset($_GET["token"])) {
                    /* we bring the user according to the tok */
                    $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null, "token_exp_user");
                    
                    if (!empty($user) && is_array($user)) {

                        /* validate that the token has not expired */
                        $time = time();
                        if ($user[0]->token_exp_user > $time) {

                            $response = new DeleteController();
                            $response->deleteData($table, $equalTo, $linkTo);
                        } else {

                            $statusCode = new RouetesController();
                            $statusCode -> StatusResponse("tokenExpire");
                            return;
                        }
                    } else {

                        $statusCode = new RouetesController();
                        $statusCode -> StatusResponse("tokenNoAutorize");
                        return;
                    }
                } else {

                    $statusCode = new RouetesController();
                    $statusCode -> StatusResponse("tokenNoAutorize");
                    return;
                }
            } else {
                $json = array(
                    'status' => 400,
                    'result' => "Error: The id is not found in the database"
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }else{
            $statusCode = new RouetesController();
            $statusCode -> StatusResponse("badResponse");
            return;
        }
    }
}
