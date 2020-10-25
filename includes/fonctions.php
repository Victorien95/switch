<?php

function connection() {
    if(!empty($_SESSION['membre'])) {
        return true;
    }
    return false;
}


function admin() {
    if(connection() && $_SESSION['membre']['statut'] == 2) {
        return true;
    } else {
        return false;
    }
}


function date_convert_fr ($date){
    $date_explode = explode('-', $date);
    $date_fr = '';
    $date_fr .= $date_explode[2] . '-';
    $date_fr .= $date_explode[1] . '-';
    $date_fr .= $date_explode[0];

    return $date_fr;
}


function date_convert_sql($date){
    $date_explode = explode('-', $date);
    $date_sql = '';
    $date_sql .= $date_explode[2] . '-';
    $date_sql .= $date_explode[1] . '-';
    $date_sql .= $date_explode[0];
    print_r($date_sql);
    return $date_sql;
}


function creationPanier()
{
    if (!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

//-----------------------------------------------------------------
function ajoutPanier($titre, $id_produit, $prix, $date_arrivee, $date_depart, $photo)
{
    $errorPanier = '';
    creationPanier();

    $positionProduit = array_search($id_produit,  $_SESSION['panier']['id_produit']);


    if($positionProduit !== false)
    {
        $errorPanier .= '<p class="font-italic text-danger">Le produit a déja été ajouté au panier</p>';

    }
    else
    {
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['date_arrivee'][] = $date_arrivee;
        $_SESSION['panier']['date_depart'][] = $date_depart;
        $_SESSION['panier']['photo'][] = $photo;

    }
}
//---------------------------------------------------------------------------------
function montantTotal()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $total += $_SESSION['panier']['prix'][$i];
    }
    return round($total, 2); // on arrondie le résultat
}

function montantPanier()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $total += 1;
    }
    return $total;
}
//---------------------------------------------------------------------------------


function retirerPanier($idProduitSupp)
{
    $positionProduit = array_search($idProduitSupp, $_SESSION['panier']['id_produit']);
    if ($positionProduit !== false)
    {

        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['id_produit'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
        array_splice($_SESSION['panier']['date_arrivee'], $positionProduit, 1);
        array_splice($_SESSION['panier']['date_depart'], $positionProduit, 1);
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
    }
}

function separateurDate($date){
    $tab = explode('-', $date);
    return $tab;
}

function star($note)
{
    $star = '';
    $moy = $note / 2;
    $tab = explode('.', $moy);
    for($i = 0; $i < $tab[0]; $i++){
        $star .= '<i class="fas fa-star mx-0 px-0 text-warning myStar"></i>';
    }
    if (isset($tab[1])){
        if ($tab[1] >= 2 && $tab[1] <= 8)
        $star .= '<i class="fas fa-star-half-alt mx-0 px-0 text-warning myStar"></i>';
        if($tab[1] < 2){
            $star .= '<i class="far fa-star mx-0 px-0 text-warning myStar"></i>';
        }if ($tab[1] > 8){
            $star .= '<i class="fas fa-star mx-0 px-0 text-warning myStar"></i>';
        }
    }
    if ($tab[0] <= 4){
        if (!isset($tab[1])){
            for ($i = 0; $i < (5 -$tab[0]); $i++){
                $star .= '<i class="far fa-star mx-0 px-0 text-warning myStar"></i>';
            }
        }else{
            for ($i = 1; $i < (5 -$tab[0]); $i++){
                $star .= '<i class="far fa-star mx-0 px-0 text-warning myStar"></i>';
            }
        }
    }
    return $star;
}

?>