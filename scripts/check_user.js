let connect = $('.connect-form').offset();
$('#explosion').css({top: connect.top-10, left: connect.left+150});


$('form').on('submit', function (e) {

    e.preventDefault();
    
    $("#explosion").show().delay(1000).queue(function(n) {
        $(this).hide(); n();
    })

    let formData = {
        mail:$("#mail").val(),
        password:$("#password").val(),
        submit:$("#submit").val()
    };

    $.ajax({
        url: './includes/check_user.php',
        type: 'POST',
        data: formData,
    }).done(function (response) {
        if(!parseInt(response)){
            if (response.indexOf("Compte") >= 0){
                reactivate();
                function reactivate(e){
                    // e.preventDefault();
                    let id = response.substring(response.lastIndexOf('?id=')+4);
                    let answer=confirm('Votre compte a été désactivé, souhaitez-vous le ré-activer ?');
                    if(answer){
                        $.ajax({
                            type: "GET",
                            url: "./includes/reactivate_account.php",
                            data: {
                                id: id,
                            },
                            dataType: 'text',
                            }).done(function (response) {
                                alert(response);
                                setTimeout(function () {
                                window.location.href = "./compte.html?id=" + id;
                                }, 500);
                            });
                    }
                    else{
                        alert("Votre compte reste désactivé");
                    }
                };
            }
            else{
                alert(response);
            }
        }
        else{
            setTimeout(function () {
            window.location.href = "./compte.html?id=" + response;
            }, 500);
        }
    });
});