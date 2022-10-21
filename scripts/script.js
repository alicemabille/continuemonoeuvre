

/* document.getElementById("style_button").innerHTML = "<img src=\"images/light_mode.svg\"/>";

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
}); */



/* Requêtes AJAX vers l'API giphy (format JSON)
const TRENDING_GIFS_URL = "https://api.giphy.com/v1/gifs/trending?api_key=gEFNlsVdIT1cLToq9t4m6k07fjmyNMYe&limit=25&rating=g";

var xhr = new XMLHttpRequest();

function makeRequest(url) {
    xhr.onreadystatechange = function () {
        // Process the server response here.
        if (this.readyState == 4 && this.status == 200) { // Everything is good, the response was received.
            const response = JSON.parse(this.responseText);
        }
    }
    
    xhr.open('POST', url);
    xhr.send();
}

$("#giphy_button").click(function() {
    makeRequest(TRENDING_GIFS_URL);
    var i;
    var data = response.data;
    for(i=0;i<sizeof(data);i++){
        $("#gif_list").append(`<li class=\"gif\"><img src=\"${data[i].images.downsized.url}\"/></li>`);
    }
});

 */



//          TENOR GIFS
const TENOR_API_KEY = "AIzaSyBQ9hgLmHHcNUitoXBEnneAYR3HehZLILE";


// url Async requesting function
function httpGetAsync(theUrl, callback)
{
    // create the request object
    var xmlHttp = new XMLHttpRequest();

    // set the state change callback to capture when the response comes in
    xmlHttp.onreadystatechange = function()
    {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
        {
            callback(xmlHttp.responseText);
        }
    }

    // open as a GET call, pass in the url and set async = True
    xmlHttp.open("GET", theUrl, true);

    // call send with no params as they were passed in on the url string
    xmlHttp.send(null);

    return;
}

// callback for the top 8 GIFs of search
function tenorCallback_search(responsetext)
{
    // Parse the JSON response
    var response_objects = JSON.parse(responsetext);

    top_10_gifs = response_objects["results"];
    var i;
    for(i=0;i<sizeof(data);i++){
        $("#gif_list").append(`<li class=\"preview_gif\"><img src="${top_10_gifs[i]["media_formats"]["nanogif"]["url"]}" style="width:220px;height:164px;" /></li>`);
    }

    return;
}


// function to call the trending and category endpoints
function grab_data()
{
    // set the apikey and limit
    var apikey = "API_KEY";
    var clientkey = "my_test_app";
    var lmt = 8;

    var trend_url = `https://g.tenor.com/v1/trending?key=${TENOR_API_KEY}&locale=fr&media_filter=minimal&content_filter=high`;

    httpGetAsync(trend_url,tenorCallback_search);

    // data will be loaded by each call's callback
    return;
}


$("#gif_button").click(function() {
    // start the flow
    grab_data();
});

$("#gif_button").hover(function() {
    $("#gif_list").show();
});
