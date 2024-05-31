<?php

// -------------------------------------------------------------------------------------------------------------
// initialisation
// -------------------------------------------------------------------------------------------------------------

require_once("init.php");

// -------------------------------------------------------------------------------------------------------------
// fetch de la BDD
// -------------------------------------------------------------------------------------------------------------

// tous les equipements
$requete = "SELECT * FROM equipements";
$reponse = $batinv_db->query($requete);
$equipements = $reponse->fetchAll();

// toutes les salles
$requete = "SELECT * FROM salles";
$reponse = $batinv_db->query($requete);
$salles = $reponse->fetchAll();

// tous les étages
$requete = "SELECT * FROM etages";
$reponse = $batinv_db->query($requete);
$etages = $reponse->fetchAll();

// -------------------------------------------------------------------------------------------------------------
// affichage
// -------------------------------------------------------------------------------------------------------------

?>
<!doctype html>
<html lang="fr">
<head>
<title>Inventaire du batiment</title>
<meta charset="utf-8">
<link rel="stylesheet" href="global-style.css">
<link rel="stylesheet" href="inventaire-style.css">
<link rel="stylesheet" href="barre_menus-style.css">
<link rel="stylesheet" href="loading.css">
<script src="inventaire-script.js" defer></script>
<script src="barre_menus-script.js" defer></script>
</head>
<body>

<?php
// barre de menus
include("barre_menus.php");
?>

<div id="barre_droite">
<span id="btn_ferme_barre_droite">&times;</span>
<div id="contenu_barre_droite">
<span>Photos</span>
<div id="photos_div"></div>
<span>Documents</span>
<div id="docs_div"></div>
</div>
</div>

<div id="photos_viewer">
<span id="btn_ferme_viewer">&times;</span>
<a id="lien_viewer" target="blank"><img id="viewer_image" alt="test"></img></a>
<div id="viewer_text">Texte</div>
</div>

<div class="main">

<div id="barre_haut">
<button id="reaffiche_colonnes" style="display:none">Réafficher les colonnes</button>
</div>

<?php

// entêtes présentables du tableau (clé = nom de champ sur la table equipements, sauf pour les champs hors de cette table)
$entetes = array("Porte", "Salle", "codeloc"=>"Codeloc", "code_mainta"=>"Code Mainta", "post"=>"Post", "designation"=>"Désignation fourniture",
"modele"=>"Type / Modèle / Référence / Dimensions", "marque"=>"Fournisseur / Marque", "date_achat"=>"Date d'achat et / ou d'arrivée", "num_commande"=>"N° Commande",
"n_eng"=>"N° ENG", "montant"=>"Montant", "imput_budget"=>"Imput budget", "n_inv_iuup"=>"N° Inventaire iu / up", "num_immo"=>"N° Immobilisation",
"n_inv_gmp"=>"N° Inventaire iu/iu/GMP", "n_inv_general"=>"N° Inventaire général iu/iu/GMP", "beneficiaire"=>"Béneficiaire",	"domaine"=>"Domaine", "num_serie"=>"N° de serie",
"annee_fab"=>"Année de fabrication", "quantite"=>"Quantité", "provenance"=>"Provenance", "capac_salles"=>"Capacité /salles", "n_tri_gmp"=>"N° pour le tri iu/iu/GMP",
"lettre_tri_gmp"=>"Lettre pour le tri iu/iu/GMP", "situation"=>"Situation constatée = Correct ou Pas correct");

print "<table>";

// création des entêtes (avec bouton de masquage)
$c = 0;
foreach ($entetes as $entete){
    print '<th><button class="btn_masque_colonne" data-col="'.$c.'">&times;</button><br/>'.$entete.'</th>';
    $c++;
}

// contenu du tableau : ligne par équipement
foreach ($equipements as $equipement){

    print '<tr class="ligne_equipement" data-rid="'.$equipement["random_id"].'">';

    // récupération des données utiles de la table salles
    foreach ($salles as $salle){
        if ($salle["id"] == $equipement["salle"]){
            $nom_salle = $salle["nom"];
            $porte = $salle["porte_principale"];
            $ref_salle = $salle["ref_portes_salle"];
        }
    }

    foreach ($entetes as $champ=>$entete){
        // numero de porte
        if ($champ == 0){
            print '<td>'.$porte."</td>";
        }
        // numero de salle + autres infos salle & présence en infobulle
        elseif ($champ == 1){
            if ($equipement["presence_company"] == 1){
                $presence = "&#9745;";
            }else{
                $presence = "&#9746;";
            }
            print '<td>'.$nom_salle.'<div class="infobulle">'.$ref_salle.'<br/> Présent : '.$presence.'</div></td>';
        }
        // commentaire en infobulle (si non vide) pour champ designation
        elseif ($champ == "designation"){
            if ($equipement["commentaire"] !== ""){
                $commentaire = nl2br($equipement["commentaire"]);
                print '<td>'.$equipement[$champ].'<div class="infobulle">'.$commentaire.'</div></td>';
            }else{
                print '<td>'.$equipement[$champ].'</td>';
            }
        }
        // autres champs
        else{
            print "<td>".$equipement[$champ]."</td>";
        }
    }

    print "</tr>";
}


print "</table>";

print '</div>';

?>

</body>
</html>