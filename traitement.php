<?php
    require "config/session.php";

    if(!isset($_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])){
        http_response_code(403);
        exit("Jeton de sécurité invalide");
    }

    echo "ok";

    var_dump($_POST);
    var_dump($_SESSION);