<?php
if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
    $orderB = RouetesController::validacionCampos($_GET["orderBy"], "campo");
    $orderM = RouetesController::validacionCampos($_GET["orderMode"], "simple");

    if($orderB == "invalidate" || $orderM == "invalidate" ){
        $orderBy = null;
        $orderMode = null;
    }else{
        $orderBy = $orderB;
        $orderMode = $orderM;
    }
} else {
    $orderBy = null;
    $orderMode = null;
}
/* GET star and ent at  */
if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
    $starA = RouetesController::validacionCampos($_GET["startAt"], "campo");
    $enA = RouetesController::validacionCampos($_GET["endAt"], "numero");

    if($starA == "invalidate" || $enA == "invalidate" ){
        $startAt = null;
        $endAt = null;
    }else{
        $startAt = $starA;
        $endAt = $enA;
    }
} else {
    $startAt = null;
    $endAt = null;
}