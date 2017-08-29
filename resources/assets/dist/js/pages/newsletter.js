"use strict";

var _interceptors = require("../components/interceptors");

var _interceptors2 = _interopRequireDefault(_interceptors);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {

  $("#formNewsletterSubscribe").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been registered, please verify you email address in your inbox!',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been validated',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formNewsletterUnSubscribe").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been unsubscribed from future newsletters!',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });
});
//# sourceMappingURL=newsletter.js.map