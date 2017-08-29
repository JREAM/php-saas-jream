import axios from "../components/interceptors";

$(() => {

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

});
