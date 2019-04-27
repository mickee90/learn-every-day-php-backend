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

      this.events();
    }

    _createClass(Post, [{
      key: "helloWorld",
      value: function helloWorld() {
        console.log('Hello World Post');
      }
    }, {
      key: "events",
      value: function events() {
        var self = this;
        $("body").on('click', ".js-btn", function (e) {
          var method = $(this).attr('data-method');
          self[method].call();
        });
      }
    }, {
      key: "showPostForm",
      value: function showPostForm() {
        $("#post_form").show();
      }
    }]);

    return Post;
  }();

  return Post;
});