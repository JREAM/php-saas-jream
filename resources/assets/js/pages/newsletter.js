import axios from "./../components/axios";

$(() => {

  $("#formNewsletterSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

  $("#formNewsletterUnSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    $.get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });

});
