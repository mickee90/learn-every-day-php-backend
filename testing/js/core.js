"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

requirejs.config({
  baseUrl: '/Resources/js',
  paths: {
    jquery: 'vendor/jquery',
    jquerymigrate: 'vendor/jquery-migrate',
    ckeditor: 'vendor/ckeditor/ckeditor'
  }
});
requirejs(['jquery', 'account', 'post', 'main'], function ($, Account, Post, Main) {
  $(function () {
    var initiated = false;

    var Core =
    /*#__PURE__*/
    function () {
      function Core() {
        _classCallCheck(this, Core);

        initiated = this;
        this.$body = $("body");
        this.$html = $("html");
        this.events();
      }

      _createClass(Core, [{
        key: "events",
        value: function events() {
          this.$body.on('click', '.js-btn', function (e) {
            var $target = $(e.currentTarget),
                task = $target.data('task') || ''; //Account.getInstance();

            var $account = new Account();
            console.log($account); //Account.helloWorld();

            /*
            console.log($target);
            console.log(task);
            //console.log($account);
            	if(task !== '') {
            	console.log(task);
            		if(task.includes('.')) {
            		let tasks = task.split('.'),
            			$class = tasks[0],
            			$method = tasks[1];
            		console.log(tasks);
            		new $class.$method;
            	} else {
            		}
            }
            */
          });
        }
      }], [{
        key: "getInstance",
        value: function getInstance() {
          if (initiated) return initiated;else return new Core();
        }
      }]);

      return Core;
    }();

    Core.getInstance();
  });
});