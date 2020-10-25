<?php
require_once '../includes/init.php';

if(!admin())
{
    header('Location:' . SITE_ROOT . 'connexion.php' );
}








if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $test ='';
    $produitDelete = $_GET['id_salle'];
    $verif = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $verif->bindValue(':id_salle', $produitDelete, PDO::PARAM_INT);
    $verif->execute();
    if ($verif->rowCount() == 0)
    {
        $validDelete = "<p class='bg-danger col-md-6 p-3 mx-auto mt-3 text-white text-center'>Erreur : Le produit n'a pas été supprimé</p>";

    }
    else
    {
        $test = "<div>Etes vous sur ?</div>";
        $data = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
        $data->bindValue(':id_salle', $produitDelete, PDO::PARAM_INT);
        $data->execute();
        $GET['action'] = 'affichage';
        $validDelete = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le produit <strong>$_GET[id_salle]</strong> a bien été supprimé</p>";
    }



}


if ($_POST)
{
    extract($_POST);



    $photoBdd = '';
    $errorEmpty = '';
    $errorCp = '';
    $errorCategorie = '';
    $errorVille = '';



    if (empty($_POST['titre']) || empty($_POST['pays']) || empty($_POST['ville']) || empty($_POST['adresse']) || empty($_POST['cp']) || empty($_POST['capacite']) || empty($_POST['description'])){
        $errorEmpty .= '<p class="font-italic text-danger">Attention tous les champs doivent être rempli</p>';
        $error = true;
    }



    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $photoBdd = $photo_actuelle;
    }

    if (!is_numeric($_POST['capacite'])){
        $errorCategorie .= '<p class="font-italic text-danger">Attention ce champs doit contenir une valeur numérique</p>';
        $error = true;
    }
    if (!is_numeric($_POST['cp'])){
        $errorCp .= '<p class="font-italic text-danger">Attention ce champs doit contenir une valeur numérique</p>';
        $error = true;
    }

    if (!is_string($_POST['ville']))
    {
        $errorVille .= '<p class="font-italic text-danger">Attention les chiffres ne sont pas acceptés</p>';
        $error = true;
    }


    if(!empty($_FILES['photo']['name']))
    {
        $listExt = array(1 => 'jpg', 2 => 'jpeg', 3 => 'png');

        $fichier = new SplFileInfo($_FILES['photo']['name']);


        $ext = strtolower($fichier->getExtension());


        $positionExt = array_search($ext, $listExt);

        if ($positionExt == false)
        {
            $errorUpload .= '<p class="font-italic text-danger">Type de fichier non autorisé</p>';
            $error = true;
        }
        else
        {

            $nomPhoto = $fichier->getFilename();


            $photoBdd = SITE_ROOT . "photo/$nomPhoto";

            $photoDossier = SERVER_ROOT . "photo/$nomPhoto";

            move_uploaded_file($_FILES['photo']['tmp_name'], $photoDossier);
        }


    }
    elseif(isset($_GET['action']) && $_GET['action'] == 'ajout' && empty($_FILES['photo']['name'])){
        $errorUpload = "<p class='text-danger font-italic'>Merci d'uploader une image</p>";
        $error = true;
    }



    if(!isset($error)){

        if(isset($_GET['action']) && $_GET['action'] == 'ajout'){
            $data = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) 
            VALUES  (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");


            $_GET['action'] = 'affichage';

            $validInsert = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le produit <strong>$titre</strong> a bien été enregistré</p>";

        }else
        {
            $data = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays,
                ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");


            $_GET['action'] = 'affichage';

            $validUpdate = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>Le produit <strong>$titre</strong> a bien été modififé</p>";


            $data->bindValue(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);

        }

        $data->bindValue(':titre', $titre, PDO::PARAM_STR);
        $data->bindValue(':description', $description, PDO::PARAM_STR);
        $data->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
        $data->bindValue(':pays', $pays, PDO::PARAM_STR);
        $data->bindValue(':ville', $ville, PDO::PARAM_STR);
        $data->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $data->bindValue(':cp', $cp, PDO::PARAM_STR);
        $data->bindValue(':capacite', $capacite, PDO::PARAM_STR);
        $data->bindValue(':categorie', $categorie, PDO::PARAM_STR);

        $req = $data->execute();



    }
}



require_once '../includes/header.php';
?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/gestion_salle.css">



<div class="container-fluid mx-0">
    <div class="row background px-0 mb-5" style="height: 100%">
        <div class="col-12">
            <div class="row justify-content-start align-items-center my-5">
                <div class="col-md-2 text-center"><p class="text-white">BACKOFFICE</p></div>
                <div class="col-md-5 "><a href="?action=affichage" class="col-md-12 btn btn-primary p-2 mb-3 mb-md-0">AFFICHER LES SALLES</a></div>
                <div class="col-md-5"><a href="?action=ajout" class="col-md-12 btn btn-primary p-2">AJOUTER UNE NOUVELLE SALLE</a></div>
            </div>
        </div>
    </div>
</div>







<?php if (isset($validDelete)) echo $validDelete ?>
<?php if(isset($validInsert)) echo $validInsert ?>
<?php if(isset($validUpdate)) echo $validUpdate ?>


