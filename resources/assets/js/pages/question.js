$(() => {

  $("#formQuestionCreate").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

});
