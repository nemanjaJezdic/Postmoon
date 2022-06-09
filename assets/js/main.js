$(document).ready(function(){
    $(".valcheck").hide();
    $(".valradio").hide();
    $("#registerName").hide();
    $("#registerSurname").hide();
    $("#registerEmail").hide();
    $("#registerPassword").hide();
    $("#registerAddress").hide();
    $("#registerCity").hide();
    $("#registerCountry").hide();
    $("#registerZip").hide();
    $("#btnRegister").on('click',e => {
        e.preventDefault();
        let ime,prezime, email, lozinka, adresa,grad,drzava,zip,pol,check,brojGresaka;
        ime = $('#inputName');
        prezime= $('#inputSurname');
        email = $('#inputEmail4');
        lozinka = $('#inputPassword4');
        adresa = $('#inputAddress');
        grad = $('#inputCity');
        drzava = $('#inputCountry');
        zip = $('#inputZip');
        pol = $('input[name=customRadio]:checked');
        check = $('input[name=customCheck]:checked');
       
        brojGresaka = 0;

       var regName=/^[A-Z][a-z]{3,}$/g
       var regSurname=/^[A-Z][a-z]{3,}$/g
       var regEmail=/^[a-z]((\.|-)?[a-z0-9]){2,}@[a-z]((\.|-)?[a-z0-9]+){2,}\.[a-z]{2,6}$/
       var regPassword=/^[a-z A-Z 0-9]{2,}$/g
       var regAdresa=/^[0-9 a-z A-Z \s,]+$/g
       var regCity=/^[A-Z][a-z]{3,}$/g
       var regZip=/^([0-9]{5})$/g

       if(!regName.test($(ime).val())){
           brojGresaka++;
           $(ime).addClass("is-invalid");
           $("#registerName").show();
        }
        else{
           $(ime).removeClass("is-invalid");
           $("#registerName").hide();
        }

        if(!regSurname.test($(prezime).val())){
            brojGresaka++;
            $(prezime).addClass("is-invalid");
            $("#registerSurname").show();
         }
         else{
            $(prezime).removeClass("is-invalid");
            $("#registerSurname").hide();
         }

         if(!regEmail.test($(email).val())){
            brojGresaka++;
            $(email).addClass("is-invalid");
            $("#registerEmail").show();
         }
         else{
            $(email).removeClass("is-invalid");
            $("#registerEmail").hide();
         }

         if(!regPassword.test($(lozinka).val())){
            brojGresaka++;
            $(lozinka).addClass("is-invalid");
            $("#registerPassword").show();
         }
         else{
            $(lozinka).removeClass("is-invalid");
            $("#registerPassword").hide();
         }
         if(!regAdresa.test($(adresa).val())){
            brojGresaka++;
            $(adresa).addClass("is-invalid");
            $("#registerAddress").show();
         }
         else{
            $(adresa).removeClass("is-invalid");
            $("#registerAddress").hide();
         }
         if(!regCity.test($(grad).val())){
            brojGresaka++;
            $(grad).addClass("is-invalid");
            $("#registerCity").show();
         }
         else{
            $(grad).removeClass("is-invalid");
            $("#registerCity").hide();
         }
         if(!regZip.test($(zip).val())){
            brojGresaka++;
            $(zip).addClass("is-invalid");
            $("#registerZip").show();
         }
         else{
            $(zip).removeClass("is-invalid");
            $("#registerZip").hide();
         }

        if($(drzava).val()==0){
            brojGresaka++;
            $(drzava).addClass("is-invalid");
            $("#registerCountry").show();
        }
        else{
            $(drzava).removeClass("is-invalid");
            $("#registerCountry").hide();
        }
       
        if($(pol).length==0){
            brojGresaka++;
            $(".valradio").show();
        }
        else{
            $(".valradio").hide();
        }
        if($(check).length==0){
            brojGresaka++;
            $(".valcheck").show();
        }
        else{
            $(".valcheck").hide();
        }



        if(brojGresaka == 0){
            let podaci= {
                imep : $(ime).val(),
                prezimep :  $(prezime).val(),
                emailp :  $(email).val(),
                lozinkap: $(lozinka).val(),
                adresap :  $(adresa).val(),
                gradp : $(grad).val(),
                drzavap : $(drzava).val(),
                zipp :  $(zip).val(),
                polp : $(pol).val(),
                checkp:  $(check).val()
            }


            ajaxCallBack("/assets/models/registration.php", "post", podaci, function(result){
                $('#odgovor').html(`<p class="alert alert-success my-3">${result.message}</p>`);
            },function(err){
                $("#odgovor").html(`<p class="alert alert-danger my-3">${err.responseJSON.message}</p>`)
            });
           
        }

    })



    $("#btnLogin").on('click',e=>{
        e.preventDefault();
        let email, lozinka, brojGresaka;
        email = $('#logemail');
        lozinka = $('#logpass');
        brojGresaka = 0;

        var regEmail=/^[a-z]((\.|-)?[a-z0-9]){2,}@[a-z]((\.|-)?[a-z0-9]+){2,}\.[a-z]{2,6}$/
        var regPassword=/^[a-z A-Z 0-9]{2,}$/g

        if(!regEmail.test($(email).val())){
            brojGresaka++;
            $(email).addClass("is-invalid");
         }
         else{
            $(email).removeClass("is-invalid");
         }

         if(!regPassword.test($(lozinka).val())){
            brojGresaka++;
            $(lozinka).addClass("is-invalid");
         }
         else{
            $(lozinka).removeClass("is-invalid");
         }


        if(brojGresaka == 0){
            let podaci = {
                email: $(email).val(),
                lozinka: $(lozinka).val()
            }

            ajaxCallBack("/assets/models/logovanje.php", "post", podaci,function(result){
                window.location.replace("/posts.php");            
            },function(err){
                alert(err.responseJSON.message);
            });
        }
    })

    $(".like-button").on("click",function(){
             var podaci={
                 id:$(this).attr("data-post-id")
             };
             var _this=$(this);
             //arrow function neutralise $(this)
            if($(this).hasClass("fas fa-arrow-up")){
                ajaxCallBack("/assets/models/like.php","post",podaci,function(result){
                    $(`#like_${podaci.id}`).html("Likes:" + result.data);
                    $(_this).removeClass("fas fa-arrow-up").addClass("fas fa-arrow-down");
                },function(err){
                    alert(err.responseJSON.message);
                });
            }
            if($(this).hasClass("fas fa-arrow-down")){
                ajaxCallBack("/assets/models/unlike.php","post",podaci,function(result){
                    $(`#like_${podaci.id}`).html("Likes:" + result.data);
                    $(_this).removeClass("fas fa-arrow-down").addClass("fas fa-arrow-up");
                },function(err){
                    alert(err.responseJSON.message);
                });
            }
           

    })

})

function ajaxCallBack(url, method, data, result,err){
    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: "json",
        success: result,
        error: err
    });
}