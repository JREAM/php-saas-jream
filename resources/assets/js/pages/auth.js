// ─────────────────────────────────────────────────────────────────────────────
// Document Ready
// ─────────────────────────────────────────────────────────────────────────────
$(() => {

  // DI Container (?)
  // Or global to store

  // ─────────────────────────────────────────────────────────────────────────────
  // This is Globally Accessible
  // ─────────────────────────────────────────────────────────────────────────────

  if (window.page_id === 'page-user-login') {
    xhr.stdForm('#form-user-login');
  }
  if (window.page_id === 'page-user-register') {
    xhr.stdForm('#form-user-register');
  }
  if (window.page_id === 'page-user-password') {
    xhr.stdForm('#form-user-password-reset-confirm');
  }

  // ─────────────────────────────────────────────────────────────────────────────

});
