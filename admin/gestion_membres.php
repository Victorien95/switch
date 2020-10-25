<?php
require_once  '../includes/header.php';

if(!admin())
{
    header('Location:' . SITE_ROOT . 'connexion.php' );
}







if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $produitDelete = $_GET['id_membre'];
    $data = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $data->bindValue(':id_membre', $produitDelete, PDO::PARAM_INT);
    $data->execute();
    $GET['action'] = 'affichage';
    $validDelete = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le membre <strong>$_GET[id_membre]</strong> a bien été supprimé</p>";

}


if(isset($_GET['id_membre']))
{
    $verif_date = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_memebre");
    $verif_date->bindValue(':id_memebre', $_GET['id_membre'], PDO::PARAM_STR);
    $verif_date->execute();
    $temp_date = $verif_date->fetch();
}


if ($_POST)
{
    extract($_POST);




    $errorPseudo = '';
    $erroEmpty = '';
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_pseudo->execute();
    $temp_pseudo = $verif_pseudo->fetch();

    if (isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if ($pseudo !== $temp_date['pseudo'] && $verif_pseudo->rowCount() > 0){
            $errorPseudo .= '<p class="font-italic text-danger">Ce Pseudo est déjà existant, merci d\'en saisir un nouveau.</p>';
            $error = true;
        }
    }

    else
    {
        if ($verif_pseudo->rowCount() > 0){
            $errorPseudo .= '<p class="font-italic text-danger">Ce Pseudo est déjà existant, merci d\'en saisir un nouveau.</p>';
            $error = true;
        }
    }
    if (isset($_GET['action'])){
        if (empty($_POST['pseudo']) || empty($_POST['mdp']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email'])){
            $erroEmpty .= '<p class="font-italic text-danger">Attention tous les champs doivent être rempli</p>';
            $error = true;
        }
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
    $temp_email = $verif_email->fetch();


    if (isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if ($email !== $temp_date['email'] && $verif_email->rowCount() > 0){
            $errorEmail .= '<p class="font-italic text-danger">Mail déja utilisé</p>';
            $error = true;
        }
    }
    else
    {
        if ($verif_email->rowCount() > 0){
            $errorEmail .= '<p class="font-italic text-danger">Un compte existant à cette adresse. Merci de vous connecter.</p>';
            $error = true;
        }
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errorEmail .= '<p class="font-italic text-danger">Email format invalide</p>';
        $error = true;
    }




    if(!isset($error)){
        if ($_GET['action'] == 'modification')
        {
            $date_enregistrement = $temp_date['date_enregistrement'];
        }
        else{
            $date_enregistrement = date('Y-m-d h:m:s');
        }
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        if(isset($_GET['action']) && $_GET['action'] == 'ajout'){
            $data = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) 
            VALUES  (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, :date_enregistrement)");


            $_GET['action'] = 'affichage';

            $validInsert = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le membre <strong>$pseudo</strong> a bien été enregistré</p>";

        }
        else
        {
            $data = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom,
                email = :email, civilite = :civilite, statut = :statut, date_enregistrement = :date_enregistrement WHERE id_membre = :id_membre");


            $_GET['action'] = 'affichage';

            $validUpdate = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le membre <strong>$pseudo</strong> a bien été modififé</p>";


            $data->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);

        }

        $data->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $data->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $data->bindValue(':nom', $nom, PDO::PARAM_STR);
        $data->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $data->bindValue(':email', $email, PDO::PARAM_STR);
        $data->bindValue(':civilite', $sexe, PDO::PARAM_STR);
        $data->bindValue(':statut', $statut, PDO::PARAM_STR);
        $data->bindValue(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);



        $req = $data->execute();



    }
}



?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/gestion_membres.css">



<div class="container-fluid mx-0">
    <div class="row background px-0 mb-5" style="background-color: #2c302e; height: 100%">
        <div class="col-12">
            <div class="row justify-content-start align-items-center my-5">
                <div class="col-md-2 text-center"><p class="text-white">BACKOFFICE</p></div>
                <div class="col-md-5"><a href="?action=affichage" class="col-md-12 btn btn-primary p-2 mb-3 mb-md-0">AFFICHER LES MEMBRES</a></div>
                <div class="col-md-5"><a href="?action=ajout" class="col-md-12 btn btn-primary p-2">AJOUTER UNE NOUVEAU MEMBRE</a></div>
            </div>
        </div>
    </div>
</div>




<?php if (isset($validDelete)) echo $validDelete ?>
<?php if(isset($validInsert)) echo $validInsert ?>
<?php if(isset($validUpdate)) echo $validUpdate ?>


