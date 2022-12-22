<?php
/* configuracion de acceso del servidor */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

/* api.wesharp2.com */
require_once "controllers/routeControler.php";
require_once "controllers/getController.php";
require_once "controllers/postController.php";
require_once "controllers/putController.php";
require_once "controllers/deleteController.php";
require_once "models/getModel.php";
require_once "models/postModel.php";
require_once "models/putModel.php";
require_once "models/deleteModel.php";

require_once "vendor/autoload.php";

$index = new RouetesController();
$index -> index();