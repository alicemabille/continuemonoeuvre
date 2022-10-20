document.getElementById("style_button").innerHTML = "<img src=\"images/light_mode.svg\"/>";

document.getElementById("signin_form").addEventListener('change',function(){
    if((document.getElementById("username_input").value==null)||(document.getElementById("password_input")==null))
    {
        document.getElementById("submit_button").setAttribute("disabled");
    }
});

document.getElementById("signup_form").addEventListener('change',function(){
    if((document.getElementById("username_input").value==null) || (document.getElementById("password_input")==null) 
    || (document.getElementById("email_input").value==null) || (document.getElementById("birthdate_input").value==null))
    {
        document.getElementById("submit_button").setAttribute("disabled");
    }
});

