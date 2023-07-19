$('#register-form').submit(function(event) {
    event.preventDefault();

    var formData = $('form').serialize();

    $.ajax({
        type: 'POST',
        url: './register.php',
        data: formData,
        success:function(response){
            const json_response=JSON.parse(response);
            $('.validate').text("");
            
            if(json_response.success==true)
                $('.dbValidation').text("Account created successfully! You can proceed to login.");


            else{
                // console.log("Performing validations")

                const validObject=json_response["validations"]
                for(const key in validObject)
                    $(`.${key}`).text(validObject[key]);


            }
         
        
        }
    })


})


$('#login-form').submit(function(event) {
    event.preventDefault();
    var formData = $('form').serialize();

    $.ajax({
        type: 'POST',
        url: './login.php',
        data: formData,
        success:function(response){    
            console.log(response)
            // const json_response=JSON.parse(response);
            // console.log(json_response)

            $('.validate').text("");
        
            if(json_response.success==false){
                $('.login-err').text(json_response(json_response["login-err"]))
                
            }
                
        
        }
    })


})

