// ─────────────────────────────────────────────────────────────────────────────
// Document Ready
// ─────────────────────────────────────────────────────────────────────────────
$(() => {
  // ─────────────────────────────────────────────────────────────────────────────
  // Only Apply to proper Page
  // ─────────────────────────────────────────────────────────────────────────────
  if (routes.current.controller != 'user') {
    return false;
  }

  // ─────────────────────────────────────────────────────────────────────────────

  $('#toggle-timezone').click((evt) => {
    evt.preventDefault();
    $('#form-timezone').toggleClass('hide');
  });

  // ─────────────────────────────────────────────────────────────────────────────

  $('#form-dashboard-account-timezone').submit((evt) => {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────

  $('#form-dashboard-account-email').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────

  $('#form-dashboard-account-password').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────

  $('#form-dashboard-account-delete').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────

  $('#formDashboardAccountNotification').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────

  // Is this used?
  $('#form-dashboard-notification').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((response) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    }).catch((error) => {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────
});
