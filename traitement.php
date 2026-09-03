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
        
        // vérification de la présence et de la bonne réception du fichier
        if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK){
            header("Location: index.php?error=5");
            exit();
        }

        // récup le fichier
        $tmpPath = $_FILES['image']['tmp_name'];
        // définition de ma taille maxi
        $tailleMax = 2 * 1024 * 1024; // 2 Mo // 1,048,576 octets
     
        // vérification de taille
        if($_FILES['image']['size'] > $tailleMax)
        {
            header("Location: index.php?error=6");
            exit();
        }

        // extension
        // image.php.jpg
        // image.JPG
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $extensionAutorisees = ['jpg','jpeg','png','svg',"webp"];

        if(!in_array($extension,$extensionAutorisees, true)){
            header("Location: index.php?error=7");
            exit();
        }

        // vérification du Mime Type => utilisation contenu binaire(fileinfo)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeReel = $finfo->file($tmpPath);

        $mimesAutorises = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "svg" => "image/svg+xml",
            "webp" => "image/webp"
        ];

        if(!in_array($mimeReel, $mimesAutorises, true) || $mimesAutorises[$extension] !== $mimeReel){
            header("Location: index.php?error=8");
            exit();
        }

        // gestion du nom du fichier
        // dossier/ico/fichier.jpg
        // fichier.jpg
        $nomImage =  basename($_FILES['image']['name']);
        $nomImageLisible = strtr($nomImage, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $nomImageSafe = preg_replace('/([^.a-z0-9]+)/i', '-', $nomImageLisible);
        $uniqnomSafe = uniqid().'-'.$nomImageSafe;


        // $uniqnomSafe = bin2hex(random_bytes(16)).'.'.$extension;

        // dfkqsjfkldfj-fichier.jpg
        // imagesdfkqsjfkldfj-fichier.jpg
        $dossierDestination = "images/";
        
        if(move_uploaded_file($tmpPath, $dossierDestination.$uniqnomSafe)){
            require "config/connexion.php";
            try{
                 $req = $bdd->prepare("INSERT INTO contacts(nom,prenom,email,photo) VALUES(:nom,:prenom,:email,:image)");
                $req->execute([
                    "nom"=>$nom,
                    "prenom"=>$prenom,
                    "email"=>$email,
                    "image"=>$uniqnomSafe
                ]);
                unset($_SESSION['csrf_token']);
                header("Location: index.php?add=success");
                exit();
            }catch(PDOException $e){
                if(file_exists($dossierDestination.$uniqnomSafe)){
                    unlink($dossierDestination.$uniqnomSafe);
                }
                header("Location: index.php?error=500");
                exit();
            }

        }else{
            header("Location: index.php?error=9");
            exit();
        }

     

    }else{
        header("Location: index.php?error=".$err);
        exit();
    }








 
  