let ids = [];
let passId;

$(document).ready(function(){
    passId = ids;

    research();
});


$('form').on('submit', function (e) {
    e.preventDefault();
    passId = ids;

    research();
});

$('#next').on('click', function (e) {

    e.preventDefault();
    passId = ids;

    research();
});

$('#before').on('click', function (e) {

    e.preventDefault();

    let beforeId = ids.pop();
    let lastId = ids.pop();
    passId = lastId;

    research();
});


function research(){

$.ajax({
    url: './includes/members.php',
    type: 'POST',
    data: {
        gender:$("#gender option:selected").val(),
        city:$("#city").val(),
        hobbies:$("input[type=checkbox]:checked").map(function(_, el){
            return $(el).val();
        }).get(),
        age:$("#age option:selected").val(),
        id:passId,
        submit:$("#submit").val()
    },
    dataType: 'json',
}).done(function (data) {
    console.log(data);
    let noresult = ['noresult'];
    if(noresult.every(key => Object.keys(data).includes(key))){
        $('#welcome').html("Aucun résultat.")
        $('#display-mail').html("Appuyez sur suivant pour recommencer la recherche");
        $('#display-birthdate').html("");
        $('#display-gender').html("");
        $('#display-city').html("");
        $('#hobbies').html("");
        ids = [];
    }
    else {
        ids.push(data.id);

        if(data.gender == 'helicopter'){
            gender = "Hélicoptère Apache de Combat";
        }
        else if (data.gender == 'backhoe'){
            gender = "Tractopelle Bi-turbo";
        }
        else {
            gender = "Non-binaire";
        }
        $('#welcome').html(data.firstname + " " + data.lastname);
        $('#display-mail').html(data.email);
        $('#display-birthdate').html(data.birthdate);
        $('#display-gender').html("Son genre est : " + gender);
        $('#display-city').html("Sa ville est : " + data.city);
        let hobbies = "";
        $.each(data.hobbies, function(i, item){
            hobbies += '- ' + item.hobby + '<br/>';
        })
        $('#hobbies').html("Sa liste de loisirs : <br/>" + hobbies);
    }
});
}

// function checkId() {
//     let id = Math.floor(Math.random()*8+1);
//     if(ids.length == 0){
//         ids.push(id);
//     }
//     else if($.inArray(id, ids) === -1){
//         ids.push(id);
//     }
//     else if(ids.length == 8){
//         ids = [];
//         checkId();
//     }
//     else{
//         checkId();
//     }
// }