jQuery(document).ready(function($) {
  $("#realops-flight-schedule tr").click(function() {
    window.document.location = $(this).data("url");
  });
  
  $(".realops-message").delay(2000).slideUp(200);
});
