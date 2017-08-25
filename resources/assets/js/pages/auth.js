

$(() => {


  $("#formLogin").on('submit', function (evt) {
    evt.preventDefault();
    let url = $(this).attr('action');
    let postData = $(this).serialize();
    console.log(url);
    console.log(postData);
    axios.post(url, postData).then(function (response) {
      console.log(response);
      $("input[data-type='csrf']").attr("name", response.data.csrf.tokenKey);
      $("input[data-type='csrf']").attr("value", response.data.csrf.token);
      $("meta[name='csrf']").attr("data-key", response.data.csrf.tokenKey);
      $("meta[name='csrf']").attr("data-token", response.data.csrf.token);
    }).catch(function (error) {
      console.log(error);
      if (!!error.csrf) {
        $("input[data-type='csrf']").attr("name", error.data.csrf.tokenKey);
        $("input[data-type='csrf']").attr("value", error.data.csrf.token);
        $("meta[name='csrf']").attr("data-key", error.data.csrf.tokenKey);
        $("meta[name='csrf']").attr("data-token", error.data.csrf.token);
      }
    })

  });

  $("#formRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");


    $.post(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });


  $("#formPasswordResetConfirm").submit(function(evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    $.post(url, postData, function (resp) {
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
