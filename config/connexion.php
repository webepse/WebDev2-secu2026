<?php
try{
    $bdd = new PDO("mysql:host=localhost;dbname=secu2026;charset=utf8mb4","root","",[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}catch(PDOException $e){
   // die("Erreur: ".$e->getMessage());
   //error_log($e->getMessage());
   http_response_code(500);
   exit("Une erreur est survenue lors de la connexion au serveur");
}


?>
