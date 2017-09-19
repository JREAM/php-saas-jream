
// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  system.Xhr.stdForm('#formUserLogin', {
    success: function() {
      window.location = system.Url.create('/dashboard');
    }
  });
  system.Xhr.stdForm('#formUserRegister');
  system.Xhr.stdForm('#formUserPasswordResetConfirm');

  // -----------------------------------------------------------------------------

});
