<?php
require_once ('includes/init.php');


$errorMessage = '';
$errorPrix = '';
$error = '';
$message_session = '';
$by = 'p.prix';
$crs = 'ASC';
$star_moyenne = '';
$dict_val = array();
$conditions = '';

if ($_GET){
    extract($_GET);

    if (!isset($v) || !isset($p) || !isset($c) || !isset($da)  || !isset($dd) || !isset($pm) || !isset($cm) || !isset($elems) || !isset($crs) || !isset($orderby)){
        unset($_SESSION['errorMessage']);
        header('Location:'. SITE_ROOT . 'boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=&crs=&orderby=');
    }


    $dict =
        [
            'ville' => $_GET['v'],
            'pays' => $_GET['p'],
            'categorie' => $_GET['c'],
            'date_arrivee' => $_GET['da'],
            'date_depart' => $_GET['dd'],
            'prix' => $_GET['pm'],
            'capacite' => $_GET['cm'],
        ];

    foreach ($dict as $key => $val){
        if (!empty($val)){
            $dict_val[$key] = $val;
        }
    }




    if (count($dict_val) >= 1){
        foreach ($dict_val as $key => $value)
        {
            $operateur = ' = ';
            if($key == 'prix' || $key == 'date_arrivee' || $key == 'date_depart'){
                $tablechoice = 'p.';
            }else{
                $tablechoice = 's.';
            }
            if ($key == 'prix' || $key == 'date_depart' ) {
                $operateur = ' <= ';
            }
            if ($key == 'capacite' || $key == 'date_arrivee' ){
                $operateur = ' >= ';
            }

            $conditions .= $tablechoice . $key . $operateur .':' . $key . ' AND ';

        }

        $conditions = trim($conditions, ' AND ');
        if (!empty($_GET['orderby'])){
            $by = $_GET['orderby'];
        }
        if (!empty($_GET['crs'])){
            $crs = $_GET['crs'];
        }


        $temp = "SELECT * FROM produit as p LEFT JOIN salle as s ON p.id_salle = s.id_salle WHERE  $conditions";
        $data = $pdo->prepare("SELECT * FROM produit as p LEFT JOIN salle as s ON p.id_salle = s.id_salle WHERE $conditions");

        foreach ($dict_val as $key => $value){
            $data->bindValue(":$key", $value);
        }

        $data->execute();

        if ($data->rowCount() == 0){
            $error = true;
        }else {

            $produitsS = $data->fetchAll();
        }
    }

    elseif(count($dict_val) == 0)
    {

        $error = true;
    }
    if ($error){
        $verif = SITE_ROOT . 'boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=&crs=&orderby=';
        $test = $_SERVER['REQUEST_URI'];
        $errorMessage .= '<p class="font-italic btn btn-outline-danger col-12 alert-danger">La selection demandée est indisponible, changez les paramètres.<br>Tous les produits ont été sélectionnés</p>';
        if ($test == $verif || $test == $verif . '10' || $test == $verif . '20' || $test == $verif . '50')
        {
            $errorMessage = '';
        }
        $temp = "SELECT * FROM produit as p LEFT JOIN salle as s ON p.id_salle = s.id_salle";
        $data = $pdo->query("SELECT * FROM produit as p LEFT JOIN salle as s ON p.id_salle = s.id_salle");
        $produitsS = $data->fetchAll(PDO::FETCH_ASSOC);
    }



}
else{
    header('Location: ' . SITE_ROOT . 'boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems=&crs=&orderby=');

}







$messagesParPage=10;

if(isset($_GET['elems'])){
    $elems = $_GET['elems'];
    if ($elems == '10'){
        $messagesParPage = 10;
    }elseif ($elems == '20'){
        $messagesParPage = 20;
    }elseif ($elems == '50'){
        $messagesParPage = 50;
    }else{
        $messagesParPage = $messagesParPage;
    }
}


$total= count($produitsS);


$nombreDePages=ceil($total/$messagesParPage);
if(isset($_GET['page']))
{
    $pageActuelle=intval($_GET['page']);

    if($pageActuelle>$nombreDePages)
    {
        $pageActuelle=$nombreDePages;
    }
}
else
{
    $pageActuelle=1;
}
$premiereEntree=($pageActuelle-1)*$messagesParPage;

$reqEnd = ' ORDER BY ' . $by . ' ' . $crs . ' LIMIT ' . $premiereEntree . ', ' . $messagesParPage . '';


$new_data = $pdo->prepare($temp . $reqEnd);


