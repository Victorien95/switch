<?php
require_once 'init.php';

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/e971e3c315.js" crossorigin="anonymous"></script>

    <!-- datepicker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" rel="stylesheet">

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.jqueryui.min.js"></script>

    <!-- Tiny -->
    <script src="https://cdn.tiny.cloud/1/xux5nc9ofnf9ijk7jk4efwtw030xpqf4dxyowv9lk0g6mlmu/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="tinymce/plugins/tinymce_languages/langs/fr_FR.js"></script>

    <!-- Lightbox -->
    <link rel="stylesheet" href="css/lightbox.css">

    <link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/header.css">








    <title>Boutique Switch</title>
</head>




<body>
<nav class="navbar navbar-expand-lg navbar-dark">

    <a class="navbar-brand" href="<?= SITE_ROOT ?>index.php"><img src="<?= SITE_ROOT ?>/includes/logo/sw_contour_blanc.svg" alt="" style="min-width: 150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>



    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

        <?php if (connection()):?>
            <li class="nav-items">
                <a class="nav-link" href="<?= SITE_ROOT ?>profil.php">Profil<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>boutique.php">Boutique</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>panier.php">Panier<span class="badge badge-primary mx-1"><?php if(isset($_SESSION['panier'])) echo montantPanier(); else echo '0' ?></span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>connexion.php?action=deconnexion">Déconnexion</a>
            </li>
        <?php else:?>
            <li class="nav-item active">
                <a class="nav-link" href="<?= SITE_ROOT ?>inscription.php">Inscription<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>connexion.php">Connection</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=&crs=&orderby=">Boutique</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_ROOT ?>panier.php">Panier</a>
            </li>
        <?php endif;?>


        <?php if(admin()):?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="backoffice" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    BackOffice
                </a>
                <div class="dropdown-menu" aria-labelledby="backoffice">
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/gestion_salle.php?action=affichage">Gestion salles</a>
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/gestion_membres.php?action=affichage">Gestion membres</a>
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/gestion_produit.php?action=affichage">Gestion produits</a>
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/gestion_commandes.php?action=affichage">Gestion commandes</a>
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/gestion_avis.php?action=affichage">Gestion avis</a>
                    <a class="dropdown-item" href="<?= SITE_ROOT ?>admin/statistiques.php">Statistiques</a>
                </div>
            </li>
       <?php endif; ?>
        </ul>

    </div>
</nav>