// -----------------------------------------------------------------------------
// Interceptor AJAX Error Handler
// -----------------------------------------------------------------------------

/**
 *   200 - Throws and exception if the result is 0 from the server.
 *
 *   301 - Moved Permanently
 *   308 - Permanent Redirect
 *
 *   400 - Bad Request
 *   401 - Unauthorized
 *   403 - Forbidden
 *   404 - Not Found
 *   405 - Method not Allowed
 *   408 - Request Timeout
 *   440 - Login Time-out
 *
 *   500 - Internal Service Error
 *   501 - Not Implemented
 *   502 - Bad Gateway
 *   503 - Service Unavailable
 *   504 - Gateway Timeout
 *   505 - HTTP Version Not Accepted
 *   508 - Loop Detected
 */
axios.interceptors.response.use(response => {

  // If JSON Output Error/Warn, handle it specially
  let errors = [
    window.notifications.error,
    window.notifications.warn
  ];

  if (_.indexOf(errors, response.data.result) != -1) {

    // List of Errors
    if (_.has(response.data, 'data') && _.isArray(response.data.data) && response.data.data.length > 0) {
      // Create a list to output
      let error_list = String('<ul>');
      for (var error of response.data.data) {
        error_list += `<li>${error}</li>`;
      }
      error_list += String('</ul>');

      $.notify(error_list, response.data.type);
    } else {
      $.notify(response.data.msg, response.data.type);
    }
    // And throw anyways
    throw response.data;
  }

  return response;

}, error => {

  if (!error || !_.has(error, 'response')) {
    return Promise.reject('No response found in the error, nevertheless one was thrown.');
  }

  switch (error.response.status) {

    // @ 300 Errors
    case 301:
      console.log(301);
      swal({
        type: "warning",
        title: "Moved Permanently",
        text: "Error, this URI has moved permanently."
      });
      break;
    case 302:
      console.log(302);
      swal({
        type: "warning",
        title: "Permanent Redirect",
        text: "Error, This was permanently redirected."
      });
      break;

    // @ 400 Errors
    case 400:
      swal({
        type: "warning",
        title: "Bad Request",
        text: "Error, This was a bad request that does not exist."
      });
      break;
    case 401:
      console.log(401);
      swal({
        type: "warning",
        title: "Unauthorized; Your Session has expired.",
        text: "Your session has expired, You must login again.",
        closeOnConfirm: false
      }, () => {
        window.location = "/user/login";
      });
      break;
    case 403:
      console.log(403);
      swal({
        type: "warning",
        title: "Forbidden",
        text: "Sorry, you are not allowed to access this"
      });
      break;
    case 404:
      console.log(404);
      swal({
        type: "warning",
        title: "Not Found",
        text: "Error, The call was not found."
      });
      break;
    case 405:
      console.log(405);
      swal({
        type: "warning",
        title: "Method not Allowed",
        text: "Error, The method called is not allowed at this endpoint."
      });
      break;
    case 408:
      console.log(408);
      swal({
        type: "warning",
        title: "Request Timeout",
        text: "Error, The call has timeout, please try again."
      });
      break;

    // @ 500 Errors
    case 500:
      console.log(500);
      swal({
        type: "warning",
        title: "Internal Service Error",
        text: "Error, There was an error in the back-end.."
      });
      break;
    case 501:
      console.log(501);
      swal({
        type: "warning",
        title: "Not Implemented",
        text: "Error, This feature is not yet implemented."
      });
      break;
    case 502:
      console.log(502);
      swal({
        type: "warning",
        title: "Bad Gateway",
        text: "Error, This is a bad gateway."
      });
      break;
    case 503:
      console.log(503);
      swal({
        type: "warning",
        title: "Service Unavailable",
        text: "Error, The service is unavailable, please try again or come back later."
      });
      break;
    case 504:
      console.log(504);
      swal({
        type: "warning",
        title: "Gateway Timeout",
        text: "Error, The gateway timeout out. Please try again."
      });
      break;

    // @ Unknown Error
    default:
      console.log('default');
      swal({
        type: "warning",
        title: "General Error",
        text: `An unknown error occured with the status of ${error}`
      });

    // Prevent a promise from occuring.
    return Promise.reject(error);
  }

});

// -----------------------------------------------------------------------------
