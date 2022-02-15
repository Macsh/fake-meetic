$(document).ready(function(){
    let url = window.location.href;
    let id = url.substring(url.lastIndexOf('?id=')+4);

    $.ajax({
        type: "GET",
        url: "./includes/get_user.php",
        data: {
            id: id,
        },
        dataType: 'json',
        }).done(function (data) {
        let activeKey = ['active'];
        if(activeKey.every(key => Object.keys(data).includes(key))){
            $('#welcome').html("Cet utilisateur a été désactivé ou n'existe pas.")
        }
        else {
            if(data.gender == 'helicopter'){
                gender = "Hélicoptère Apache de Combat";
            }
            else if (data.gender == 'backhoe'){
                gender = "Tractopelle Bi-turbo";
            }
            else {
                gender = "Non-binaire";
            }
            if (data.firstname == null){
                firstname = ", il faudrait penser à renseigner votre prénom ";
            }
            else {
                firstname = ' ' + data.firstname;
            }
            if (data.lastname == null){
                lastname = "et votre nom.";
            }
            else {
                lastname = ' ' + data.lastname;
            }
            $('#welcome').html("Bienvenue" + firstname + " " + lastname);
            $('#display-mail').html("Votre mail est : " + data.email);
            $('#display-birthdate').html("Vous êtes née le : " + data.birthdate);
            $('#display-gender').html("Votre genre est : " + gender);
            $('#display-city').html("Votre ville est : " + data.city);
            let hobbies = "";
            $.each(data.hobbies, function(i, item){
                hobbies += '- ' + item.hobby + '<br/>';
            })
            $('#hobbies').html("Votre liste de loisirs : <br/>" + hobbies);
            if(data.access == 1){
                $(".admin").show();
            }
        }
    });

    $('#disconnect').on('click', function(){
        document.location = './includes/logout.php';
    });

    $('#deactivate').on('click', function(e){
        e.preventDefault();
        let answer=confirm('Êtes-vous sûr de vouloir désactiver votre compte ?');
        if (answer){
            $.ajax({
                type: "GET",
                url: "./includes/deactivate_account.php",
                data: {
                    id: id,
                },
                dataType: 'text',
                }).done(function (response) {
                    alert(response);
                    window.location.href = "./compte.html?id=" + id;
                });
        }
        else {
            alert("Votre compte a bien été supp- Conservé.")
        }
    });
});