<?php
    require "config/session.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h1>Formulaire</h1>
                <form action="traitement.php" method="POST">
                    <?php 
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group my-3">
                        <label for="nom">Nom: </label>
                        <input type="text" name="nom" id="nom" class="form-control">
                    </div>
                    <div class="form-group my-3">
                        <label for="nom">Prénom: </label>
                        <input type="text" name="prenom" id="prenom" class="form-control">
                    </div>
                     <div class="form-group my-3">
                        <label for="nom">Email: </label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group my-3">
                        <input type="submit" value="Envoyer" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>