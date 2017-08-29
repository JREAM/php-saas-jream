'use strict';

var _sweetalert = require('sweetalert2');

var _sweetalert2 = _interopRequireDefault(_sweetalert);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var sections = ['#page-account', '#page-forgotpassword', '#page-forgotpassword', '#page-createpassword?'];

$(function () {

  $("#formLogin").on('submit', function (evt) {
    evt.preventDefault();

    var url = $(this).attr('action');
    var postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      (0, _sweetalert2.default)({
        title: 'Success',
        text: 'Logging In..',
        type: 'success',
        timer: 2000
      }).then(function () {},
      // When Timer is Complete, or item Closed
      function (dismiss) {
        if (dismiss === 'timer') {
          window.location = '/dashboard';
        }
        window.location = '/dashboard';
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formRegister").submit(function (evt) {
    evt.preventDefault();

    var postData = $(this).serialize();
    var url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {
      (0, _sweetalert2.default)({
        title: 'Success',
        text: 'Logging In..',
        type: 'success',
        timer: 2000
      }).then(function () {},
      // When Timer is Complete, or item Closed
      function (dismiss) {
        if (dismiss === 'timer') {
          window.location = '/dashboard';
        }
        window.location = '/dashboard';
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formPasswordResetConfirm").submit(function (evt) {
    evt.preventDefault();

    var postData = $(this).serialize();
    var url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {
      (0, _sweetalert2.default)({
        title: 'Success',
        text: 'Please Check your Email',
        type: 'success'
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#logout").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");

    axios.get(url, postData).then(function (response) {
      window.location = '/';
    });
  });
});
//# sourceMappingURL=auth.js.map