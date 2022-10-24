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



//          TENOR GIFS

$("#featured_gif_button").click(function() {
    $("#gif_list").empty();
    $.get(`https://tenor.googleapis.com/v2/featured?key=${TENOR_API_KEY}&client_key=continue+mon+oeuvre`, function(data, status){
        for(let i=0;i<data.results.length/3;i++){
            $("#gif_list_1").append(`<div class="preview_gif col-6 m-1 "><img src="${data.results[i].media_formats.tinygif.url}" class="rounded img-fluid" /></div>`);
        }
        for(let i=data.results.length/3;i<data.results.length*2/3;i++){
            $("#gif_list_2").append(`<div class="preview_gif col-6 m-1 "><img src="${data.results[i].media_formats.tinygif.url}" class="rounded img-fluid" /></div>`);
        }
        for(let i=data.results.length*2/3;i<data.results.length;i++){
            $("#gif_list_3").append(`<div class="preview_gif col-6 m-1 "><img src="${data.results[i].media_formats.tinygif.url}" class="rounded img-fluid" /></div>`);
        }
    });
});

$("#search_gif_button").click(function() {
    $("#gif_list").empty();
    var q = $("#search_gif_input").val();
    console.log(q);
    $.get(`https://tenor.googleapis.com/v2/search?key=${TENOR_API_KEY}&client_key=continue+mon+oeuvre&q=${q}`, function(data, status){
        for(let i=0;i<data.results.length;i++){
            $("#gif_list").append(`<div class=\"preview_gif col m-1\"><img src="${data.results[i].media_formats.tinygif.url}" class="rounded img-fluid" /></div>`);
        }
    });
});