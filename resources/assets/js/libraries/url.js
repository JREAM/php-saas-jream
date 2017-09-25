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

module.exports = Url;
