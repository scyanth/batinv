<?php

/*
script destiné à répondre aux requêtes AJAX de l'inventaire : obtenir une photo en grande taille (pour visionneuse)
renvoie le contenu en base64
nécessite le random id (rid) de l'image en paramètre
*/

// ---------------------------------------------
// chemin racine des fichiers, à changer en cas de déplacement ou renommages
$racine = "/home/batinv/";
// ---------------------------------------------

require_once("init.php");

// extraction du random id de l'image en url
$rid = $_REQUEST["rid"];

$requete_p = $batinv_db->prepare("SELECT chemin FROM fichiers WHERE random_id = :rid AND type = 'image'");
$requete_p->bindValue(":rid", $rid);
$requete_p->execute();
$chemin_relatif = $requete_p->fetch();

if ($chemin_relatif != ""){
    $image = encode_image($chemin_relatif[0], $racine);
    echo json_encode($image);
}

// fonction pour encoder les images en base64 pour inclusion directe dans la sortie texte
function encode_image($chemin_relatif, $racine){
    $image = array();
    $chemin_complet = $racine.$chemin_relatif;
    $chemin_blocs = explode("/",$chemin_relatif);
    $nom = end($chemin_blocs);
    $metad = exif_read_data($chemin_complet);
    $fichier_encode = base64_encode(file_get_contents($chemin_complet));
    $image["mimetype"] = $metad["MimeType"];
    $image["data"] = $fichier_encode;
    $image["nom"] = $nom;
    return $image;
}
