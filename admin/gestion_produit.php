<?php
require_once  '../includes/header.php';

if(!admin())
{
    header('Location:' . URL . 'connexion.php' );
}


$data = $pdo->query("SELECT * FROM salle");
$salle = $data->fetchAll();
foreach ($salle as $key => $value){
    if ($value == 'id_salle'){
        echo $value['id_salle'];
    }
}



if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $produitDelete = $_GET['id_produit'];
    $data = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $data->bindValue(':id_produit', $produitDelete, PDO::PARAM_INT);
    $data->execute();
    $GET['action'] = 'affichage';
    $validDelete = "<p class='bg-success col-md-6 p-3 mx-auto mt-3 text-white text-center'>Le produit <strong>$_GET[id_produit]</strong> a bien été supprimé</p>";

}


if(isset($_GET['id_produit']))
{
    $verif_date = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $verif_date->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $verif_date->execute();
    $temp_date = $verif_date->fetch();
    $id_produit = $temp_date['id_produit'];
    $id_salle = $temp_date['id_salle'];
}



if ($_POST)
{
    extract($_POST);

    $errorDate = '';
    $errorPrix = '';
    $errorDate_depart = '';
    $errorDate_arrivee = '';
    $date_depart_verif = separateurDate($date_depart);
    $date_arrivee_verif = separateurDate($date_arrivee);


    if (isset($_GET['action'])){
        if (empty($date_depart) && empty($date_arrivee)){
            $errorDate .= '<p class="font-italic text-danger">Ce champ doit être rempli</p>';
            $error = true;
        }

        if ($date_arrivee < date('Y-m-d') || $date_depart < date('Y-m-d') ){
            $errorDate .= '<p class="font-italic text-danger">Attention la date ne peut être antérieur à la date du jour</p>';
            $error = true;
        }

        if ($date_arrivee >= $date_depart){
            $errorDate .= '<p class="font-italic text-danger">Attention La date d\'arrivée ne peut être supérieur à la date de départ</p>' ;
            $error = true;
        }



        if (empty($date_depart)){
            $errorDate_depart .= '<p class="font-italic text-danger">Ce champ doit être rempli</p>';
            $error = true;
        }
        if (empty($date_arrivee)){
            $errorDate_arrivee .= '<p class="font-italic text-danger">Ce champ doit être rempli</p>';
            $error = true;
        }




        if (isset($id_salle) && isset($id_produit)){
            $query = "SELECT * FROM produit WHERE id_produit != $id_produit AND id_salle = $id_salle AND ('$date_arrivee' BETWEEN date_arrivee AND date_depart)";
            $data = $pdo->query($query);
            $data->execute();
            if ($data->rowCount() >= 1){
                echo 'baaaaaaaaaaaaaaaaaaaaaa';
                $errorDate_arrivee .= '<p class="font-italic text-danger">Cette date correspond déja à un produit identique</p>';
                $error = true;

            }
            $query = "SELECT * FROM produit WHERE id_produit != $id_produit AND id_salle = $id_salle AND ('$date_depart' BETWEEN date_arrivee AND date_depart)";
            $data = $pdo->query($query);
            $data->execute();
            if ($data->rowCount() >=1 ){
                $errorDate_depart .= '<p class="font-italic text-danger">Cette date correspond déja à un produit identique</p>';
                $error = true;
            }
        }
        if ($_GET['action'] && $_GET['action'] == 'ajout'){

            $subIdsalle = explode(" ", $id_salle);

            $query = "SELECT * FROM produit WHERE id_salle = $subIdsalle[0] AND (date_arrivee BETWEEN ('$date_arrivee') AND '$date_depart')";
            $data = $pdo->query($query);
            $data->execute();

            if ($data->rowCount() > 0){
                $errorDate_arrivee .= '<p class="font-italic text-danger">Cette date correspond déja à un produit identique</p>';
                $error = true;
            }
            $query = "SELECT * FROM produit WHERE id_salle = $subIdsalle[0] AND (date_depart BETWEEN '$date_arrivee' AND '$date_depart')";
            $data = $pdo->query($query);
            $data->execute();
            if ($data->rowCount()){
                $errorDate_depart .= '<p class="font-italic text-danger">Cette date correspond déja à un produit identique</p>';
                $error = true;
            }
        }











        if (empty($prix)){
            $errorPrix .= '<p class="font-italic text-danger">Ce champ doit être rempli</p>';
            $error = true;
        }
        if (!is_numeric($prix)){
            $errorPrix .= '<p class="font-italic text-danger">Ce champ doit numérique</p>';
            $error = true;
        }
    }










    if(!isset($error)){

        if(isset($_GET['action']) && $_GET['action'] == 'ajout'){
            $data = $pdo->prepare("INSERT INTO produit (date_arrivee, date_depart, prix, etat, id_salle) 
            VALUES  (:date_arrivee, :date_depart, :prix, :etat, :id_salle)");


            $_GET['action'] = 'affichage';

            $validInsert = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le produit a bien été enregistré</p>";
            $data->bindValue(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
            $data->bindValue(':date_depart', $date_depart, PDO::PARAM_STR);
            $data->bindValue(':prix', $prix, PDO::PARAM_STR);
            $data->bindValue(':etat', $etat, PDO::PARAM_STR);
            $data->bindValue(':id_salle', $id_salle, PDO::PARAM_INT);

        }
        else
        {
            $data = $pdo->prepare("UPDATE produit SET date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix, etat = :etat WHERE id_produit = :id_produit");


            $_GET['action'] = 'affichage';

            $validUpdate = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le produit a bien été modififé</p>";


            $data->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
            $data->bindValue(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
            $data->bindValue(':date_depart', $date_depart, PDO::PARAM_STR);
            $data->bindValue(':prix', $prix, PDO::PARAM_STR);
            $data->bindValue(':etat', $etat, PDO::PARAM_STR);

        }



        $req = $data->execute();



    }
}



?>

<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/gestion_produit.css">


<div class="container-fluid mx-0">
    <div class="row background px-0 mb-5" style="background-color: #2c302e; height: 100%">
        <div class="col-12">
            <div class="row justify-content-start align-items-center my-5">
                <div class="col-md-2 text-center"><p class="text-white">BACKOFFICE</p></div>
                <div class="col-md-5"><a href="?action=affichage" class="col-md-12 btn btn-primary p-2 mb-3 mb-md-0">AFFICHER LES PRODUITS</a></div>
                <div class="col-md-5"><a href="?action=ajout" class="col-md-12 btn btn-primary p-2">AJOUTER UN NOUVEAU PRODUIT</a></div>
            </div>
        </div>
    </div>
</div>







<?php if (isset($validDelete)) echo $validDelete ?>
<?php if(isset($validInsert)) echo $validInsert ?>
<?php if(isset($validUpdate)) echo $validUpdate ?>


<?php if(isset($_GET['action']) && $_GET['action'] == 'affichage'):?>

    <div class="col-12 text-center pt-5 pb-2"><h1 class="display-4 text-center mt-2">Affichage des produits</h1></div>
    <div class="col-12 my-0 py-0 text-center"><p class="reactiver btn btn-secondary">Réactiver la confirmation de supression</p></div>


<div class="col-12 my-5">

    <table id="salle_table" class="table table-bordered table-responsive-xl text-center myTable"><thead><tr>


            <?php
            $data = $pdo->query("SELECT * FROM produit");

            for($i = 0; $i < $data->columnCount(); $i++):
                $colonne = $data->getColumnMeta($i);
                ?>
                <th class="align-middle"><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th class="align-middle">Editer / supprimer</th>
        </tr></thead><tbody class="align-items-center">

        <?php while ($products = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <?php foreach ($products as $key => $value): ?>
                <?php if ($key == 'id_salle'): ?>
                        <?php $temp = $pdo->query("SELECT * FROM salle WHERE id_salle = $value"); ?>
                        <?php $photo = $temp->fetch() ?>
                        <td class="align-middle col-1"><?= $value ?><br><img class="col-12 tab_img" src="<?= $photo['photo']?>" alt="" data-toggle="modal" data-target="#exampleModal10<?= $products['id_salle'] ?>" style="width: 170px"></td>
                <?php elseif ($key == 'date_arrivee' || $key == 'date_depart'): ?>
                        <?php
                        $date = new DateTime($value);
                        ?>
                <td class="align-middle col-1"><?= $date->format('d/m/Y à h:m:s') ?></td>
                    <?php else: ?>
                        <td class="align-middle col-1"><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td class="align-middle col-1">
                    <div>
                        <a href="?action=modification&id_produit=<?= $products['id_produit'] ?>" class="px-2"><i class="fas fa-edit"></i></a>
                        <a href="?action=supression&id_produit=<?= $products['id_produit'] ?>" class="supp px-2 mySuppButton" data-toggle="modal" data-target="#exampleModal<?= $products['id_produit'] ?>"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </td>
            </tr>


            <div class="modal fade myModal text-center" id="exampleModal<?= $products['id_produit'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title h5" id="exampleModalLabel">Attention ! <br>Vous vous apprêtez a supprimer le produit:
                                <br><?= $products['id_produit'] ?> - salle: <?= $photo['titre'] ?></h5>
                        </div>
                        <div class="modal-body h5">
                            Êtes vous sur de vouloir supprimer ?
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div class="ml-4">
                                <input class="form-check-input" type="checkbox" value="1" id="deleteCheckbox">
                                <label class="form-check-label" for="deleteCheckbox">Ne plus me demander</label>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <a href="?action=supression&id_produit=<?= $products['id_produit'] ?>" class="btn btn-danger">Supprimer</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>






            <!--Modal image -->

            <div class="modal fade myModal text-center" id="exampleModal10<?= $products['id_salle'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">

                            <h5 class="modal-title h5" id="exampleModalLabel"><?= ucfirst($photo['titre']) ?></h5>
                        </div>
                        <div class="">
                            <i  data-dismiss="modal" class="far fa-times-circle" style="position:relative; top: -55px; right: -220px;font-size: 30px"></i>
                        </div>
                        <div class="modal-body col-12 h5">
                            <img class="image-link col-12" src="<?= $photo['photo'] ?>" alt="image salle <?= $value ?>"
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal image -->
        <?php endwhile; ?>
        </tbody></table>
</div>

<?php endif; ?>



<?php
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')):
    if (isset($_GET['id_produit'])){
        $data = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
        $data->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
        $data->execute();

        $produitActuel = $data->fetch(PDO::FETCH_ASSOC);

        foreach ($produitActuel as $key => $value){
            $$key = (isset($produitActuel["$key"])) ? $produitActuel["$key"] : '';
        }

    }

    ?>


    <h1 class="display-4 text-center mt-2"><?= ucfirst($_GET['action']) ?> Produit</h1><hr>
    <div class="container">
        <form method="post" enctype="multipart/form-data">





            <div class="form-row justify-content-center">
                <div class="form-group col-md-6">
                    <label for="date_arrivee"><b>Date d'arrivée</b></label><br>
                    <input type="datetime-local" class="form-control" id="date_arrivee" name="date_arrivee" value="<?php if (isset($date_arrivee)) echo $date_arrivee?>">
                    <?php if (isset($errorDate_arrivee)){echo $errorDate_arrivee;};?>
                    <?php if (isset($errorDate)){echo $errorDate;};?>
                </div>

                <div class="form-group col-md-6">
                    <label for="date_depart"><b>Date de départ</b></label><br>
                    <input type="datetime-local" class="form-control" id="date_depart" name="date_depart" value="<?php if (isset($date_depart)) echo $date_depart?>">
                    <?php if (isset($errorDate_depart)){echo $errorDate_depart;}?>
                    <?php if (isset($errorDate)){echo $errorDate;}?>
                </div>
            </div>







            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="prix"><b>Prix</b></label>
                    <input type="text" class="form-control" id="prix" name="prix" value="<?php if (isset($prix)) echo $prix?>">
                    <?php if (isset($errorPrix)){echo $errorPrix;}?>

                </div>
                <div class="form-group col-md-6">
                    <label for="etat"><b>Etat</b></label>
                    <select class="form-control" name="etat" id="etat">
                        <option value="libre" <?php if (isset($etat) && $etat == 'libre'){echo 'selected';} ?>>Libre</option>
                        <option value="reservation" <?php if (isset($etat) && $etat == 'reservation'){echo 'selected';} ?>>Réservation</option>
                    </select>
                </div>

                <?php if (isset($_GET['action']) && $_GET['action'] == 'ajout'): ?>
                <div class="form-group col-md-6">
                    <label for="id_salle">Salle</label>
                    <select class="form-control" name="id_salle" id="id_salle">
                    <?php
                        $data = $pdo->query("SELECT * FROM salle");
                        $salle = $data->fetchAll();?>
                        <?php foreach ($salle as $key => $value): ?>
                            <?php if ($value['id_salle']): ?>
                                <option value="<?=  $value['id_salle'].' - '.$value['titre']  ?>"><?= $value['id_salle'].' - '.$value['titre'] ?></option>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>


            <div class="form-group">
                <button type="submit" class="btn btn-dark">Ajout produit</button>
            </div>
        </form>
    </div>
<?php endif?>

<script src="<?= SITE_ROOT ?>/includes/moment-develop/locale/fr.js"></script>

<script src="<?= SITE_ROOT ?>/includes/js/datepicker.js"></script>

<?php
require_once '../includes/footer.php';
?>


