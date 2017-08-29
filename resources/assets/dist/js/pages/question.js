"use strict";

var _interceptors = require("../components/interceptors");

var _interceptors2 = _interopRequireDefault(_interceptors);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {

  $("#formQuestionCreate").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your Question was Posted',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your reply was Posted.',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");
    var postData = $(this).serialize();

    _interceptors2.default.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your question was removed.',
        type: 'success',
        timer: 3000
      });
    }).catch(function (error) {
      popError(error.msg);
    });
  });
});
//# sourceMappingURL=question.js.map