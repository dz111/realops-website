jQuery(document).ready(function($) {
  $("#realops-flight-schedule tr").click(function() {
    var url = $(this).data("url");
    if (url) {
      window.document.location = url;
    }
  });
  
  $(".realops-message").delay(2000).slideUp(200);
});
