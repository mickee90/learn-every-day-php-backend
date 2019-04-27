"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var User =
  /*#__PURE__*/
  function () {
    function User() {
      _classCallCheck(this, User);

      //this.$create_user_form = document.querySelector('#login_user_form').querySelectorAll('input');
      this.$edit_user_form = document.querySelector('#edit_user_form');
      this.$change_password_form = document.querySelector('#change_password_form');
    }

    _createClass(User, [{
      key: "loginForm",
      value: function loginForm() {
        this.prepareAjaxForm(document.querySelectorAll('.form_input'));
      }
    }, {
      key: "editForms",
      value: function editForms() {
        this.prepareAjaxForm(document.querySelectorAll('.form_input'));
      }
    }, {
      key: "changePasswordForm",
      value: function changePasswordForm() {
        this.prepareAjaxForm(this.$change_password_form[0]);
      }
    }, {
      key: "prepareAjaxForm",
      value: function prepareAjaxForm(form) {
        var url = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : window.location.pathname;
        var inputs = Utils.getInputsByForm(form);

        if (!Utils.validateObjectItems(inputs)) {
          new window.FlashMessage().execute({
            type: 'error',
            message: 'Fyll i de obligatoriska fälten.'
          });
          return false;
        }

        if (inputs.find(function (input) {
          return input.name === "password_1" || input.name === "password_2";
        })) {
          var password_1 = inputs.find(function (input) {
            return input.name === "password_1";
          }),
              password_2 = inputs.find(function (input) {
            return input.name === "password_2";
          });

          if (password_1.value !== password_2.value) {
            new window.FlashMessage().execute({
              type: 'error',
              message: 'Lösenorden matchar inte.'
            });
            return false;
          }
        }

        var data = FetchRequest.prepareFormDataFromObjects(inputs);
        this.ajaxForm(data, url);
      }
    }, {
      key: "ajaxForm",
      value: function ajaxForm(data, url) {
        new FetchRequest().setUrl(url).setBody(data).execute().then(function (data) {
          // let call = setInterval(this.scroll, 1);
          // let target = document.querySelector('#msg').offsetTop;
          // document.querySelector('body').scrollTop = target-10;
          new window.FlashMessage().execute({
            type: 'success',
            message: data
          });
        }).catch(function (data) {
          new FlashMessage().execute({
            type: 'error',
            message: data
          });
        });
      } // scroll() {
      // 	let	offset = 0;
      // 	let call;
      //
      // 	if ((offset - document.documentElement.scrollTop) > 0) {
      // 		console.log(offset);
      // 		console.log(document.documentElement.scrollTop);
      // 		document.documentElement.scrollTop += 10
      // 	}
      // 	else if ((offset - document.documentElement.scrollTop) < 0) {
      // 		console.log(offset);
      // 		console.log(document.documentElement.scrollTop);
      // 		document.documentElement.scrollTop -= 10
      // 	}
      // 	else {
      // 		clearInterval(call)
      // 	}
      // };

    }]);

    return User;
  }();

  return User;
});