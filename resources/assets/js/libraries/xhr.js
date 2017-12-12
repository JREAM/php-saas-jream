/**
 * Standard XHR Method, reduces code duplication
 *
 * @depends libaries/forms
 */
window.Forms = require('../libraries/forms');

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
   *                              passback={};    // will passback the values inside to the response
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
    // Form Handler for enable/disable
    this.Form = new Forms(id);
    // Only accept strings, cleaner code to change
    if (!_.isString(id)) {
      throw 'The id passed must be a string to XHRStandard.';
    }

    // Do not bind something non-existant
    this.element = $(id);
    if (this.element.length !== 1) {
      console.error(`XHR Attachment Problem: ${id} - not found`); // Debug if Missing, problem!
      return false;
    }


    // Bind to submit method
    this.element.submit((evt) => {
      evt.preventDefault();
      console.log('submit');
      // @TODO @DEBUG HERE Why i get no class blah blah error
      // return false;

      // Run beforeXHR (optional)
      if (_.has(callable, 'beforeSubmit') && _.isFunction('beforeSubmit')) {
        callable.beforeSubmit(evt);
      }

      // Disable this when handling XHR
      this.Form.disable();

      const url = $(id).attr('action');
      const postData = $(id).serialize();

      // Coding Error, My Mistake warn!
      if (!url) {
        alert('Missing URL for Form!', 'error');
        return false;
      }

      axios.post(url, postData).then((resp) => {
        if (_.has(resp, 'msg') && resp.msg) {
          const type = (_.has(resp, 'type') && resp.type) ? resp.type : 'warning';
          alert(resp.msg);
          // $(id).notify(resp.msg, type);
        }

        // If a redirect is returned in the 'data' from Phalcon
        if (_.has(resp.data, 'redirect') && resp.data.redirect) {
          window.location = resp.data.redirect;
          return true;
        }

        // (Optional) If Callback
        if (_.has(callable, 'success') && _.isFunction(callable.success)) {
          callable.success(resp, evt);
        }
      }).then((resp) => {
        // Aways re-enable the button
        if (!_.has(resp, 'data') && !_.has(resp.data, 'keep_form_disabled' && resp.data.keep_form_disable != true)) {
          this.Form.enable();
        }
      }).catch((error) => {
        if (_.has(error, error.msg)) {
          alert(error.msg, error.type);
        }

        // (Optional) If Callback
        if (_.has(callable, 'fail') && _.isFunction(callable.fail)) {
          callable.fail(resp, evt);
        }
        // Aways re-enable the button
        this.Form.enable();
      });

      // Run beforeXHR (optional)
      if (_.has(callable, 'afterSubmit') && _.isFunction(callable.afterSubmit)) {
        callable.afterSubmit(evt);
      }
    });
  }
}

module.exports = Xhr;
