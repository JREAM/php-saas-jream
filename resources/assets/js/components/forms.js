const formUtil = {
  // Disable all Form Elements
  disable: function(id) {
    $(`#${id}`).find('input, textarea, button, select').attr('disabled', 'disabled');
  },
  // Enable all Form Elements
  enable: function(id) {
    $(`#${id}`).find('input, textarea, button, select').attr('disabled', 'disabled');
  }
};

module.exports = formUtil;

