<?php
/* conection to the database */
class Conection{
    static public function connect(){
        try{
            $link = new PDO("mysql:host=localhost;dbname=seture","root", "");
            $link -> exec("set names utf8");
        }catch(PDOException $e){
            die("Error: ". $e->getMessage());
        }
        return $link;
    }
}
