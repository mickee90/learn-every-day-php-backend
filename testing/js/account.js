"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var initiated = false;

  var Account =
  /*#__PURE__*/
  function () {
    function Account() {
      _classCallCheck(this, Account);

      initiated = this;
      console.log('hi Account');
      this.helloWorld();
    }

    _createClass(Account, [{
      key: "helloWorld",
      value: function helloWorld() {
        var $html = $(html);
        console.log($html);
        return 'yas';
      }
    }, {
      key: "postLoginForm",
      value: function postLoginForm() {
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
              "X-Request-With": "XMLHttpRequest"
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
      }
    }], [{
      key: "getInstance",
      value: function getInstance() {
        if (initiated) return initiated;else return new Account();
      }
    }]);

    return Account;
  }();

  return Account;
});