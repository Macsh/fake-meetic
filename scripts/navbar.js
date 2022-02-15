$(document).ready(function(){

    let id;

    $.ajax({
        url: './includes/check_nav.php',
    }).done(function (response) {
        if(response != ""){
            id = response;
        }

        if(id != null){
            $('#registerPage').hide();
            $('#connexionPage').hide();
            $('#myPage a').attr("href", "./compte.html?id=" + id);
        }
        else {
            $('#myPage').hide();
            $('#membersPage').hide();
            let currentUrl = window.location.pathname;
            if(currentUrl.indexOf("members") > -1 || currentUrl.indexOf("compte") > -1){
                $('body').hide();
                alert("Veuillez vous connecter pour avoir accès à cette page.");
                window.location.href = "./connexion.html";
            }
        }
    });

    $("#nav-bar a").on('click', function(){
        if($(".dropdown").is(":visible")){
            $(".dropdown").hide();
        }
        else{
            $(".dropdown").show();
        }
    });
    
    $("#opensearch").on('click', function(){
        if($(".dd-search").is(":visible")){
            $(".dd-search").hide();
        }
        else {
            $(".dd-search").show();
        }
    });
});

