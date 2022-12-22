<?php
require_once "conections.php";

class DeleteModel{
    static public function deleteData($table, $id, $nameId){
        try{
            $stmt = Conection :: connect() -> prepare("DELETE FROM $table WHERE $nameId=:$nameId");
            $stmt -> bindParam(":".$nameId, $id, PDO::PARAM_INT);
            
            $exec = $stmt->execute();
            if($exec){
                return "The Process was Successfull";
            }
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
}