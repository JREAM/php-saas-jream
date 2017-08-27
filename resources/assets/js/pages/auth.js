/**
 * Update CSRF
 *
 * @param response
 */
function updateCsrf(response) {
  console.log(response)

  $(".csrf-field")
    .attr("name", response.tokenKey)
    .attr("value", response.token);

  console.log( $(".csrf-field").attr('name') );

  $("meta#csrf")
    .attr("data-key", response.tokenKey)
    .attr("data-token", response.token);

  console.log( $("meta#csrf").attr('data-key') );
  console.log( $("meta#csrf").attr('data-token') );
}


$(() => {

  $("#formLogin").on('submit', function (evt) {
    evt.preventDefault();

    let url = $(this).attr('action');
    let postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      console.log(response);
      updateCsrf(response);

    }).catch(function (error) {
      console.log(error);
      if (!!error.data) {
        updateCsrf(error);
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


  $("#formPasswordResetConfirm").submit(function (evt) {
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
