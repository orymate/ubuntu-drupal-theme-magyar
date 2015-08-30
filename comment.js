$(document).ready(function () {
    if (document.location.hash.length > 1) {
      $(document.location.hash + ".comment").addClass('comment-selected');
    }
    $("span.new").click(function () {
      document.location.hash=$("#comment-new-"+(($(this).attr('id').substring(12))*1+1)).parents(".comment").attr('id');
      $('.comment-selected').removeClass('comment-selected');
      $(document.location.hash).addClass('comment-selected');
      });
    $(".comment a.permalink, .comment-parent-link").click(function () {
      $('.comment-selected').removeClass('comment-selected');
      $("#" + $(this).attr('href').split("#")[1]).addClass('comment-selected');
      });
    $(".cse-q").placeholder();
});

