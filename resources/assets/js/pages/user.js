$(() => {

  $("#formUpdateTimezone").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formUpdateEmail").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formUpdateNotificationsAction").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

});
