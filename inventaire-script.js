function masque_colonne(colonne){
    document.getElementsByTagName("th")[colonne].style.display = "none";
    for (ligne of document.getElementsByTagName("tr")){
        ligne.children[colonne].style.display = "none";
    }
    document.getElementById("reaffiche_colonnes").style.display = "inline-block";
}

function get_fichiers_equipement(rid){
    document.getElementById("photos_div").innerHTML = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
    document.getElementById("docs_div").innerHTML = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
    let ajaxreq = new XMLHttpRequest();
    ajaxreq.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200){
            if (this.responseText !== ""){
                let fichiers_data = JSON.parse(this.responseText);
                let images = fichiers_data.images;
                let pdfs = fichiers_data.pdf;
                document.getElementById("photos_div").innerHTML = "";
                document.getElementById("docs_div").innerHTML = "";
                if (images != ""){
                    let i = 0;
                    document.getElementById("photos_div").innerHTML = "<table>";
                    for (photo of images){
                        let src = 'data:' + photo.mimetype + ';base64,' + photo.data;
                        if (i % 2 == 0){
                            document.getElementById("photos_div").innerHTML += '<tr><td><img src="' + src + '" style="width:150px;cursor:pointer;" onclick="photoview(&quot;' + photo.rid + '&quot;)"></td>';
                        }else{
                            document.getElementById("photos_div").innerHTML += '<td><img src="' + src + '" style="width:150px;cursor:pointer;" onclick="photoview(&quot;' + photo.rid + '&quot;)"></td></tr>';
                        }
                        i++;
                    }
                    document.getElementById("photos_div").innerHTML += "</table>";
                }
                if (pdfs != ""){
                    for (doc of pdfs){
                        document.getElementById("docs_div").innerHTML += '<a target="blank" href="document.php?rid=' + doc.rid + '">' + doc.nom + '</a><br>';
                    }
                }
            }
        }
    };
    ajaxreq.open("GET","get_fichiers_equipement.php?rid=" + rid, true);
    ajaxreq.send();
}

function photoview(rid){
    document.getElementById("viewer_image").src = "";
    document.getElementById("viewer_text").innerHTML = "";
    document.getElementById("viewer_image").innerHTML = '<div class="lds-ring" id="lds-ring_big"><div></div><div></div><div></div><div></div></div>';
    let ajaxreq = new XMLHttpRequest();
    ajaxreq.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200){
            if (this.responseText !== ""){
                let photo = JSON.parse(this.responseText);
                let src = 'data:' + photo.mimetype + ';base64,' + photo.data;
                document.getElementById("viewer_image").src = src;
                document.getElementById("viewer_image").parentElement.href = src;
                document.getElementById("viewer_image").innerHTML = "";
                document.getElementById("viewer_text").innerHTML = photo.nom;
            }
        }
    };
    ajaxreq.open("GET","get_image.php?rid=" + rid, true);
    ajaxreq.send();
    document.getElementById("photos_viewer").style.display = "block";
}

document.getElementById("btn_ferme_barre_droite").addEventListener("click", function() {
    document.getElementById("barre_droite").style.width = "0";
    document.getElementById("btn_ferme_barre_droite").style = "display:none";
});

document.getElementById("reaffiche_colonnes").addEventListener("click", function() {
    for (entete of document.getElementsByTagName("th")){
        entete.style.display = "table-cell";
    }
    for (ligne of document.getElementsByTagName("tr")){
        for (cellule of ligne.children){
            cellule.style.display = "table-cell";
        }
    }
});

document.getElementById("btn_ferme_viewer").addEventListener("click", function() {
    document.getElementById("photos_viewer").style = "display:none";
});

for (btn of document.getElementsByClassName("btn_masque_colonne")){
    btn.addEventListener("click", masque_colonne.bind(null, btn.getAttribute("data-col")));
}

for (ligne of document.getElementsByClassName("ligne_equipement")){
    ligne.addEventListener("click", function() {
        document.getElementById("barre_droite").style.width = "300px";
        document.getElementById("btn_ferme_barre_droite").style = "display:block";
    });
    ligne.addEventListener("click", get_fichiers_equipement.bind(null, ligne.getAttribute("data-rid")));
}