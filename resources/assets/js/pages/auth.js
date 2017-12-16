// ─────────────────────────────────────────────────────────────────────────────
// Document Ready
// ─────────────────────────────────────────────────────────────────────────────
$(() => {

  // DI Container (?)
  // Or global to store

  // ─────────────────────────────────────────────────────────────────────────────
  // This is Globally Accessible
  // ─────────────────────────────────────────────────────────────────────────────

  console.info(window.pageId)
  if (window.pageId === 'page-user-login') {
    xhr.stdForm('#form-user-login');
  }
  if (window.pageId === 'page-user-register') {
    xhr.stdForm('#form-user-register');
  }
  if (window.pageId === 'page-user-password') {
    xhr.stdForm('#form-user-password-reset-confirm');
  }

  // ─────────────────────────────────────────────────────────────────────────────

});
