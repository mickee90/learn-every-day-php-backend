"use strict";

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

define(function () {
  var Account =
  /*#__PURE__*/
  function () {
    function Account() {
      _classCallCheck(this, Account);
    }

    _createClass(Account, [{
      key: "postLoginForm",
      value: function postLoginForm() {
        var $login_account_form = $("#login_account_form"),
            task = "postLoginForm",
            username = $login_account_form.find("input[name=username]").val() ? $login_account_form.find("input[name=username]").val() : '',
            password = $login_account_form.find("input[name=password]").val() ? $login_account_form.find("input[name=password]").val() : '',
            url = window.location.pathname;

        if (username !== '' && password !== '') {
          var data = {
            task: task,
            username: username,
            password: password
          };
          new AjaxRequest().setUrl(url).setData(data).execute().then(function (data) {
            new window.FlashMessage().execute({
              type: 'success',
              message: data.content
            });
          }).catch(function (data) {
            new window.FlashMessage().execute({
              type: 'error',
              message: data
            });
          });
        } else {
          console.log("Fyll i båda fälten");
        }
      }
    }, {
      key: "editForm",
      value: function editForm() {
        var $edit_user_form = $("#edit_user_form"),
            task = "editForm",
            username = $edit_user_form.find("input[name=username]").val() ? $edit_user_form.find("input[name=username]").val() : '',
            first_name = $edit_user_form.find("input[name=first_name]").val() ? $edit_user_form.find("input[name=first_name]").val() : '',
            last_name = $edit_user_form.find("input[name=last_name]").val() ? $edit_user_form.find("input[name=last_name]").val() : '',
            address = $edit_user_form.find("input[name=address]").val() ? $edit_user_form.find("input[name=address]").val() : '',
            zip_code = $edit_user_form.find("input[name=zip_code]").val() ? $edit_user_form.find("input[name=zip_code]").val() : '',
            city = $edit_user_form.find("input[name=city]").val() ? $edit_user_form.find("input[name=city]").val() : '',
            email = $edit_user_form.find("input[name=email]").val() ? $edit_user_form.find("input[name=email]").val() : '',
            phone = $edit_user_form.find("input[name=phone]").val() ? $edit_user_form.find("input[name=phone]").val() : '',
            password_1 = $edit_user_form.find("input[name=password_1]").val() ? $edit_user_form.find("input[name=password_1]").val() : '',
            password_2 = $edit_user_form.find("input[name=password_2]").val() ? $edit_user_form.find("input[name=password_2]").val() : '',
            id = $edit_user_form.find("input[name=id]").val() ? $edit_user_form.find("input[name=id]").val() : '',
            url = window.location.pathname;

        if (password_1 === '' && password_2 !== '' || password_1 !== '' && password_2 === '') {
          alert('Fyll i båda lösenorden');
          return false;
        }

        if (password_1 !== password_2) {
          alert('Lösenorden matchar inte');
          return false;
        }

        if (!username || !first_name || !last_name || !address || !zip_code || !city || !email || !id) {
          alert('Fyll i de obligatoriska fälten');
          return false;
        }

        console.log(username, first_name, last_name, address, zip_code, city, email, phone, password_1, password_1, id);
        var data = {
          task: task,
          username: username,
          first_name: first_name,
          last_name: last_name,
          address: address,
          zip_code: zip_code,
          city: city,
          email: email,
          phone: phone,
          password_1: password_1,
          password_2: password_2,
          id: id
        };
        new AjaxRequest().setUrl(url).setData(data).execute().then(function (data) {
          new window.FlashMessage().execute({
            type: 'success',
            message: data.content
          });
        }).catch(function (data) {
          new window.FlashMessage().execute({
            type: 'error',
            message: data
          });
        });
      }
    }]);

    return Account;
  }();

  return Account;
});