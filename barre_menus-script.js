document.getElementById("btn_barre_menus").addEventListener("click", function () {
    if (document.getElementById("barre_menus").style.width == "40px"){
        document.getElementById("barre_menus").style.width = "180px";
        document.getElementsByClassName("main")[0].style.marginLeft = "170px";
        document.getElementById("btn_barre_menus").innerHTML = "&#8592;";
        document.getElementById("btn_barre_menus").style.paddingLeft = "50px";
        document.getElementById("liste_menus").style = "display:block";
    }else{
        document.getElementById("barre_menus").style.width = "40px";
        document.getElementsByClassName("main")[0].style.marginLeft = "30px";
        document.getElementById("btn_barre_menus").innerHTML = "&#8594;";
        document.getElementById("btn_barre_menus").style.paddingLeft = "3px";
        document.getElementById("liste_menus").style = "display:none";
    }
});