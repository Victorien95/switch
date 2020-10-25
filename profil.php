<?php
require_once 'includes/init.php';


if (!connection()){
    header('location:connexion.php');
}

require_once 'includes/header.php';
?>

<div class="starter-template text-center py-5">
    <h1 id="profil-fas"><i  class="fas fa-user-circle"></i>Profil</h1>
    <p class="lead"><?php echo $msg; ?></p>
</div>

<div class="container-fluid mb-5 py-5">
    <div class="row justify-content-center">
        <div class="col-6">
            <ul class="list-group">
                <li class="list-group-item background">Bonjour <b><?= ucfirst($_SESSION['membre']['pseudo']); ?></b></li>
                <li class="list-group-item">Pseudo: <?= ucfirst($_SESSION['membre']['pseudo']) ?> </li>
                <li class="list-group-item">Nom: <?= ucfirst($_SESSION['membre']['nom']) ?> </li>
                <li class="list-group-item">Prenom: <?= ucfirst($_SESSION['membre']['prenom']) ?> </li>
                <li class="list-group-item">Email: <?= $_SESSION['membre']['email'] ?> </li>
                <li class="list-group-item">Sexe:
                    <?php
                    if ($_SESSION['membre']['civilite'] == 'm'){
                        echo 'Homme';
                    }
                    else
                    {
                     echo 'Femme';
                    }
                    ?>
                </li>
                <?php

                    $date = new DateTime($_SESSION['membre']['date_enregistrement']);
                ?>
                <li class="list-group-item">Date d'inscription: <?= 'Le ' . $date->format('d/m/Y à h:m:s') ?> </li>
                <li class="list-group-item">Statut:
                    <?php
                    if ($_SESSION['membre']['statut'] == '2')
                    {
                        echo 'Administrateur';
                    }
                    else
                    {
                        echo 'Membre';
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    
</div>


<?php
require_once 'includes/footer.php';
?>