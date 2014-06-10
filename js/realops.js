jQuery(document).ready(function($) {
  $("#realops-flight-schedule tr").click(function() {
    window.document.location = $(this).data("url");
  });
});
