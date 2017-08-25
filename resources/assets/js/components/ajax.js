module.exports = {

  updateCSRF: function(csrf) {
    console.log(csrf)
      $("input[data-type='csrf']").attr("name", csrf.tokenKey);
      $("input[data-type='csrf']").attr("value", csrf.token);
      $("meta[name='csrf']").attr("data-key", csrf.tokenKey);
      $("meta[name='csrf']").attr("data-token", csrf.token);
  }

};

