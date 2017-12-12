class Url {

  /**
   * Creates a URL properly
   * @param {string} append
   * @returns {string}
   */
  static create(uri) {
    const baseUrl = _.trimEnd(window.baseUrl, '/');
    const newUri = _.trim(uri, '/');
    return `${baseUrl}/${newUri}`;
  }

  /**
   * Redirects via JavaScript
   * @depends url()
   *
   * @param {string} uri
   * @returns {string}
   */
  static redirect(uri) {
    const newLocation = url(uri);
    return window.location = newLocation;
  }

  /**
   * Adds a Hash URL
   * @param hash
   */
  static hash(hash) {
    if (history.replaceState) {
      history.replaceState(null, null, `#${hash}`);
    } else {
      window.location.hash = `#${hash}`;
    }
  }
}

module.exports = Url;