foreach ($dict_val as $key => $value){
    $new_data->bindValue(":$key", $value);
}
$new_data->execute();



$produits = $new_data->fetchAll(PDO::FETCH_ASSOC);





if (isset($_GET['page'])){
    $page = $_GET['page'];
}

$form_data = $pdo->query("SELECT * FROM produit ORDER BY prix DESC");
$form_req = $form_data->fetchAll();
$prixMax = $form_req[0]['prix'];
$prixMin = $form_req[count($form_req) - 1]['prix'];

$form_data = $pdo->query("SELECT * FROM salle ORDER BY capacite DESC");
$form_req = $form_data->fetchAll();
$capaciteMax = $form_req[0]['capacite'];
$capaciteMin = $form_req[count($form_req) - 1]['capacite'];




require_once ('includes/header.php');
?>

<link rel="stylesheet" href="<?= SITE_ROOT ?>includes/css/boutique.css">



<!-- Page Content -->
<div id="container-boutique" class="container-fluid mb-5 px-0 mx-0" style="margin-bottom: 0px !important;" >


    <div class="row justify-content-center px-1">


        <div id="nav-boutique" class="col-lg-3 px-1" style="padding-left: 50px !important; padding-right: 50px !important;">
            <h1 class="my-4 display-4 text-center mt-2">Boutique</h1>
            <div class="row text-center justify-content-start pt-3 px-0">
                <div class="col-12 col-lg-6 px-0 mb-2 mb-lg-0">
                    <a id="refresh" href="<?= SITE_ROOT ?>boutique.php?v=&p=&c=&da=&dd=&pm=&cm=&elems="><i class="fas fa-sync-alt mr-md-2 col"></i>Rafraîchir</a>
                </div>
                <div class="col-12 col-lg-6 px-0">
                    <a class="col-2" id="filtres" href="#"><i id="fa_filtre" class="fas fa-sort-amount-up-alt fa-sort-amount-up-alt"></i><p>Filtres</p></a>
                </div>
            </div>
            <div id="filtre_hide">

            <div class="list-group mt-5">
                <div id="villes" class="d-flex justify-content-between boutiquecursor">
                    <div class="col-8 px-0">
                        <h4  class="titres"><i class="fas fa-city mr-2"></i>Villes</h4>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <i id="fa-villes" class="fas fa-angle-up"></i>
                    </div>
                </div>

                    <div id="ville">
                    <?php
                    $data = $pdo->query("SELECT DISTINCT ville FROM salle ORDER BY ville");
                    while($ligne = $data->fetch(PDO::FETCH_ASSOC)):?>
                        <a href='#' class="list-group-item town <?php if(isset($_GET['v']) && $_GET['v'] == ucfirst($ligne['ville'])) echo 'active'?>"><?= ucfirst($ligne['ville']) ?></a>
                    <?php endwhile;?>
                    </div>
            </div>
            <br>
            <div class="list-group">
                <div id="payss" class="d-flex justify-content-between boutiquecursor">
                    <div class="col-8 px-0">
                        <h4  class="titres"><i class="fas fa-globe mr-2"></i>Pays</h4>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <i id="fa-pays" class="fas fa-angle-up"></i>
                    </div>
                </div>

                    <div id="pays">
                        <?php
                        $data = $pdo->query("SELECT DISTINCT pays FROM salle ORDER BY pays");
                        while($ligne = $data->fetch(PDO::FETCH_ASSOC)):?>
                            <a href='#' class="list-group-item pays <?php if(isset($_GET['p']) && $_GET['p'] == ucfirst($ligne['pays'])) echo 'active'?>"><?= ucfirst($ligne['pays']) ?></a>
                        <?php endwhile;?>
                    </div>
            </div>
            <br>
            <div class="list-group">
                <div id="categories" class="d-flex justify-content-between boutiquecursor">
                    <div class="col-8 px-0">
                        <h4 class="titres"><i class="fas fa-server mr-2"></i>Categories</h4>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <i id="fa-categories" class="fas fa-angle-up"></i>
                    </div>
                </div>

                <div id="categorie">
                        <?php
                        $data = $pdo->query("SELECT DISTINCT categorie FROM salle ORDER BY categorie");
                        while($ligne = $data->fetch(PDO::FETCH_ASSOC)):?>
                            <a href='#' class="list-group-item categorie <?php if(isset($_GET['c']) && $_GET['c'] == ucfirst($ligne['categorie'])) echo 'active'?>"><?= ucfirst($ligne['categorie']) ?></a>
                        <?php endwhile;?>
                    </div>
            </div>
            <div class="list-group">

                <label for="date_arrivee"><h4 class="mt-4 titres"><i class="fas fa-calendar-alt mr-2"></i>Date d'arrivée</h4></label>
                    <div class="">
                        <input type="date" class="form-control <?php if(isset($_GET['da']) && $_GET['da'] !== '') echo 'date_select'?>" id="date_arrivee" name="date_arrivee" value="<?php if(isset($_GET['da'])) echo $_GET['da']?>">
                    </div>




                <label for="date_depart"><h4 class="mt-4 titres"><i class="fas fa-calendar-alt mr-2"></i>Date de départ</h4></label>
                    <div class="form-row">
                        <input type="date" class="form-control <?php if(isset($_GET['dd']) && $_GET['dd'] !== '') echo 'date_select'?> " id="date_depart" name="date_depart" value="<?php if(isset($_GET['dd'])) echo $_GET['dd']?>">
                    </div>

                <label for="prix"><h4 class="mt-4 titres"><i class="fas fa-euro-sign mr-2"></i>Prix Max</h4></label>
                    <div class="row justify-content-center">
                        <input type="range" class="form-control" step="100" min="<?php if (isset($prixMin)) echo $prixMin?>" max="<?php if (isset($prixMax)) echo $prixMax?>"  id="prix" name="prix" value="<?php if (isset($_GET['pm'])) echo $_GET['pm']?>">
                        <div id="prix_val"></div>
                    </div>


                <label for="capacite"><h4 class="mt-4 titres"><i class="fas fa-user mr-2"></i>Capacite Minimum</h4></label>
                    <div class="row justify-content-center">
                        <input type="range" class="form-control" min="<?php if (isset($capaciteMin)) echo $capaciteMin?>" step="1" max="<?php if (isset($capaciteMax)) echo $capaciteMax?>"  id="capacite" name="capacite" value="<?php if (isset($_GET['cm'])) echo $_GET['cm']?>">
                        <div id="capacite_val"></div>
                    </div>
            </div>
            </div>

        </div>


        <!-- /.col-lg-3   -->



        <div class="col-12 col-lg-9 align-items-center justify-content-center mx-0 px-0">

            <?php if (isset($errorMessage)):?>
            <div class="col-12">
                <p><?= $errorMessage ?></p>
            </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['errorMessage'])): ?>
                <div class="col-12">
                    <p><?= $_SESSION['errorMessage'] ?></p>
                </div>
            <?php endif; ?>




            <div class="row justify-content-center align-items-center my-5" >


                    <nav class="col-12 mt-5 mt-lg-0 px-0 justify-content-around  mb-5" aria-label="...">
                        <div class="row justify-content-center align-items-center">
                            <ul class="pagination justify-content-center mb-2 my-lg-0 col-lg-6 ">
                                <li class="page-item disabled">
                                    <span class="page-link">Elements</span>
                                </li>
                                <li class="page-item <?php if ($messagesParPage == 10) echo 'active'?>"><a class="page-link" href="#">10</a></li>
                                <li class="page-item <?php if ($messagesParPage == 20) echo 'active'?>"><a class="page-link" href="#">20</a></li>
                                <li class="page-item <?php if ($messagesParPage == 50) echo 'active'?>"><a class="page-link" href="#">50</a></li>
                            </ul>


                            <div class="d-flex align-items-center col-10 col-lg-5">
                                <i class="fas fa-sort mr-1 my-0 py-0 col-1"></i>
                                <select class="form-control mx-0 px-0 col-5" name="tri" id="tri">
                                    <optgroup label="Tri:">
                                        <option value="p.prix" <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'p.prix' ) echo 'selected'; ?>>Prix</option>
                                        <option  value="p.date_arrivee" <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'p.date_arrive' ) echo 'selected'; ?>>Date d'arrivée</option>
                                        <option value="p.date_depart" <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'p.date_depart' ) echo 'selected'; ?>>Date de départ</option>
                                        <option value="s.capacite" <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 's.capacite' ) echo 'selected'; ?>>Capacité</option>
                                    </optgroup>
                                </select>
                                <select class="form-control mx-0 px-0 ml-2 col-5" name="crs" id="crs">
                                    <optgroup label="Ordre:">
                                        <option value="ASC" <?php if (isset($_GET['crs']) && $_GET['crs'] == 'ASC' ) echo 'selected'; ?>>Croissant</option>
                                        <option  value="DESC" <?php if (isset($_GET['crs']) && $_GET['crs'] == 'DESC' ) echo 'selected'; ?>>Décroissant</option>
                                    </optgroup>
                                </select>
                            </div>

                        </div>

                    </nav>


                <!-- < $last_url . $sep ?> -->

                <?php foreach ($produits as $key => $tab): ?>
                        <div class="card col-md-4 col-lg-3 mb-5 mx-md-3 px-0 mx-5">
                            <a href="#"><img data-toggle="modal" data-target="#exampleModal10<?= $tab['id_salle'] ?>" class="card-img-top" src="<?= $tab['photo'] ?>" alt="<?= 'image salle ' . $tab['titre'] ?>"></a>
                            <div class="card-body">
                                <div class="text-center mb-1" style="border-bottom: 2px solid rgba(0,0,0,.125)">
                                    <h4 class="card-title" style="font-size: 30px">
                                        <a href="fiche_produit.php?id_produit=<?= $tab['id_produit'] ?>"><?= $tab['titre']?></a>
                                    </h4>
                                </div>
                                <div class="text-center">
                                    <p><b>Description:</b></p>
                                    <p class="card-text"><?= strip_tags(substr($tab['description'], 0, 200))  ?> ...</p>
                                </div>
                                <div class="row mt-3 px-0">
                                    <div class="col-12 px-0">
                                        <?php
                                        $date_depart = new DateTime($tab['date_depart']);
                                        $date_arrivee = new DateTime($tab['date_arrivee']);
                                        ?>


                                        <p style="border-radius: 0" class="card-text px-1 col-12 badge-pill badge-success"><i class="fas fa-calendar-alt mr-2"></i>Arrivé: <?= $date_arrivee->format('d/m/Y') . ' - ' . $date_arrivee->format('H') . ' h'?></p>
                                        <p style="border-radius: 0" class="card-text px-1 col-12 badge-pill badge-warning"><i class="fas fa-calendar-alt mr-2"></i>Départ: <?= $date_depart->format('d/m/Y') . ' - ' . $date_depart->format('H') . ' h'?></p>

                                    </div>
                                    <div class="row col-12 justify-content-between my-2 px-0 mx-1">
                                        <p style="border-radius: 0" class="col-6 my-0 py-0"><i class="fas fa-user mr-1"></i><?= $tab['capacite']?> personnes</p>
                                        <p style="border-radius: 0" class="col-6 my-0 py-0 mx-0 text-right"><i class="fas fa-server mr-1"></i><?= ucfirst($tab['categorie']) ?></p>
                                    </div>
                                    <div class="col-12 text-center justify-content-center" style="border: #007bff 1px solid; border-radius: 0.25rem" >
                                        <h5 class="my-0 py-2 text-primary"><?= $tab['prix'] . ' €' ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="fiche_produit.php?id_produit=<?= $tab['id_produit']?>" target="_blank" class="btn btn-dark">Détails</a>
                            </div>
                        </div>



                    <!--Modal image -->

                    <div class="modal fade myModal text-center" id="exampleModal10<?= $tab['id_salle'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center">

                                    <h5 class="modal-title h5" id="exampleModalLabel"><?= ucfirst($tab['titre']) ?></h5>
                                </div>
                                <div class="">
                                    <i  data-dismiss="modal" class="far fa-times-circle" style="position:relative; top: -55px; right: -220px;font-size: 30px"></i>
                                </div>
                                <div class="modal-body col-12 h5">
                                    <img class="image-link col-12" src="<?= $tab['photo'] ?>" alt="image salle <?= $tab['titre'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Modal image -->
                <?php endforeach; ?>
            </div>

            <!-- /.row -->
            <div class="row align-items-center justify-content-center px-2">
                <nav class="col-6 justify-content-end" aria-label="...">
                    <ul class="pagination pagination-sm justify-content-center">
                        <?php for($i = 1; $i <= $nombreDePages; $i++): ?>
                            <?php if($i == $pageActuelle): ?>
                                <li class="page-item active"><a href="#" class="page-link url_page"><?=$i?></a></li>
                            <?php else: ?>
                                <li class="page-item <?php if (isset($page) && $page == $i) echo 'active'?>"><a class="page-link url_page" href="#"><?=$i?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul>
                </nav>

            </div>

        </div>
        <!-- /.col-lg-9 -->
    </div>
    <!-- /.row -->

</div>
<!-- /.container -->


<script src="<?= SITE_ROOT ?>includes/js/boutique.js">

</script>

<?php
require_once ('includes/footer.php');
?>


