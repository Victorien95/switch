<?php
require_once ('includes/init.php');

if(isset($_POST['ajout_panier']))
{
    $data = $pdo->prepare("SELECT * FROM produit as p LEFT JOIN salle as s ON p.id_produit = :id_produit");
    $data->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
    $data->execute();

    $product = $data->fetch(PDO::FETCH_ASSOC);


    ajoutPanier($product['titre'], $product['id_produit'], $product['prix'], $product['date_arrivee'], $product['date_depart'], $product['photo']);

}


if(isset($_GET['supprimer']) && $_GET['supprimer'] == 'true' && isset($_GET['id_produit']))
{


    if($position = $_GET['id_produit'])
    {
        $pos = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);
        if ($pos !== false){
            $suppression = '<p class="px-3 mx-auto bg-danger text-white text-center rounded">Le produit : <strong>' . $_SESSION['panier']['titre'][$pos] .
                '</strong> a été supprimé</p>';

            retirerPanier($position);
        }


    }
}





if (isset($_POST['payer']))
{
    if(!isset($error))
    {

        for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
        {
            $pdo->query("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (" . $_SESSION['membre']['id_membre'] . "," . $_SESSION['panier']['id_produit'][$i] . ", NOW())"); // pour chaque produit, on execute une insertion dans la table details_commande associé au bon id_commande
        }
        unset($_SESSION['panier']);
        $validPanier = "<p class='col-md-6 mx-auto bg-success text-white text-center rounded p-2'> Votre commande a bien été validé !!</p>";
    }
}

require_once ('includes/header.php');
?>
<div class="container-fluid vh-100 align-items-center d-flex flex-column justify-content-center">
    <h1 class='display-4 text-center'>Panier</h1>





    <table class="col-md-8 table table-responsive-sm ta table-bordered text-center">
        <?php if (isset($errorPanier)) echo $errorPanier?>
        <?php if (isset($validPanier)) echo $validPanier?>
        <?php if (isset($suppression)) echo $suppression?>
        <?php if (isset($errorPanier)) echo $errorPanier?>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Titre du produit</th>
            <th>Prix</th>
            <th>Date d'arrivée</th>
            <th>Date de départ</th>
            <th>Supprimer</th>
        </tr>
        <?php if(empty($_SESSION['panier']['id_produit'])): ?>
            <tr>
                <td colspan="6"><p class="bg-dark rounded text-center text-white p-2">Votre panier est vide</p></td>
            </tr>
        <?php else:?>
            <?php for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++): ?>
                <tr>
                    <td><?= $_SESSION['panier']['id_produit'][$i] ?></td>
                    <td><img src="<?= $_SESSION['panier']['photo'][$i]?>" style="width: 100px" alt=""></td>
                    <td><?= $_SESSION['panier']['titre'][$i] ?></td>
                    <td><?= $_SESSION['panier']['prix'][$i] ?></td>
                    <td><?= date_convert_fr($_SESSION['panier']['date_arrivee'][$i]) ?></td>
                    <td><?= date_convert_fr($_SESSION['panier']['date_depart'][$i]) ?></td>
                    <td><a href="?supprimer=true&id_produit=<?= $_SESSION['panier']['id_produit'][$i]?>" class="btn btn-danger">Supprimer</a></td>
                </tr>
            <?php endfor; ?>
            <tr>
                <th>Montant Total</th>
                <td></td><td></td><td></td>
                <td><?= montantTotal() ?> €</td>
                <td></td>
            </tr>
            <?php if(connection()): ?>
                <form action="" method="post">
                    <tr>
                        <td colspan="6">
                            <input type="submit" name="payer" value="Valider le paiement" class="btn btn-success">
                        </td>
                    </tr>
                </form>
            <?php else: ?>
                <tr>
                    <td colspan="6"><p class="bg-success text-white text-center rounded p-2">Veuillez vous
                            <a href="connexion.php" class="text-white alert-link">Connecter</a> ou vous
                            <a href="inscription.php" class="text-white alert-link">inscrire</a> afin de valider le paiement</p>
                    </td>
                </tr>
            <?php endif; ?>


        <?php endif;?>
    </table>
</div>

<?php
require_once ('includes/footer.php');
?>
