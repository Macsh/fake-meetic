    let url = window.location.href;
    let id = url.substring(url.lastIndexOf('?id=')+4);
    let editButton = $('#edit');

    editButton.on('click', function(){
    $('#edit-div').show()
    $('#intro').hide();
    $('.admin').hide();
    $.ajax({
        type: "GET",
        url: "./includes/get_user.php",
        data: {
            id: id,
        },
        dataType: 'json',
        }).done(function (data) {
            if(data.gender == 'helicopter'){
                gender = "Hélicoptère Apache de Combat";
            }
            else if (data.gender == 'backhoe'){
                gender = "Tractopelle Bi-turbo";
            }
            else {
                gender = "Non-binaire";
            }
            $('#mail').val(data.email);
            $('#firstname').val(data.firstname);
            $('#lastname').val(data.lastname);
            $('#birthdate').val(data.birthdate);
            $('#gender').val(gender);
            $('#city').val(data.city);
    });
});

$('#edit-form').on('submit', function (e) {

    e.preventDefault();
    
    $.ajax({
        url: './includes/edit_user.php',
        type: 'POST',
        data: {
            firstname:$("#firstname").val(),
            lastname:$("#lastname").val(),
            birthdate:$("#birthdate").val(),
            mail:$("#mail").val(),
            city:$("#city").val(),
            oldpassword:$("#oldpassword").val(),
            password:$("#password").val(),
            userId:id,
            submit:$("#submit").val()
        },
    }).done(function (response) {
        alert(response);
        if(response.includes("données")){
            window.location.href = "./compte.html?id="+id;
        }
    });
});