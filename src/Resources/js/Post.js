"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var Post =
  /*#__PURE__*/
  function () {
    function Post() {
      _classCallCheck(this, Post);

      this.$create_post_form = document.querySelector('#create_post_form');
      this.$edit_post_form = document.querySelector('#edit_post_form'); //this.edit_post_inputs = document.querySelectorAll('.form_input');

      this.$create_message_form = document.querySelector('#post_message_form');
      this.events();
    }

    _createClass(Post, [{
      key: "events",
      value: function events() {}
    }, {
      key: "create",
      value: function create() {
        this.prepareAjaxForm(document.querySelectorAll('.form_input'));
      }
    }, {
      key: "edit",
      value: function edit() {
        this.prepareAjaxForm(document.querySelectorAll('.form_input'));
      }
    }, {
      key: "sendMessage",
      value: function sendMessage() {
        this.prepareAjaxForm(this.$create_message_form[0]);
      }
    }, {
      key: "prepareAjaxForm",
      value: function prepareAjaxForm(form) {
        var url = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : window.location.pathname;
        console.log(form);
        var inputs = Utils.prepareInputs(form);
        console.log(inputs);

        if (!Utils.validateObjectItems(inputs)) {
          new window.FlashMessage().execute({
            type: 'error',
            message: 'Fyll i de obligatoriska fälten.'
          });
          return false;
        }

        var data = FetchRequest.prepareFormDataFromObjects(inputs);
        this.ajaxForm(data, url);
      }
    }, {
      key: "ajaxForm",
      value: function ajaxForm(data, url) {
        new FetchRequest().setUrl(url).setBody(data).execute().then(function (data) {
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
      }
    }, {
      key: "showForm",
      value: function showForm() {
        document.querySelector("#post_message_form").classList.toggle('show');
      }
    }]);

    return Post;
  }();

  return Post;
});