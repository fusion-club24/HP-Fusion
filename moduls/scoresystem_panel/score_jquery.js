
// Admin Score Slide Show
$(document).ready(function() {
  $(".score_body").hide();
  $(".score_master_head").click(function() {
    $(this).next(".score_master_body").slideToggle(1000);
  });
  $(".score_head").click(function() {
    $(this).next(".score_body").slideToggle(1000);
  });
});