"use strict";

var _interceptors = require("../components/interceptors");

var _interceptors2 = _interopRequireDefault(_interceptors);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {

  $("#formUpdateTimezone").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your Timezone has been updated',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formUpdateEmail").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email was updated',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formUpdateNotificationsAction").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your notification settings have been updated.',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });
});
//# sourceMappingURL=user.js.map