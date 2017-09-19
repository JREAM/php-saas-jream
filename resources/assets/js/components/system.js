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
   *        }
   *    });
   */
  static stdForm(id, callable = {}) {

    // Only accept strings, cleaner code to change
    if (!_.isString(id)) {
      throw 'The id passed must be a string to XHRStandard.';
    }

    // Do not bind something non-existant
    this.element = $(id);
    if (this.element.length != 1) {
      // console.log(id); // Debug if Missing, problem!
      return false;
    }


    // Bind to submit method
    this.element.submit((evt) => {
      evt.preventDefault();

      // @TODO @DEBUG HERE Why i get no class blah blah error
      //return false;

      // Run beforeXHR (optional)
      // if (_.has(callable, 'beforeSubmit') && _.isFunction(beforeSubmit)) {
      //   callable.beforeSubmit(evt);
      // }

      // Disable this when handling XHR
      this.btnSubmit = $(id).find(':submit');
      if (this.btnSubmit.length > 0) {
        this.btnSubmit.prop('disabled', true);
      }

      const url = $(id).attr('action');
      const postData = $(id).serialize();

      if (!url) {
        alert('Missing URL for Form!', 'error');
        return false;
      }

      axios.post(url, postData).then(resp => {

        if (_.has(resp, 'msg')) {
          $(id).notify(resp.msg, resp.type);
        }

        // If a redirect is returned in the 'data' from Phalcon
        if (_.has(resp, 'data') && _.has(resp.data, 'redirect')) {
          window.location = resp.data.redirect;
          // @TODO: Does this one work?
          //window.location = Url.create(resp.data.redirect);
          return true;
        }

        // (Optional) If Callback
        // if (_.has(callable, 'success') && _.isFunction(callable.success)) {
        //   callable.success(resp, evt);
        // }
      }).then(() => {
        // Aways re-enable the button
        this.btnSubmit.prop('disabled', false);
      }).catch(error => {
        if (_.has(error, error.msg)) {
          $(id).notify(error.msg, error.type);
        }

        // (Optional) If Callback
        // if (_.has(callable, 'fail') && _.isFunction(callable.fail)) {
        //   callable.fail(resp, evt);
        // }
        // Aways re-enable the button
        this.btnSubmit.prop('disabled', false);
      });

      // Run beforeXHR (optional)
      // if (_.has(callable, 'afterSubmit') && _.isFunction(callable.afterSubmit)) {
      //   callable.afterSubmit(evt);
      // }

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
