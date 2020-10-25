<?php
require_once 'includes/init.php';



if(connection())
{
    header('Location:' . SITE_ROOT . 'profil.php');
}



if($_POST)
{
    extract($_POST);

    $errorPseudo = '';
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_pseudo->execute();
    if($verif_pseudo->rowCount() > 0)
    {
        $errorPseudo .= '<p class="font-italic text-danger">Ce Pseudo est déjà existant, merci d\'en saisir un nouveau.</p>';
        $error = true;
    }

    if(!preg_match('#^[a-zA-Z0-9._-]{2,20}+$#', $pseudo))
    {
        $errorPseudo .= '<p class="font-italic text-danger">Caractères autorisés (entre 2 et 20) : [a-zA-Z0-9._-]</p>';
        $error = true;
    }

    $errorEmail = '';
    $verif_email = $pdo->prepare("SELECT * FROM membre WHERE email = :email");
    $verif_email->bindValue(':email', $email, PDO::PARAM_STR);
    $verif_email->execute();
    if($verif_email->rowCount() > 0)
    {
        $errorEmail .= '<p class="font-italic text-danger">Un compte existant à cette adresse. Merci de vous connecter.</p>';
        $error = true;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errorEmail .= '<p class="font-italic text-danger">Email format invalide</p>';
        $error = true;
    }


    if($mdp !== $mdp_confirm)
    {
        $errorMdp = '<p class="font-italic text-danger">Vérifier les mots de passe</p>';
        $error = true;
    }

    if(!isset($error))
    {
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        $date = date('Y-m-d h:m:s');

        $insert = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) 
        VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, :date_enregistrement)");

        $insert->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $insert->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $insert->bindValue(':nom', $nom, PDO::PARAM_STR);
        $insert->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $insert->bindValue(':email', $email, PDO::PARAM_STR);
        $insert->bindValue(':civilite', $sexe, PDO::PARAM_STR);
        $insert->bindValue(':statut', 1, PDO::PARAM_INT);
        $insert->bindValue(':date_enregistrement', $date, PDO::PARAM_STR);

        $insert->execute();
        header('Location:connexion.php?inscription=valid');
    }
}

require_once 'includes/header.php';
?>

<div class="container">
<div class="row col-12 justify-content-center py-5">


    <?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];}
    $test = $pdo->query("SELECT * FROM membre");
    $test = $test->fetch(PDO::FETCH_ASSOC);

    ?>

    <form action="" method="post">
        <div>
            <h1>Inscription</h1>
        </div>
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" value="<?php if (isset($pseudo)){echo $pseudo;} ?>" name="pseudo">
            <?php if(isset($errorPseudo)) echo $errorPseudo ?>
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" value="" name="mdp">

            <label for="mdp_confirm">Confirmation</label>
            <input type="password" class="form-control" id="mdp_confirm" value="" name="mdp_confirm">
            <?php if(isset($errorMdp)) echo $errorMdp ?>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" value="<?php if (isset($nom)){echo $nom;} ?>" name="nom">
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" value="<?php if (isset($prenom)){echo $prenom;} ?>" name="prenom">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" value="<?php if (isset($email)){echo $email;} ?>" name="email">
            <?php if(isset($errorEmail)) echo $errorEmail ?>
        </div>
        <div class="form-group">
            <div>
                <input type="radio" id="homme" name="sexe" value="m">
                <label for="homme">Homme</label>
                <input type="radio"  id="femme" name="sexe" value="f">
                <label for="femme">Femme</label>
            </div>
        </div>
        <div>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-success">Inscription</button>
        </div>

    </form>

</div>
</div>


<?php
require_once 'includes/footer.php';
?>