$(() => {

  $("#formLogin").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    $(this).post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    $(this).post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });


  $("#formPasswordResetConfirm").submit(function(evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    $(this).post(url, postData, function (resp) {
      console.log(resp);
    }, "json");

  });


  $("#logout").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $(this).get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

});
