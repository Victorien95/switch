<?php
require_once '../includes/init.php';



if(!admin())
{
    header('Location: http://switch/connexion.php' );
}
$top = '';


if (isset($_GET['top'])){
    if ($_GET['top'] == '1'){
        $data = $pdo->query("SELECT *, AVG(note) as AVG FROM avis as a LEFT JOIN salle as s ON s.id_salle = a.id_salle  GROUP BY a.id_salle ORDER BY AVG(a.note) DESC LIMIT 5");
        $top = $data->fetchAll();
    }
    elseif ($_GET['top'] == '2'){
        $data = $pdo->query("SELECT *, COUNT(c.id_produit) as COUNT_SALLES FROM commande as c LEFT JOIN produit as p ON c.id_produit = p.id_produit LEFT JOIN salle as s ON p.id_salle = s.id_salle GROUP BY c.id_produit ORDER BY COUNT(c.id_produit) DESC LIMIT 5");
        $top = $data->fetchAll();
    }
    elseif ($_GET['top'] == '3'){
        $data = $pdo->query("SELECT *, COUNT(c.id_membre) as COUNT_MEMBRES FROM commande  as c LEFT JOIN membre as m ON c.id_membre = m.id_membre GROUP BY c.id_membre ORDER BY COUNT(c.id_membre) DESC LIMIT 5");
        $top = $data->fetchAll();
    }
    elseif ($_GET['top'] == '4'){
        $data = $pdo->query("SELECT *, SUM(p.prix) as SUM_TOTAL FROM commande as c LEFT JOIN produit as p ON c.id_produit = p.id_produit LEFT JOIN membre as m ON m.id_membre = c.id_membre GROUP BY c.id_membre ORDER BY SUM(p.prix) DESC LIMIT 5");
        $top = $data->fetchAll();
    }else{
        header('location:statistiques.php');
    }
}



require_once  '../includes/header.php';
?>



<!-- Page Content -->
<div class="container-fluid mb-5">
    <h1 class="pb-5 display-5 text-center my-5">STATISTIQUES</h1>

    <div class="row">

        <div class="col-lg-3">
            <?php if (isset($errorMessage)){echo $errorMessage;} ?>
            <div class="list-group my-5 text-center">
                <a href='?top=1' class='list-group-item'><h5>Les salles les mieux notés</h5></a>
                <a href='?top=2' class='list-group-item'><h5>Les salles les plus commandées</h5></a>
                <a href='?top=3' class='list-group-item'><h5>Les membres qui achètent le plus (quantités)</h5></a>
                <a href='?top=4' class='list-group-item'><h5>Les membres qui achètent le plus chère (prix)</h5></a>
            </div>
        </div>


        <div class="card-body col-lg-9">
            <div class="row text-center justify-content-center">
                <?php if (isset($_GET['top']) && $_GET['top'] == '1'): ?>
                <ul class="list-group col-9">
                <?php for($i = 0; $i < count($top); $i++):?>
                        <li class="list-group-item">
                            <p ><h3 style="color: goldenrod"><i class="fas fa-star text-warning"><?=$i +1 ?></i></h3></p>
                            <h4><span class="badge badge-warning badge-pill"><?=$top[$i]['titre']?></span></h4>
                            <h5><span class="badge badge-primary badge-pill">Moyenne : <?= round($top[$i]['AVG'], 0) ?></span></h5>
                        </li>
                    <?php endfor; ?>
                </ul>



                    <!-- --------------------------------------------------------- -->


                <?php elseif (isset($_GET['top']) && $_GET['top'] == '2'): ?>
                <ul class="list-group col-9">
                <?php for($i = 0; $i < count($top); $i++):?>
                    <li class="list-group-item">
                        <p ><h3 style="color: goldenrod"><i class="fas fa-star text-warning"><?=$i +1 ?></i></h3></p>
                        <h4><span class="badge badge-warning badge-pill"><?=$top[$i]['titre']?></span></h4>
                        <h5><span class="badge badge-primary badge-pill">Commandé : <?= round($top[$i]['COUNT_SALLES'], 0) ?> fois</span></h5>
                    </li>


                    <?php endfor; ?>
                </ul>
                    <!-- --------------------------------------------------------- -->

                <?php elseif (isset($_GET['top']) && $_GET['top'] == '3'): ?>
                <ul class="list-group col-9">

                <?php for($i = 0; $i < count($top); $i++):?>
                    <li class="list-group-item">
                        <p ><h3 style="color: goldenrod"><i class="fas fa-star text-warning"><?=$i +1 ?></i></h3></p>
                        <h4><span class="badge badge-warning badge-pill"><?=$top[$i]['pseudo']. ' - ' . $top[$i]['email']?></span></h4>
                        <h5><span class="badge badge-primary badge-pill">Total commandes effectuées : <?= round($top[$i]['COUNT_MEMBRES'], 0) ?></span></h5>
                    </li>
                    <?php endfor; ?>
                </ul>

                    <!-- --------------------------------------------------------- -->

                <?php elseif (isset($_GET['top']) && $_GET['top'] == '4'): ?>
                <ul class="list-group col-9">
                <?php for($i = 0; $i < count($top); $i++):?>
                    <li class="list-group-item">
                        <p ><h3><i class="fas fa-star text-warning"><?=$i +1 ?></i></h3></p>

                        <h4><span class="badge badge-warning badge-pill"><?=$top[$i]['pseudo']. ' - ' . $top[$i]['email']?></span></h4>
                        <h5><span class="badge badge-primary badge-pill">Total du montant des commandes : <?= round($top[$i]['SUM_TOTAL'], 0) . ' €'?></span></h5>
                    </li>
                    <?php endfor; ?>
                </ul>

                <?php else: ?>
                    <ul class="list-group col-9">
                            <li class="list-group-item">
                                <p><h5>Choisissez un affichage</h5></p>
                            </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.col-lg-3 -->
        <!-- /.col-lg-9 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container -->





<?php
require_once ('../includes/footer.php');
?>




