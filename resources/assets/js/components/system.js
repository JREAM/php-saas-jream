/**
 * Creates a URL properly
 * @param {string} append
 * @returns {string}
 */
function url(uri) {
  let base_url = _.trimEnd(window.base_url, '/');
  let uri = _.trim(uri, '/');
  return `${base_url}/${uri}`;
}

/**
 * Redirects via JavaScript
 * @depends url()
 *
 * @param {string} uri
 * @returns {string}
 */
function redirect(uri) {
  let new_location = url(uri);
  return window.location = new_location;
}

/**
 * Adds a Hash URL
 * @param hash
 */
function hashUrl(hash) {
  if (history.replaceState) {
      history.replaceState(null, null, `#${hash}`);
  }
  else {
      location.hash = `#${hash}`;
  }
}
