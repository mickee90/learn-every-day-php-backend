"use strict";

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; var ownKeys = Object.keys(source); if (typeof Object.getOwnPropertySymbols === 'function') { ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) { return Object.getOwnPropertyDescriptor(source, sym).enumerable; })); } ownKeys.forEach(function (key) { _defineProperty(target, key, source[key]); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/*
	Good to know:
	Do not add content-type: application/json if you send files,
	or want to receive the data with $_POST.
	The fetch API will automatically add the correct content-type


*/
var FetchRequest =
/*#__PURE__*/
function () {
  function FetchRequest() {
    _classCallCheck(this, FetchRequest);

    this.base_url = 'http://127.0.0.1:8888';
    this.url = window.location.pathname;
    this.method = 'POST';
    this.dataType = 'json';
    this.mode = 'same-origin';
    this.credentials = 'same-origin';
    this.body = {};
    this.headers = {
      'X-Requested-With': 'XMLHttpRequest'
    };
  }

  _createClass(FetchRequest, [{
    key: "setMethod",
    value: function setMethod(method) {
      this.method = method;
      return this;
    }
  }, {
    key: "setUrl",
    value: function setUrl(url) {
      this.url = url;
      return this;
    }
  }, {
    key: "setBody",
    value: function setBody() {
      var body = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      this.body = body;
      return this;
    }
  }, {
    key: "setMode",
    value: function setMode(mode) {
      this.mode = mode;
      return this;
    }
  }, {
    key: "setCredentials",
    value: function setCredentials(credentials) {
      this.credentials = credentials;
      return this;
    }
  }, {
    key: "setHeaders",
    value: function setHeaders() {
      var headers = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      this.headers = headers;
      return this;
    }
  }, {
    key: "addHeader",
    value: function addHeader() {
      var header = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      this.headers = _objectSpread({}, this.headers, header);
      return this;
    }
  }, {
    key: "getOptions",
    value: function getOptions() {
      return {
        method: this.method,
        body: this.body,
        mode: this.mode,
        credentials: this.credentials,
        headers: this.headers
      };
    }
  }, {
    key: "execute",
    value: function execute() {
      var _this = this;

      return new Promise(function (resolve, reject) {
        fetch(_this.base_url + _this.url, _this.getOptions()).then(function (response) {
          return _this.checkStatus(response);
        }).then(function (json) {
          if (!json.ok) {
            if (json.status === 301) {
              window.location.href = json.content;
              return false;
            } else {
              console.log(json);
              return false;
              reject(json.content);
            }
          }

          _this.checkRedirect(json);

          resolve(json.content);
        }).catch(function (error) {
          return reject(error);
        });
      });
    }
  }, {
    key: "checkRedirect",
    value: function checkRedirect(data) {
      if (data.redirect !== void 0 && data.redirect !== '') {
        window.location.href = this.base_url + data.redirect;
        return false;
      }
    }
  }, {
    key: "checkStatus",
    value: function checkStatus(response) {
      return new Promise(function (resolve) {
        return response.json().then(function (json) {
          return resolve({
            status: response.status,
            ok: response.ok,
            content: json.content,
            redirect: json.redirect,
            json: json
          });
        });
      });
    }
  }], [{
    key: "prepareFormDataFromObjects",
    value: function prepareFormDataFromObjects(inputs) {
      var data = new FormData();
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = inputs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var input = _step.value;
          data.append(input.name, input.value);
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
  }, {
    key: "prepareDataFromObjects",
    value: function prepareDataFromObjects(inputs) {
      var data = {};
      var _iteratorNormalCompletion2 = true;
      var _didIteratorError2 = false;
      var _iteratorError2 = undefined;

      try {
        for (var _iterator2 = inputs[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
          var input = _step2.value;
          data[input.name] = input.value;
        }
      } catch (err) {
        _didIteratorError2 = true;
        _iteratorError2 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion2 && _iterator2.return != null) {
            _iterator2.return();
          }
        } finally {
          if (_didIteratorError2) {
            throw _iteratorError2;
          }
        }
      }

      return data;
    }
  }]);

  return FetchRequest;
}();

window.FetchRequest = FetchRequest;