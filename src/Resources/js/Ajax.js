"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var AjaxRequest =
/*#__PURE__*/
function () {
  function AjaxRequest() {
    _classCallCheck(this, AjaxRequest);

    this.base_url = 'http://127.0.0.1:8888/';
    this.url = window.location.pathname;
    this.type = 'POST';
    this.dataType = 'json';
    this.contentType = true;
    this.processData = true;
    this.data = {};
  }

  _createClass(AjaxRequest, [{
    key: "setType",
    value: function setType(type) {
      this.type = type;
      return this;
    }
  }, {
    key: "setUrl",
    value: function setUrl(url) {
      this.url = url;
      return this;
    }
  }, {
    key: "setContentType",
    value: function setContentType(type) {
      this.contentType = type;
      return this;
    }
  }, {
    key: "setProcessData",
    value: function setProcessData(data) {
      this.processData = data;
      return this;
    }
  }, {
    key: "setData",
    value: function setData() {
      var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      this.data = data;
      return this;
    }
  }, {
    key: "getOptions",
    value: function getOptions() {
      return {
        url: this.base_url + this.url,
        type: this.type,
        dataType: this.dataType,
        contentType: this.contentType,
        processData: this.processData,
        data: this.data
      };
    }
  }, {
    key: "execute",
    value: function execute() {
      var options = this.getOptions();
      var type = options.type,
          url = options.url,
          dataType = options.dataType,
          data = options.data,
          contentType = options.contentType,
          processData = options.processData;
      return new Promise(function (resolve, reject) {
        $.ajax({
          type: type,
          url: url,
          data: data,
          dataType: dataType,
          contentType: contentType,
          processData: processData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          async: false,
          cache: false
        }).done(function (data) {
          AjaxRequest.redirect(data);
          resolve(data);
        }).fail(function (xhr, status, error) {
          switch (xhr.status) {
            case 301:
              {
                window.location.href = xhr.responseJSON.content;
                break;
              }

            default:
              {
                var error_response = xhr.responseJSON.content || error;
                reject(error_response);
                break;
              }
          }
        }).always(function () {});
      });
    }
  }], [{
    key: "redirect",
    value: function redirect(data) {
      console.log(data);

      if (data.redirect !== void 0 && data.redirect !== '') {
        window.location.href = base_url + data.redirect;
      }
    }
  }, {
    key: "prepareDataFromObjects",
    value: function prepareDataFromObjects(inputs) {
      var data = {};
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = inputs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var input = _step.value;
          data[input.name] = input.value;
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

      return data;
    }
  }]);

  return AjaxRequest;
}();

window.AjaxRequest = AjaxRequest;