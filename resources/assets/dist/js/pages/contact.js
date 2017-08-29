"use strict";

var _interceptors = require("../components/interceptors");

var _interceptors2 = _interopRequireDefault(_interceptors);

var _forms = require("./../components/forms");

var _forms2 = _interopRequireDefault(_forms);

var _sweetalert = require("sweetalert2");

var _sweetalert2 = _interopRequireDefault(_sweetalert);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {

  $("#formContact").on('submit', function (evt) {
    evt.preventDefault();

    _forms2.default.disable(this.id);
    var url = $(this).attr('action');
    var postData = $(this).serialize();

    window.axios.post(url, postData).then(function (response) {

      if (reponse.result == 0) {
        throw Exception(response);
      }

      (0, _sweetalert2.default)({
        title: 'Email Dispatched',
        text: response.msg,
        type: 'success',
        cancelButtonText: 'Close',
        timer: 2000
      }).then(function () {},
      // Promise Rejection
      function (dismiss) {
        if (dismiss === 'timer') {
          window.location = '/contact/thanks';
        }
        window.location = '/contact/thanks';
      });
    }).catch(function (error) {

      (0, _sweetalert2.default)({
        title: 'Error',
        text: error.msg,
        type: 'error',
        showCancelButton: true,
        cancelButtonText: 'Close'
      });

      // Reset Recaptcha
      grecaptcha.reset();
      _forms2.default.enable(this.id);
    });
  });
});
//# sourceMappingURL=contact.js.map