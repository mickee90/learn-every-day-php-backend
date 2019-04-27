"use strict";

var Account = function Account() {
  this.events(); //this.$body = $("body");
};

Account.prototype.events = function () {
  var self = this;
  $("body").on('click', ".js-btn", function (e) {
    var method = $(this).attr('data-method');
    self[method].call();
  });
};

Account.prototype.postLoginForm = function () {
  var $login_account_form = $("#login_account_form"),
      task = "postLoginForm",
      username = $login_account_form.find("input[name=username]").val() ? $login_account_form.find("input[name=username]").val() : '',
      password = $login_account_form.find("input[name=password]").val() ? $login_account_form.find("input[name=password]").val() : '',
      url = window.location.pathname;

  if (username !== '' && password !== '') {
    $.ajax({
      type: "POST",
      url: url,
      async: false,
      dataType: "json",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      data: {
        task: task,
        username: username,
        password: password
      },
      cache: false,
      success: function success(data) {
        if (data['redirect'] !== '') {
          window.location.href = data['redirect'];
        }
      },
      error: function error(data) {
        console.log('no');
      }
    });
  } else {
    console.log("Fyll i båda fälten");
  }
};

$(document).ready(function () {
  new Account();
});