<?php if(isset($_GET['action']) && $_GET['action'] == 'affichage'):?>

    <div class="col-12 text-center pt-5 pb-2"><h1 class="display-4 text-center mt-2">Affichage des membres</h1></div>
    <div class="col-12 my-0 py-0 text-center"><p class="reactiver btn btn-secondary">Réactiver la confirmation de supression</p></div>


<div class="col-12 my-5">
    <table id="salle_table" class="table table-bordered text-center table-responsive-xl text-center myTable"><thead><tr>


            <?php
            $data = $pdo->query("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, date_enregistrement FROM membre");

            for($i = 0; $i < $data->columnCount(); $i++):
                $colonne = $data->getColumnMeta($i);
                ?>
                <th style="min-width: 150px" class="align-middle"><?= str_replace('_', ' ', ucfirst($colonne['name']))  ?></th>
            <?php endfor; ?>
            <th class="align-middle">Editer / supprimer</th>
        </tr>
        </thead><tbody>

        <?php while ($products = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <?php foreach ($products as $key => $value): ?>
                    <?php if ($key == 'photo'): ?>
                        <td class="align-middle"><img src="<?= $value?>" alt="" style="width: 150px"></td>
                    <?php elseif ($key == 'date_enregistrement'): ?>
                        <?php
                        $date_depart = new DateTime($value);
                        ?>
                        <td class="align-middle"><?= $date_depart->format('d/m/Y à h:m:s') ?></td>
                    <?php else: ?>
                        <td class="align-middle"><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>

                <td class="col-1 align-middle">
                    <div class="">
                        <a href="?action=modification&id_membre=<?= $products['id_membre'] ?>" class="px-2"><i class="fas fa-edit"></i></a>
                        <a href="?action=supression&id_membre=<?= $products['id_membre'] ?>" class="supp px-2 mySuppButton" data-toggle="modal" data-target="#exampleModal<?= $products['id_membre'] ?>"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </td>

            </tr>

            <div class="modal myModal text-center fade" id="exampleModal<?= $products['id_membre'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title" id="exampleModalLabel">Attention ! <br>vous vous apprêtez a supprimer le membre:
                                <br><?= $products['id_membre'] ?> - <?= $products['nom'] . ' - ' . $products['email']?> </h5>
                        </div>
                        <div class="modal-body h5">
                            Êtes vous sur de vouloir supprimer ?
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="ml-4">
                                <input class="form-check-input" type="checkbox" value="1" id="deleteCheckbox">
                                <label class="form-check-label" for="deleteCheckbox">Ne plus me demander</label>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a href="?action=supression&id_membre=<?= $products['id_membre'] ?>" class="btn btn-danger">Supprimer</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody></table>
</div>
<?php endif; ?>



<?php
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')):
    if (isset($_GET['id_membre'])){
        $data = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $data->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $data->execute();

        $produitActuel = $data->fetch(PDO::FETCH_ASSOC);
        echo '<pre>'; print_r($produitActuel['id_membre']); echo '</pre>';

        foreach ($produitActuel as $key => $value){
            $$key = (isset($produitActuel["$key"])) ? $produitActuel["$key"] : '';
        }

    }

    ?>

    <h1 class="display-4 text-center mt-2"><?= ucfirst($_GET['action']) ?> Membre</h1><hr>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php if (isset($pseudo)) echo $pseudo?>">
                    <?php if(isset($errorPseudo)) echo $errorPseudo ?>
                </div>

                <div class="form-group col-md-6">
                    <label for="mdp">Mot de passe</label>
                    <input type="text" class="form-control" id="mdp" name="mdp" value="<?php if (isset($mdp)) echo $mdp?>">
                    <?php if(isset($errorMdp)) echo $errorMdp ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php if (isset($nom)) echo $nom?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php if (isset($prenom)) echo $prenom?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php if (isset($email)) echo $email?>">
                    <?php if(isset($errorEmail)) echo $errorEmail ?>

                </div>
                <div class="form_group">
                    <label for="sexe">Civilite</label>
                    <select class="form-control" name="sexe" id="sexe">
                        <option value="m" <?php if (isset($sexe) && $sexe == 'm') echo 'selected'?>>Homme</option>
                        <option value="f" <?php if (isset($sexe) && $sexe == 'f') echo 'selected'?>>Femme</option>
                    </select>
                </div>
            </div>



            <div class="form-row">
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select class="form-control" name="statut" id="statut">
                        <option value="1" <?php if (isset($statut) && $statut == '1') echo 'selected'?>>Membre</option>
                        <option value="2" <?php if (isset($statut) && $statut == '2') echo 'selected'?>>Administrateur</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <?php if(isset($erroEmpty)) echo $erroEmpty ?>
                <button type="submit" class="btn btn-dark">Ajout produit</button>
            </div>
        </form>
    </div>
<?php endif?>


<?php
require_once '../includes/footer.php';
?>
























