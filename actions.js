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
            if(/login\.php/.test(window.location.href))
                break;
            if(/profile\.php/.test(window.location.href))
                break;


            regex = /^(?=.*?[0-9]).{8,}$/;
            if(!regex.test(element.value)){
                errorMessage = " must be at least 8 characters and have at least one number";
                valid = false;
            }
        break;


        case "confpassword":
            if(!element.value.length || element.value != document.getElementById("password").value){
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



// https://www.w3schools.com/howto/howto_js_autocomplete.asp
function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
  }
          