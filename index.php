<?php require_once 'includes/header.php';
?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/index.css">

<div class="wrapper" >

    <div class="container d-flex flex-column align-items-center justify-content-center col-12 vh-100">
        <div class="welcome my-0">
            <div class="logo">
                <h1 class="mb-5"><small>Bienvenue sur la boutique</small></h1>
                <img src="<?= SITE_ROOT ?>/includes/logo/sw_contour_blanc.svg" alt="" style="max-width: 500px">
            </div>
        </div>

        <div class="status">
            <p class="status-ready mb-5 h5">Votre espace de réservation Coworking en ligne</p>
            <code>
                <span class="check">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </span>
                <span><a class="h5" href="<?= SITE_ROOT ?>boutique.php">Réserver dès maintenant</a></span>
            </code>
        </div>

    </div>

</div>
<?php require_once 'includes/footer.php'?>





