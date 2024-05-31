<?php

// affichage d'un document PDF
// nécessite le random id (rid) du document en paramètre

// ---------------------------------------------
// chemin racine des fichiers, à changer en cas de déplacement ou renommages
$racine = "/home/batinv/";
// ---------------------------------------------

require_once("init.php");

// extraction du random id du document en url
$rid = $_REQUEST["rid"];

// 
$requete_p = $batinv_db->prepare("SELECT chemin FROM fichiers WHERE random_id = :rid AND type = 'pdf'");
$requete_p->bindValue(":rid", $rid);
$requete_p->execute();
$chemin_relatif = $requete_p->fetch();

if ($chemin_relatif != ""){
    $chemin_complet = $racine.$chemin_relatif[0];
    header('Content-Type: application/pdf');
    readfile($chemin_complet);
}