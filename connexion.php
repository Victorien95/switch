<?php
require_once  'includes/init.php';

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
}




if(connection()) {
    header('location:' . SITE_ROOT . 'profil.php');
}






if(isset($_GET['inscription']) && $_GET['inscription'] == 'valid'){
    $inscriptionValid = "<p class='bg-success rounded col-md-5 text-white mx-auto text-center p-2 mt-2'>Vous êtes maintenant inscrit sur le site !</p>";
}





$pseudo = '';
if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $verif_connexion->execute();

    if($verif_connexion->rowCount() > 0) {
        $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);

        if(password_verify($mdp, $infos['mdp'])) {


            foreach($infos AS $indice => $valeur) {
                if($indice != 'mdp') {
                    $_SESSION['membre'][$indice] = $valeur;
                }
            }

            header('location:' . SITE_ROOT . 'profil.php');


        } else {
            $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
        }

    } else {
        $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
    }

}
require_once 'includes/header.php';
?>



<div id="connect" class="row mb-5 vh-100 align-items-center">

    <div class="col-12 col-md-4 mx-auto">
        <div class="starter-template text-center mt-5 ">
            <h1><i class="fas fa-sign-in-alt mr-2"></i>Connexion</h1>
            <p class="lead"><?= $msg ?></p>
            <?php if (isset($inscriptionValid)){echo $inscriptionValid;}?>
        </div>

        <form method="post" action="">
            <div class="form-group">
                <?php if(isset($test)){echo $test;} ?>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" value="<?= $pseudo ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" autocomplete="off" name="mdp" id="mdp" value="" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" name="connexion" id="connexion" class="form-control btn btn-outline-success">Connexion</button>
            </div>
        </form>

    </div>
</div>

<?php
require_once 'includes/footer.php';
?>