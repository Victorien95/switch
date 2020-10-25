<?php
require_once '../includes/header.php';
if(!admin())
{
    header('Location:' . SITE_ROOT . 'connexion.php' );
}


if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $produitDelete = $_GET['id_commande'];
    $data = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $data->bindValue(':id_commande', $produitDelete, PDO::PARAM_INT);
    $data->execute();
    $GET['action'] = 'affichage';
    $validDelete = "<p class='bg-success col-md-6 p-3 mx-auto mt-3 text-white text-center'>Le produit <strong>$_GET[id_commande]</strong> a bien été supprimé</p>";

}

if ($_POST)
{
    extract($_POST);

}


?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/gestion_commandes.css">


<div class="container-fluid mx-0">
    <div class="row background px-0" style="background-color: #2c302e; height: 100%">
        <div class="col-12">
            <div class="row justify-content-start align-items-center my-5">
                <div class="col-md-12 text-center"><p class="text-white">BACKOFFICE</p></div>
                <div class="col-12 d-flex justify-content-center text-center">
                    <div class="col-md-5"><a href="?action=affichage" class="col-md-12 btn btn-primary p-2">AFFICHER LES COMMANDES</a></div>
                </div>
            </div>

        </div>
    </div>
</div>






<?php if (isset($validDelete)) echo $validDelete ?>



<?php if(isset($_GET['action']) && $_GET['action'] == 'affichage'):?>

    <div class="col-12 text-center py-5"><h1 class="display-4 text-center mt-2">Affichage des commandes</h1></div>
    <div class="col-12 my-0 py-0 text-center"><p class="reactiver btn btn-secondary">Réactiver la confirmation de supression</p></div>

<div class="col-12 my-5">

    <table id="salle_table" class="table table-bordered table-responsive-xl text-center myTable"><thead class="align-middle"><tr>


            <?php
            $data = $pdo->query("SELECT * FROM commande as c LEFT JOIN membre as m ON c.id_membre = m.id_membre LEFT JOIN produit as p ON p.id_produit = c.id_produit LEFT JOIN salle as s ON s.id_salle = p.id_salle"); ?>

                <th  class="align-middle">Id commande</th>
                <th  class="align-middle">Id membre - Mail</th>
                <th  class="align-middle">Id produit</th>
                <th  class="align-middle">Date d'enregistrement</th>
                <th  class="align-middle">Prix</th>
                <th  class="align-middle">Supprimer</th>
            </tr>
            </thead><tbody>
        <?php while ($products = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <?php foreach ($products as $key => $value): ?>

                    <?php
                    $date1 = new DateTime($products['date_arrivee']);
                    $date2 = new DateTime($products['date_depart']);
                    ?>
                    <?php if ($key == 'id_commande'): ?>
                        <td class="align-middle col-1"><?= $products['id_commande']?></td>
                    <?php elseif($key == 'id_membre'): ?>
                        <td class="align-middle col-1"><?= $products['id_membre'] . ' - ' . $products['email']?></td>
                    <?php elseif($key == 'id_produit'): ?>
                        <td class="align-middle col-1"><?= $products['id_produit'] . ' - ' . $products['titre']?>
                            <br><img data-toggle="modal" data-target="#exampleModal10<?= $products['id_salle'] ?>" style="max-width: 150px" src="<?=$products['photo']?>" alt="<?= $products['photo']?>">
                            <br>Du <?= $date2->format('d/m/Y à H')?>h
                            <br>au <?= $date2->format('d/m/Y à H')?>h</td>
                    <?php elseif($key == 'prix'): ?>
                        <td class="align-middle col-1"><?= $products['prix']?></td>
                    <?php elseif($key == 'date_enregistrement'): ?>
                        <?php
                        $date = new DateTime($value);
                        ?>

                        <td class="align-middle col-1"><?= $date->format('d/m/Y à h:m:s')?></td>
                    <?php else: ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td class="align-middle col-1"><a href="?action=supression&id_commande=<?= $products['id_commande'] ?>" class="supp px-2 mySuppButton" data-toggle="modal" data-target="#exampleModal<?= $products['id_commande'] ?>"><i class="fas fa-trash-alt"></i></a></td>

            </tr>


            <div class="modal fade myModal text-center" id="exampleModal<?= $products['id_commande'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title h5" id="exampleModalLabel">Attention ! <br>Vous vous apprêtez a supprimer la commande:
                                <br><?= $products['id_commande'] ?> commandé par <?= $products['email'] ?></h5>
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
                                <a href="?action=supression&id_commande=<?= $products['id_commande'] ?>" class="btn btn-danger">Supprimer</a>
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

                            <h5 class="modal-title h5" id="exampleModalLabel"><?= ucfirst($products['titre']) ?></h5>
                        </div>
                        <div class="">
                            <i  data-dismiss="modal" class="far fa-times-circle" style="position:relative; top: -55px; right: -220px;font-size: 30px"></i>
                        </div>
                        <div class="modal-body col-12 h5">
                            <img class="image-link col-12" src="<?= $products['photo'] ?>" alt="image salle <?= $value ?>"
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
require_once '../includes/footer.php';
?>

























