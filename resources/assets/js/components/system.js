/**
 * Standard XHR Method, reduces code duplication
 *
 * @TODO: Reduce Dependencies to only use ES6, perhaps no UnderscoreJS either.
 */
class Xhr {

  /**
   * Handles a common XHR situation, using jQuery Selectors and Axios POST,
   * this is also dependent on the Axios Interceptors in my other file to handle
   * exceptions.
   *
   * @param {{string}}  id   String ID of the form element
   * @param {{closure}} callable Acceptable callbacks:
   *                              beforeSubmit(); // evt
   *                              afterSubmit();  // evt
   *                              success();      // resp, evt
   *                              fail();         // resp, evt
   *
   * @example: ...
   *    Simple:    xhr.stdForm("#formId");
   *    Callbacks: xhr.stdForm("#formId2", {
   *        function(evt) {
   *          alert('Submitting!')
   *        },
   *        'beforeSubmit': function(evt) {
   *          console.log('Result!');
   *          console.log(evt);
 *          }
   *    });
   */
  static stdForm(id, callable = {}) {

    // Only accept strings, cleaner code to change
    this.id = id;
    if (!_.isString(this.id)) {
      throw 'The id passed must be a string to XHRStandard.';
    }

    // Do not bind something non-existant
    this.element = $(id);
    if (this.element.length != 1) {
      return false;
    }

    // Bind to submit method
    this.element.submit((evt) => {
      evt.preventDefault();

      // Run beforeXHR (optional)
      if (_.has(callable, 'beforeSubmit') && _.isFunction(beforeSubmit)) {
        callable.beforeSubmit(evt);
      }

      // Disable this when handling XHR
      this.btnSubmit = this.element.find(':submit');
      this.btnSubmit.prop('disabled', true);

      const url = this.element.attr('action');
      const postData = this.element.serialize();

      axios.post(url, postData).then(resp => {
        $(this).notify(resp.data.msg, resp.data.type);

        // (Optional) If Callback
        if (_.has(callable, 'success') && _.isFunction(callable.success)) {
          callable.success(resp, evt);
        }
      }).catch(function(err) {
        $(this).notify(err.msg, err.type);

        // (Optional) If Callback
        if (_.has(callable, 'fail') && _.isFunction(callable.fail)) {
          callable.fail(resp, evt);
        }
      });

      axios.post(url, postData).then(resp => {
        window.location = resp.data.data.redirect;
      }).then(() => {
        // Aways re-enable the button
        this.btnSubmit.prop('disabled', false);
      }).catch(err => {
        $(this).notify(err.msg, err.type);
      });

      // Run beforeXHR (optional)
      if (_.has(callable, 'afterSubmit') && _.isFunction(callable.afterSubmit)) {
        callable.afterSubmit(evt);
      }

    });
  }
}

class Url {
  /**
   * Creates a URL properly
   * @param {string} append
   * @returns {string}
   */
  static create(uri) {
    let base_url = _.trimEnd(window.base_url, '/');
    let new_uri = _.trim(uri, '/');
    return `${base_url}/${new_uri}`;
  }

  /**
   * Redirects via JavaScript
   * @depends url()
   *
   * @param {string} uri
   * @returns {string}
   */
  static redirect(uri) {
    let new_location = url(uri);
    return window.location = new_location;
  }

  /**
   * Adds a Hash URL
   * @param hash
   */
  static hash(hash) {
    if (history.replaceState) {
      history.replaceState(null, null, `#${hash}`);
    }
    else {
      location.hash = `#${hash}`;
    }
  }
}

module.exports = {
  Xhr: Xhr,
  Url: Url,
};
