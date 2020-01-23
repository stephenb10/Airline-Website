function validateForm(form){
    var formValid = true;

    for (i = 0; i < form.length; i++) {
        element = form[i]
        if(!isElementValid(element)){
            formValid = false;
        }

       

    }


    return formValid;


}


function isElementValid(element){
    var valid = true;
    var errorMessage = "";

    if(!element.value.length){
        errorMessage = " is required";
        valid = false;
    }

    if(valid)
    switch (element.id){
        case "fname":
        case "lname":
            regex = /^[A-Z]/;
            if(!regex.test(element.value)){
                errorMessage = " requires a capital letter";
                valid = false;
            }
        break;

        case "suburb":
            regex = /^[a-zA-z]{2,}$/;
            if(!regex.test(element.value)){
                errorMessage = " is invalid";
                valid = false;
            }
        break;

        case "phone":
            regex = /^[0-9]{10}$/;
            if(!regex.test(element.value)){
                errorMessage = " is invalid";
                valid = false;
            }
        break;

        case "address":
            if(!element.value.length){
                errorMessage = " is invalid";
                valid = false;
            }
        break;

        case "email":
            regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!regex.test(element.value)){
                errorMessage = " is invalid";
                valid = false;
            }
        break;

        case "state":
            if(!element.value.length){
                errorMessage = " is invalid";
                valid = false;
            }
        break;

        case "postcode":
            regex = /^\d{4}$/;
            if(!regex.test(element.value)){
                errorMessage = " is invalid";
                valid = false;
            }
        break;


        case "password":
            regex = /^(?=.*?[0-9]).{8,}$/;
            if(!regex.test(element.value)){
                errorMessage = " must be at least 8 characters and have at least one number";
                valid = false;
            }
        break;


        case "confpassword":
            if(!element.value.length || element.value != form.password){
                    valid = false;
                    errorMessage = " does not match";
            }

        break;

        default:
            return true;
        break;


    }

    
    if(!valid)
    {
        showError(element.id, errorMessage);
    }
    else{
        hideError(element.id, errorMessage);
    }

    return valid;
}


function showError(id, msg){
    var input = document.getElementById(id);
    input.className = "invalid";
    input.nextElementSibling.style.display = "inline";
    input.nextElementSibling.nextElementSibling.style.display = "inline";
    input.nextElementSibling.nextElementSibling.innerText = msg;
    input.previousElementSibling.getElementsByTagName('img')[0].style.display = "inline";
    
}

function hideError(id, msg){
    var input = document.getElementById(id);
    input.className = "";
    input.nextSibling.nextSibling.style.display = "none";
    input.nextElementSibling.nextElementSibling.style.display = "none";
    input.previousElementSibling.getElementsByTagName('img')[0].style.display = "none";
}