<?php if(isset($_GET['action']) && $_GET['action'] == 'affichage'):?>

    <div class="col-12 text-center pt-5 pb-2"><h1 class="display-4 text-center mt-2">Affichage des salles</h1></div>
    <div class="col-12 my-0 py-0 text-center"><p class="reactiver btn btn-secondary">Réactiver la confirmation de supression</p></div>


    <div class="col-12 my-5">
    <table id="salle_table" class="table table-bordered table-responsive-xl text-center myTable"><thead><tr>


            <?php
            $data = $pdo->query("SELECT * FROM salle");

            for($i = 0; $i < $data->columnCount(); $i++):
                $colonne = $data->getColumnMeta($i);
                ?>
                <th class="align-middle"><?=str_replace('_', ' ', ucfirst($colonne['name']) )  ?></th>
            <?php endfor; ?>
            <th class="align-middle">Editer / supprimer</th>
        </tr></thead><tbody>

        <?php while ($products = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr >
                <?php foreach ($products as $key => $value): ?>
                    <?php if ($key == 'photo'): ?>
                        <td class="align-middle"><a href="<?= $value ?>" data-toggle="modal" data-target="#exampleModal10<?= $products['id_salle'] ?>"><img class="image-link tab_img" src="<?= $value ?>" alt="image salle <?= $value ?>" style="width: 150px"></a></td>
                <?php elseif ($key == 'description'): ?>
                <td class="align-middle col-1"><?= strip_tags(substr(ucfirst($value),0, 50)) . '...' ?></td>



                <?php else: ?>
                        <td class="align-middle col-1"><?= ucfirst($value)?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td class="align-middle col-1">
                    <div>
                        <a href="?action=modification&id_salle=<?= $products['id_salle'] ?>" class="px-2"><i class="fas fa-edit"></i></a>
                        <a href="?action=supression&id_salle=<?= $products['id_salle'] ?>" class="supp px-2 mySuppButton" data-toggle="modal" data-target="#exampleModal<?= $products['id_salle'] ?>"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </td>

            </tr>








            <!--Modal supp -->
            <div class="modal fade myModal text-center" id="exampleModal<?= $products['id_salle'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title h5" id="exampleModalLabel">Attention ! <br>Vous vous apprêtez a supprimer la salle:
                                <br><?= $products['id_salle'] ?> - <?= $products['titre'] ?></h5>
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
                                <a href="?action=supression&id_salle=<?= $products['id_salle'] ?>" class="btn btn-danger">Supprimer</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--Modal supp -->






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
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')):
    if (isset($_GET['id_salle'])){
        $data = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
        $data->bindValue(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);
        $data->execute();

        $produitActuel = $data->fetch(PDO::FETCH_ASSOC);

        foreach ($produitActuel as $key => $value){
            $$key = (isset($produitActuel["$key"])) ? $produitActuel["$key"] : '';
        }

    }
?>


    <h1 class="display-4 text-center mt-2"><?= ucfirst($_GET['action']) ?> produit</h1><hr>
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" value="<?php if (isset($titre)) echo $titre?>">
            </div>
            <div class="form-group col-md-6">
                <label for="pays">Pays</label>
                <input type="text" class="form-control" id="pays" name="pays" value="<?php if (isset($pays)) echo $pays?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?php if (isset($ville)) echo $ville?>">
                <?php if(isset($errorVille)) echo $errorVille;?>
            </div>
            <div class="form-group col-md-6">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?php if (isset($adresse)) echo $adresse?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="cp">Code Postal</label>
                <input type="text" class="form-control" id="cp" name="cp" value="<?php if (isset($cp)) echo $cp?>">
                <?php if (isset($errorCp)) echo $errorCp;?>
            </div>
            <div class="form-group col-md-6">
                <label for="capacite">Capacité</label>
                <input type="text" class="form-control" id="capacite" name="capacite" value="<?php if (isset($capacite)) echo $capacite?>">
                <?php if (isset($errorCategorie)) echo $errorCategorie;?>
            </div>
        </div>




        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
                <?php if(isset($errorUpload)) echo $errorUpload?>
            </div>
            <div class="form-group col-md-6">
                <label for="categorie">Catégorie</label>
                <select class="form-control" name="categorie" id="categorie">
                    <option value="reunion" <?php if (isset($categorie) && $categorie =='reunion') echo 'selected'?>>Réunion</option>
                    <option value="bureau" <?php if (isset($categorie) && $categorie =='bureau') echo 'selected'?>>Bureau</option>
                    <option value="formation" <?php if (isset($categorie) && $categorie =='formation') echo 'selected'?>>Formation</option>
                </select>
            </div>
        </div>
        <?php if(isset($photo) && !empty($photo)): ?>
            <div class="text-center" style="border: #ced4da 1px solid; border-radius: 0.15rem">
                <h6>Photo du produit</h6>
                <img src="<?= $photo ?>" alt="<?= $titre ?>" class="col-md-4 mx-auto" style="width: 300px"><br>
                <em>Vous pouvez uploader une nouvelle photo si vous souhaitez la changer</em><br>

            </div>
        <?php endif; ?>
        <input type="hidden" name="photo_actuelle" value="<?php if(isset($photo)) echo $photo?>">

        <label class="mt-5" for="titre">Description</label>
        <div class="form-row">
            <div class="form-group col-md-12">
                <textarea class="form-control textarea" name="description" id="description" cols="50" rows="10" wrap="hard"><?php if (isset($description)) echo $description?></textarea>
            </div>
        </div>


        <?php if(isset($errorEmpty)) echo $errorEmpty ?>
        <button type="submit" class="btn btn-dark my-5">Ajout produit</button>
    </form>

</div>
<?php endif?>


<script src="">



</script>



<?php
require_once '../includes/footer.php';
?>

























