<?php
require_once  '../includes/header.php';

if(!admin())
{
    header('Location:' . SITE_ROOT . 'connexion.php' );
}


if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $produitDelete = $_GET['id_avis'];
    $data = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $data->bindValue(':id_avis', $produitDelete, PDO::PARAM_INT);
    $data->execute();
    $GET['action'] = 'affichage';
    $validDelete = "<p class='bg-success col-md-6 p-3 mx-auto mt-3 text-white text-center'>Le membre <strong>$_GET[id_avis]</strong> a bien été supprimé</p>";

}


if(isset($_GET['id_avis']))
{
    $verif_date = $pdo->prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
    $verif_date->bindValue(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
    $verif_date->execute();
    $temp_date = $verif_date->fetch();
}


if ($_POST)
{
    extract($_POST);




    $errornote = '';
    $errorcomment = '';




    if (isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if (empty($_POST['commentaire']) || empty($_POST['note'])){
            $errorEmpty .= '<p class="font-italic text-danger">Attention tous les champs doivent être rempli</p>';
            $error = true;
        }
        if (!is_numeric($note)){
            $errornote .= '<p class="font-italic text-danger">Ce champ requiert un chiffre alpha numéric</p>';
            $error = true;
        }
        $note = (int)$note;


        if ($note > 100 || $note < 0)
        {
            $errornote .= '<p class="font-italic text-danger">Note doit etre compris entre 0 et 100</p>';
            $error = true;
        }
        if(empty($commentaire)){
            $errorcomment .= '<p class="font-italic text-danger">Veuillez remplir le commentaire</p>';

            $error = true;
        }

    }






    if(!isset($error)){

        $data = $pdo->prepare("UPDATE avis SET commentaire = :commentaire, note = :note WHERE id_avis = :id_avis");


        $_GET['action'] = 'affichage';

        $validUpdate = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>L'avis a bien été modififé</p>";


        $data->bindValue(':id_avis', $_GET['id_avis'], PDO::PARAM_INT);

        $data->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $data->bindValue(':note', $note, PDO::PARAM_INT);




        $req = $data->execute();



    }
}
?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/gestion_avis.css">


<div class="container-fluid mx-0">
    <div class="row background px-0" style="background-color: #2c302e; height: 100%">
        <div class="col-12">
            <div class="row justify-content-start align-items-center my-5">
                <div class="col-md-12 text-center"><p class="text-white">BACKOFFICE</p></div>
                <div class="col-12 d-flex justify-content-center text-center">
                    <div class="col-md-5"><a href="?action=affichage" class="col-md-12 myBackground btn btn-primary p-2">AFFICHER LES AVIS</a></div>
                </div>
            </div>

        </div>
    </div>
</div>





<?php if (isset($validDelete)) echo $validDelete ?>
<?php if(isset($validInsert)) echo $validInsert ?>
<?php if(isset($validUpdate)) echo $validUpdate ?>



<?php if(isset($_GET['action']) && $_GET['action'] == 'affichage'):?>

    <div class="col-12 text-center py-5"><h1>Affichage des avis</h1></div>
    <div class="col-12 my-0 py-0 text-center"><p class="reactiver btn btn-secondary">Réactiver la confirmation de supression</p></div>


<div class="col-12 my-5">
    <table id="salle_table" class="table table-bordered table-responsive-xl text-center myTable"><thead><tr>


            <?php
            $data = $pdo->query("SELECT * FROM avis");

            for($i = 0; $i < $data->columnCount(); $i++):
                $colonne = $data->getColumnMeta($i);
                ?>
                <th class="align-middle"><?=str_replace('_', ' ', ucfirst($colonne['name'])) ?></th>
            <?php endfor; ?>
            <th>Editer / supprimer</th>
        </tr></thead><tbody>


        <?php while ($products = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <?php foreach ($products as $key => $value): ?>
                    <?php if ($key == 'photo'): ?>
                        <td class="align-middle"><img src="<?= $value?>" alt="" style="width: 150px"></td>
                    <?php elseif ($key == 'date_enregistrement'): ?>
                        <?php
                        $date = new DateTime($value);
                        ?>
                        <td class="align-middle"><?= $date->format('d/m/Y à h:m:s')?></td>
                    <?php else: ?>
                        <td class="align-middle"><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td class="align-middle col-1">
                    <div>
                        <a href="?action=modification&id_avis=<?= $products['id_avis'] ?>" cclass="px-2"><i class="fas fa-edit"></i></a>
                        <a href="?action=supression&id_avis=<?= $products['id_avis'] ?>" class="supp px-2 mySuppButton" data-toggle="modal" data-target="#exampleModal<?= $products['id_avis'] ?>"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </td>

            </tr>


            <div class="modal fade myModal text-center" id="exampleModal<?= $products['id_avis'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title h5" id="exampleModalLabel">Attention ! <br>Vous vous apprêtez a supprimer l'avis:
                                <br><?= $products['id_avis']?></h5>
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
                                <a href="?action=supression&id_avis=<?= $products['id_avis'] ?>" class="btn btn-danger">Supprimer</a>
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
    if (isset($_GET['id_avis'])){
        $data = $pdo->prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
        $data->bindValue(':id_avis', $_GET['id_avis'], PDO::PARAM_INT);
        $data->execute();

        $produitActuel = $data->fetch(PDO::FETCH_ASSOC);
        echo '<pre>'; print_r($produitActuel['id_avis']); echo '</pre>';

        foreach ($produitActuel as $key => $value){
            $$key = (isset($produitActuel["$key"])) ? $produitActuel["$key"] : '';
        }

    }

    ?>

    <h1 class="display-4 text-center mt-2"><?= ucfirst($_GET['action']) ?> Avis</h1><hr>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="note">Note</label>
                        <select class="form-control" name="note" id="note">
                            <?php for ($i = 1; $i <=10; $i++): ?>
                            <option <?php if (isset($note)) echo 'selected'?> value="<?=$i?>"><?=$i?></option>
                            <?php endfor; ?>
                        </select>
                    <?php if(isset($errornote)) echo $errornote ?>
                </div>

                <div class="form-group col-12">
                    <label for="commentaire">Commentaire</label>
                    <?php if(isset($errorcomment)) echo $errorcomment ?>
                    <textarea class="form-control" name="commentaire" id="commentaire" cols="50" rows="10"><?php if (isset($commentaire)) echo $commentaire?></textarea>
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
























