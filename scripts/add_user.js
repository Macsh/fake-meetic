$('form').on('submit', function (e) {

    e.preventDefault();

    $.ajax({
        url: './includes/add_user.php',
        type: 'POST',
        data: {
            firstname:$("#firstname").val(),
            lastname:$("#lastname").val(),
            birthdate:$("#birthdate").val(),
            mail:$("#mail").val(),
            gender:$("input[name=gender]:checked").val(),
            hobbies:$("input[type=checkbox]:checked").map(function(_, el){
                return $(el).val();
            }).get(),
            city:$("#city").val(),
            password:$("#password").val(),
            passwordconfirm:$("#passwordconfirm").val(),
            submit:$("#submit").val()
        },
    }).done(function (response) {
        alert(response);
        if(response.includes("réussie")){
            window.location.href = "./connexion.html";
        }
    });
});