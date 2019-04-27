"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var Media =
  /*#__PURE__*/
  function () {
    function Media() {
      _classCallCheck(this, Media);

      this.$image_uploader = document.querySelector("#image_uploader");
      this.image_list = document.querySelector("#image_list");
      this.events();
    }

    _createClass(Media, [{
      key: "events",
      value: function events() {
        var _this = this;

        if (this.$image_uploader !== null) this.$image_uploader.addEventListener("change", function (e) {
          _this.uploadImages(e);
        });
      }
    }, {
      key: "uploadImages",
      value: function uploadImages(e) {
        var $target = e.target,
            file_list = $target.files,
            data = new FormData();
        if (file_list.length === 0) return false;
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          for (var _iterator = file_list[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var $file = _step.value;
            data.append('files[]', $file);
          }
        } catch (err) {
          _didIteratorError = true;
          _iteratorError = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion && _iterator.return != null) {
              _iterator.return();
            }
          } finally {
            if (_didIteratorError) {
              throw _iteratorError;
            }
          }
        }

        this.ajaxForm(data, '/media/upload', 'appendData');
      }
    }, {
      key: "appendData",
      value: function appendData(data) {
        var _this2 = this;

        data.forEach(function (media) {
          var new_media_input = "<div class='image_item'>" + "<div class='image'><img src='" + media.url + "' alt='" + media.nice_name + "' /></div>" + "<input class='form_input' type='hidden' name='images[]' value='" + media.id + "' />" + "<div class='btn_list link_btn js-btn' data-task='Media.remove'>Ta bort</div>" + "</div>";
          _this2.image_list.innerHTML = _this2.image_list.innerHTML + new_media_input;
        });
      }
    }, {
      key: "remove",
      value: function remove(e) {
        e.target.parentNode.remove();
      }
    }, {
      key: "ajaxForm",
      value: function ajaxForm(data, url, method) {
        new FetchRequest().setUrl(url).setBody(data).execute().then(function (data) {
          new Media()[method](data);
        }).catch(function (data) {
          new window.FlashMessage().execute({
            type: 'error',
            message: data
          });
        });
      }
    }]);

    return Media;
  }();

  return Media;
});