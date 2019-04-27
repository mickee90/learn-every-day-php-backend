"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

requirejs.config({
  enforceDefine: false,
  baseUrl: '/Resources/js',
  paths: {
    ckeditor: 'vendor/ckeditor/ckeditor'
  },
  shim: {
    'datepicker': {
      deps: ['jquery-ui']
    }
  },
  urlArgs: "bust=" + new Date().getTime()
});
requirejs(['ckeditor', 'User', 'Post', 'Main', 'Contact', 'Fetch', 'Flash_Message', 'Utils', 'Media'], function (CKEDITOR, User, Post, Main, Contact, FetchRequest, FlashMessage, Utils, Media) {
  var initiated = false;
  var classes = {
    User: User,
    Post: Post,
    Main: Main,
    Contact: Contact,
    Media: Media
  };

  var Core =
  /*#__PURE__*/
  function () {
    function Core() {
      _classCallCheck(this, Core);

      initiated = this;
      this.$body = document.querySelector("body");
      this.$html = document.querySelector("html");
      this.$js_btn = document.querySelector('.js-btn');
      this.events();
      this.main = new Main();
      this.Media = new Media();
    }

    _createClass(Core, [{
      key: "events",
      value: function events() {
        document.addEventListener('click', function (e) {
          if (e.target && e.target.className.includes('js-btn')) {
            Core.router(e);
          }
        }); // if(this.$js_btn) {
        // 	this.$js_btn.addEventListener('click', (e) => {
        // 		Core.router(e);
        // 	});
        // }
      }
    }], [{
      key: "router",
      value: function router(e) {
        var $target = e.target,
            task = $target.getAttribute('data-task') || false;
        if (!task) return false;

        if (task.includes('.')) {
          var tasks = task.split('.'),
              $class = tasks[0],
              $method = tasks[1];

          if (_typeof(new classes[$class]()) === 'object' && typeof new classes[$class]()[$method] === 'function') {
            new classes[$class]()[$method](e);
          }
        } else {
          if (typeof Core[task] === "function") Core[task]();
        }
      }
    }, {
      key: "getInstance",
      value: function getInstance() {
        if (initiated) return initiated;else return new Core();
      }
    }]);

    return Core;
  }();

  Core.getInstance();
});