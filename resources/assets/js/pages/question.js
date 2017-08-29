import axios from "../components/interceptors";

$(() => {

  $("#formQuestionCreate").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your Question was Posted',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your reply was Posted.',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your question was removed.',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });
  });

});
