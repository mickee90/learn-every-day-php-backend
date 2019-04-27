"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var Contact =
  /*#__PURE__*/
  function () {
    function Contact() {
      _classCallCheck(this, Contact);

      this.events();
    }

    _createClass(Contact, [{
      key: "events",
      value: function events() {}
    }, {
      key: "sendForm",
      value: function sendForm() {
        this.prepareAjaxForm(document.querySelector("#contact_form")[0]);
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

        var data = FetchRequest.prepareDataFromObjects(inputs);
        this.ajaxForm(data, url);
      }
    }, {
      key: "ajaxForm",
      value: function ajaxForm(data, url) {
        new FetchRequest().setUrl(url).setData(data).execute().then(function (data) {
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

    return Contact;
  }();

  return Contact;
});