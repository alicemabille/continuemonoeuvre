// Disable form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Get the forms we want to add validation styles to
      var forms = $(".needs-validation");
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
          //$("#password-warning").setAttribute("disabled");
        }, false);
      });
    }, false);
  })();


//          TENOR GIFS

$("#featured_gif_button").click(function() {
    $("#gif-list").empty();
    $.get(`https://tenor.googleapis.com/v2/featured?key=${TENOR_API_KEY}&client_key=continue+mon+oeuvre`, function(data, status){
        for(let i=0;i<data.results.length;i+=2){
            $("#gif-list").append(`<div class="preview_gif col-5 m-1">
            <input type="image" src="${data.results[i].media_formats.tinygif.url}" class="gif rounded img-fluid">
            <input type="image" src="${data.results[i+1].media_formats.tinygif.url}" class="gif rounded img-fluid">
            </div>`);
        }
    });
});

$("#search_gif_button").click(function() {
    $("#gif-list").empty();
    var q = $("#search_gif_input").val();
    $.get(`https://tenor.googleapis.com/v2/search?key=${TENOR_API_KEY}&client_key=continue+mon+oeuvre&q=${q}`, function(data, status){
        for(let i=0;i<data.results.length;i+=2){
            $("#gif-list").append(`<div class="preview_gif col-5 m-1">
            <input type="img" src="${data.results[i].media_formats.tinygif.url}" class="rounded img-fluid">
            <input type="img" src="${data.results[i+1].media_formats.tinygif.url}" class="rounded img-fluid">
            </div>`);
        }
    });
});