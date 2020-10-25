<?php
require_once 'includes/init.php';

if (isset($_GET['id_produit'])){
    $data2 = $pdo->prepare("SELECT * FROM produit as p LEFT JOIN salle as s ON s.id_salle = p.id_salle WHERE p.id_produit = :id_produit");
    $data2->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $data2->execute();




    if($data2->rowCount()){
        $ligne2 = $data2->fetch(PDO::FETCH_ASSOC);
        $myId = $ligne2['id_salle'];


    }else{
        header('Location:' . SITE_ROOT . 'boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=');
    }
}else{
    header('Location:' . SITE_ROOT . 'boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=');
}





if ($_POST) {

    if(!connection())
    {
        header('Location:' . SITE_ROOT . 'connexion.php' );
    }


    extract($_POST);


    $errornote = '';
    $errorcomment = '';


    if (isset($_GET['review']) && $_GET['review'] == 'true') {
        if (!is_numeric($note)) {
            $errornote .= '<p class="font-italic text-danger">Ce champ requiert un chiffre alpha numéric</p>';
            $error = true;
        }
        $note = (int)$note;


        if ($note > 100 || $note < 0) {
            $errornote .= '<p class="font-italic text-danger">Note doit etre compris entre 0 et 100</p>';
            $error = true;
        }
        if(empty($commentaire)){
            $errorcomment .= '<p class="font-italic text-danger">Veuillez remplir le commentaire</p>';

            $error = true;
        }

    }


    if (!isset($error)) {

        $data = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :commentaire, :note, NOW())");


        $_GET['review'] = 'false';

        $validUpdate = "<p class='bg-success col-md-6 p-3 mx-auto text-white text-center'>L'avis a bien été ajouté</p>";


        $data->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $data->bindValue(':note', $note, PDO::PARAM_INT);
        $data->bindValue(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
        $data->bindValue(':id_salle', $myId, PDO::PARAM_INT);
        $data->bindValue(':note', $note, PDO::PARAM_INT);


        $req = $data->execute();


    }

}

$data_avis2 = $pdo->query("SELECT * FROM avis as a LEFT JOIN membre as m ON a.id_membre = m.id_membre WHERE a.id_salle = $myId");

$data_avis3 = $pdo->query("SELECT AVG(note) as moyenne FROM avis as a  WHERE id_salle = $myId");

$star_moyenne = $data_avis3->fetchAll();
$star_moyenne = star($star_moyenne[0]['moyenne']);


require_once ('includes/header.php');
?>

    <link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/fiche_produit.css">



    <!-- Page Content -->
    <div class="container-fluid">

        <div class="row align-items-center justify-content-center">


            <!-- /.col-lg-3 -->

            <div class="col-lg-9">
                <div class="card mt-5">
                    <div id="titre_fiche_produit" class="col-12 text-center">
                        <h3 class="card-title display-3 mb-5 mt-5"><b><?= $ligne2['titre'] ?></b></h3>
                        <p><?= $star_moyenne?></p>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <img class="card-img-top" src="<?=$ligne2['photo']?>" alt="">
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <p class="py-0 display-4">Description:</p>
                            </div>
                            <div class="text-center">
                                <p class="card-text"><?= $ligne2['description'] ?></p>
                            </div>
                            <div>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-12 py-5">
                            <div class="row justify-content-center">
                                <p class="display-4">Nous trouver sur google maps</p>
                            </div>
                            <div>
                                <iframe id="frame" width="100%" height="250" src="http://maps.google.fr/maps?q=<?= $ligne2['adresse'] .', ' . $ligne2['cp'] . ', ' .  $ligne2['ville'] . ' - ' . $ligne2['pays'] ?>&t=&output=embed"
                                        frameborder="0" scrolling="no" marginheight="0" marginwidth="20"></iframe>
                            </div><br />
                        </div>
                    </div>
                    <div class="card-body    py-0">
                        <div class="row">
                            <div class="col-4">

                                <?php
                                $date_depart = new DateTime($ligne2['date_depart']);
                                $date_arrivee = new DateTime($ligne2['date_arrivee']);
                                ?>
                                <p class="card-text"><i class="fas fa-calendar-alt"></i>Arrivée le <?= $date_arrivee->format('d/m/Y') . ' - ' . $date_arrivee->format('H') . ' h'?></p>
                                <p class="card-text"><i class="fas fa-calendar-alt"></i>Départ le <?= $date_depart->format('d/m/Y') . ' - ' . $date_depart->format('H') . ' h'?></p>
                            </div>
                            <div class="col-4">
                                <p class="card-text"><i class="fas fa-user"></i>Capacite: <?= $ligne2['capacite'] ?></p>
                                <p class="card-text"><i class="fas fa-server"></i>Categorie: <?= $ligne2['categorie'] ?></p>
                            </div>
                            <div class="col-4">
                                <p class="card-text"><i class="fas fa-map-marker-alt"></i>Adresse: <?= $ligne2['adresse'] .', ' . $ligne2['cp'] . ', ' .  $ligne2['ville'] . ' - ' . $ligne2['pays']   ?></p>
                                <p class="card-text"><i class="fas fa-euro-sign"></i>Tarif: <?= $ligne2['prix'] ?> €</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <?php  if ($ligne2['etat'] == 'reservation'): ?>
                                    <p class="text-center text-danger font-italic">Le produit est actuellement indisponible</p>
                                <?php else: ?>
                                    <form class="pt-5" action="<?= SITE_ROOT ?>panier.php" method="post">
                                        <input type="hidden" name="id_produit" value="<?= $ligne2['id_produit'] ?>">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success col-12" name="ajout_panier" value="Ajouter au panier">
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <div></div>
                </div>
                <?php if (isset($validUpdate)){echo $validUpdate;} ?>






                <?php if (isset($_GET['review']) && $_GET['review'] == 'true'): ?>
                    <div class="container">
                        <h1 class="display-4 text-center mt-2">Laissez votre Avis</h1><hr>

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
                                    <label class="col-12 px-0" for="commentaire">Votre Commentaire</label>
                                    <?php if(isset($errorcomment)) echo $errorcomment ?>
                                    <textarea class="form-control" name="commentaire" id="commentaire" cols="50" rows="10"><?php if (isset($commentaire)) echo $commentaire?></textarea>
                                </div>


                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-dark">Ajout commentaire</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- /.card -->




                <div class="card card-outline-secondary my-4">

                    <div class="card-header">
                        Avis produit
                    </div>
                    <div class="card-body">
                        <?php if ($data_avis2->rowCount()): ?>
                        <?php $avis = $data_avis2->fetchAll(PDO::FETCH_ASSOC);?>
                            <?php for($i = 0; $i < count($avis); $i++):?>

                            <?php $date = substr($avis[$i]['date_enregistrement'], 0, 10) ?>
                            
                            

                            <p>Note: <?= star($avis[$i]['note'])?></p>
                            <p><?= $avis[$i]['commentaire'] ?></p>
                            <?php $poste_date = new DateTime($avis[$i]['date_enregistrement']);?>
                            <small class="text-muted">Poster par <?= $avis[$i]['pseudo'] ?> le <?= $poste_date->format('d/m/Y') ?></small>
                            <hr>
                            <?php endfor; ?>
                        <?php endif; ?>
                        <a href="?id_produit=<?= $_GET['id_produit'] ?>&review=true" class="btn btn-warning">Laisser un commentaire</a>
                    </div>
                </div>

                <!-- /.card -->


            </div>
            <!-- /.col-lg-9 -->
        </div>

    </div>
    <!-- /.container -->



<?php
require_once ('includes/footer.php');
?>