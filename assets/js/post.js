$(document).ready(function () {
    $(".commenterr").hide();
    $("#comment-form").on("submit", function (e) {

        var text = $("#exampleFormControlTextarea1");

        var regex = /^[a-z A-Z 0-9 \s . ? !]{5,200}$/g

        brojGresaka = 0;
        if (!regex.test($(text).val())) {
            brojGresaka++;
            $(text).addClass("is-invalid");
            $(".commenterr").show();
        }
        else {
            $(text).removeClass("is-invalid");
            $(".commenterr").hide();
        }

        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#newpostheading").hide();
    $("#newpostcategory").hide();
    $("#newpostform").on("submit", function (e) {

        var text = $("#heading");
        var country=$("#category_id");
        var regex = /^[a-z A-Z 0-9 \s . ? !]{5,200}$/g


        brojGresaka = 0;
        if (!regex.test($(text).val())) {
            brojGresaka++;
            $(text).addClass("is-invalid");
            $("#newpostheading").show();
        }
        else {
            $(text).removeClass("is-invalid");
            $("#newpostheading").hide();
        }
        if($(country).val()==0){
            brojGresaka++;
            $(country).addClass("is-invalid");
            $("#newpostcategory").show();
        }else{
            $(country).removeClass("is-invalid");
            $("#newpostcategory").hide();
        }

        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#profileName").hide();
    $("#profileSurname").hide();
    $("#profileAddress").hide();
    $("#profileCity").hide();
    $("#profileCountry").hide();
    $("#profileZip").hide();
    $("#profileform").on("submit", function (e) {
        var ime = $('#name');
        var prezime= $('#surname');
        var adresa = $('#address');
        var grad = $('#city');
        var drzava = $('#country');
        var zip = $('#zip');

        brojGresaka=0;

       var regName=/^[A-Z][a-z]{3,}$/g
       var regSurname=/^[A-Z][a-z]{3,}$/g
       var regAdresa=/^[0-9 a-z A-Z \s,]+$/g
       var regCity=/^[A-Z][a-z]{3,}$/g
       var regZip=/^([0-9]{5})$/g

       if(!regName.test($(ime).val())){
           brojGresaka++;
           $(ime).addClass("is-invalid");
           $("#profileName").show();
        }
        else{
           $(ime).removeClass("is-invalid");
           $("#profileName").hide();
        }

        if(!regSurname.test($(prezime).val())){
            brojGresaka++;
            $(prezime).addClass("is-invalid");
            $("#profileSurname").show();
         }
         else{
            $(prezime).removeClass("is-invalid");
            $("#profileSurname").hide();
         }
         if(!regAdresa.test($(adresa).val())){
            brojGresaka++;
            $(adresa).addClass("is-invalid");
            $("#profileAddress").show();
         }
         else{
            $(adresa).removeClass("is-invalid");
            $("#profileAddress").hide();
         }
         if(!regCity.test($(grad).val())){
            brojGresaka++;
            $(grad).addClass("is-invalid");
            $("#profileCity").show();
         }
         else{
            $(grad).removeClass("is-invalid");
            $("#profileCity").hide();
         }
         if(!regZip.test($(zip).val())){
            brojGresaka++;
            $(zip).addClass("is-invalid");
            $("#profileZip").show();
         }
         else{
            $(zip).removeClass("is-invalid");
            $("#profileZip").hide();
         }

        if($(drzava).val()==0){
            brojGresaka++;
            $(drzava).addClass("is-invalid");
            $("#profileCountry").show();
        }
        else{
            $(drzava).removeClass("is-invalid");
            $("#profileCountry").hide();
        }


        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#edituserform").on("submit", function (e) {
        var ime = $('#name');
        var prezime= $('#surname');
        var adresa = $('#address');
        var grad = $('#city');
        var drzava = $('#country');
        var zip = $('#zip');
        
        brojGresaka=0;

       var regName=/^[A-Z][a-z]{3,}$/g
       var regSurname=/^[A-Z][a-z]{3,}$/g
       var regAdresa=/^[0-9 a-z A-Z \s,]+$/g
       var regCity=/^[A-Z][a-z]{3,}$/g
       var regZip=/^([0-9]{5})$/g

       if(!regName.test($(ime).val())){
           brojGresaka++;
           $(ime).addClass("is-invalid");
           $("#profileName").show();
        }
        else{
           $(ime).removeClass("is-invalid");
           $("#profileName").hide();
        }

        if(!regSurname.test($(prezime).val())){
            brojGresaka++;
            $(prezime).addClass("is-invalid");
            $("#profileSurname").show();
         }
         else{
            $(prezime).removeClass("is-invalid");
            $("#profileSurname").hide();
         }
         if(!regAdresa.test($(adresa).val())){
            brojGresaka++;
            $(adresa).addClass("is-invalid");
            $("#profileAddress").show();
         }
         else{
            $(adresa).removeClass("is-invalid");
            $("#profileAddress").hide();
         }
         if(!regCity.test($(grad).val())){
            brojGresaka++;
            $(grad).addClass("is-invalid");
            $("#profileCity").show();
         }
         else{
            $(grad).removeClass("is-invalid");
            $("#profileCity").hide();
         }
         if(!regZip.test($(zip).val())){
            brojGresaka++;
            $(zip).addClass("is-invalid");
            $("#profileZip").show();
         }
         else{
            $(zip).removeClass("is-invalid");
            $("#profileZip").hide();
         }

        if($(drzava).val()==0){
            brojGresaka++;
            $(drzava).addClass("is-invalid");
            $("#profileCountry").show();
        }
        else{
            $(drzava).removeClass("is-invalid");
            $("#profileCountry").hide();
        }


        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#editpostform").on("submit", function (e) {

        var text = $("#heading");
        var country=$("#category_id");
        var regex = /^[a-z A-Z 0-9 \s . ? !]{5,200}$/g


        brojGresaka = 0;
        if (!regex.test($(text).val())) {
            brojGresaka++;
            $(text).addClass("is-invalid");
            $("#newpostheading").show();
        }
        else {
            $(text).removeClass("is-invalid");
            $("#newpostheading").hide();
        }
        if($(country).val()==0){
            brojGresaka++;
            $(country).addClass("is-invalid");
            $("#newpostcategory").show();
        }else{
            $(country).removeClass("is-invalid");
            $("#newpostcategory").hide();
        }

        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#editcategoryerr").hide();
    $("#editcategoryform").on("submit", function (e) {

        var text = $("#categoryname");
        var regex = /^[A-Z][a-z]{3,}$/g


        brojGresaka = 0;
        if (!regex.test($(text).val())) {
            brojGresaka++;
            $(text).addClass("is-invalid");
            $("#editcategoryerr").show();
        }
        else {
            $(text).removeClass("is-invalid");
            $("#editcategoryerr").hide();
        }
        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })
    $("#addcategoryerr").hide();
    $("#addcatform").on("submit", function (e) {

        var text = $("#catname");
        var regex = /^[A-Z][a-z]{3,}$/g


        brojGresaka = 0;
        if (!regex.test($(text).val())) {
            brojGresaka++;
            $(text).addClass("is-invalid");
            $("#addcategoryerr").show();
        }
        else {
            $(text).removeClass("is-invalid");
            $("#addcategoryerr").hide();
        }
        if (brojGresaka != 0) {
            e.preventDefault();
        }
    })


    
});

