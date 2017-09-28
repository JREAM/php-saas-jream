// -----------------------------------------------------------------------------
// Form Utilities
// -----------------------------------------------------------------------------
class Forms {

  constructor(id) {
    // Remove # incase exists
    this.setId(id);
  }

  setId(id) {
    if (!_.isString(id)) {
      throw 'Forms::setId(id) must be a string';
    }
    this.id = '#' + id.replace('#', '');
    return this.id;
  }

  // Disable all Form Elements
  disable(id) {
    id = id ? this.setId(id) : this.id;

    $(`${id}`).find('input, textarea, button, select').attr('disabled', 'disabled');
  }

  // Enable all Form Elements
  enable(id) {
    id = id ? this.setId(id) : this.id;
    $(`${id}`).find('input, textarea, button, select').removeAttr('disabled');
  }

}

module.exports = Forms;

// -----------------------------------------------------------------------------
