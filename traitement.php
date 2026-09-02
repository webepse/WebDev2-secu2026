<?php
    require "config/session.php";

    if($_SERVER['REQUEST_METHOD'] !== "POST"){
        http_response_code(405); // 405 Méthode non autorisée
        header("Allow: POST"); // indiquer la méthode autorisée
        exit("Méthode non autorisée, Utilisez POST");
    }

    if(!isset($_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])){
        http_response_code(403);
        exit("Jeton de sécurité invalide");
    }

    $err = 0;

    // opérateur de coalescence nulle (Null Coalescing Operator)
    // c'est un raccouci pour dire : " si la valeur à gauche existe et n'est pas nul, utilise là, sinon, utilise la valeur par défaut à droite

    $nom = trim($_POST['nom'] ?? "");
    $prenom = trim($_POST['prenom'] ?? "");
    $email = trim($_POST['email'] ?? "");

    if (empty($nom)){
        $err = 1;
    }elseif (empty($prenom)){
        $err = 2;
    }elseif (empty($email)){
        $err = 3;
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $err = 4;
    }

    if($err===0){
        require "config/connexion.php";
        $req = $bdd->prepare("INSERT INTO contacts(nom,prenom,email) VALUES(:nom,:prenom,:email)");
        $req->execute([
            "nom"=>$nom,
            "prenom"=>$prenom,
            "email"=>$email
        ]);

        // bonne pratique: forcer la régénération du token
        // => supprimer le token => fonction unset();
        unset($_SESSION['csrf_token']);

        header("Location: index.php?add=success");
        exit();

    }else{
        header("Location: index.php?error=".$err);
        exit();
    }





 
  