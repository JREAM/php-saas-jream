"use strict";

var _interceptors = require("../components/interceptors");

var _interceptors2 = _interopRequireDefault(_interceptors);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr("action");

    $.get(url, postData, function (resp) {
      console.log(resp);
    }, "json");
  });
});
//# sourceMappingURL=purchase.js.map