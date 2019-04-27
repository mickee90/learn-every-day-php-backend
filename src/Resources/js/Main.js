"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

define(function () {
  var Main =
  /*#__PURE__*/
  function () {
    function Main() {
      _classCallCheck(this, Main);

      this.tab_bar = document.querySelector("#tab_bar");
      this.$datepicker = document.querySelector("#datepicker");
      this.events();
    }

    _createClass(Main, [{
      key: "events",
      value: function events() {
        if (this.tab_bar) // this.handleTabs();
          // if(this.$datepicker)
          // 	this.$datepicker.datepicker({maxDate: '90', dateFormat: "yy-mm-dd"});
          console.log('fail ck_editor');

        try {
          CKEDITOR.replace('ck_editor', {
            toolbar: [{
              name: 'clipboard',
              items: ['Undo', 'Redo']
            }, {
              name: 'basicstyles',
              items: ['Bold', 'Italic', 'Underline', 'RemoveFormat']
            }, {
              name: 'align',
              items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            }]
          });
        } catch (_unused) {}
      }
    }, {
      key: "handleTabs",
      value: function handleTabs() {
        var tab_contents = document.querySelectorAll(".tab_content"),
            tabs = document.querySelectorAll(".tab"),
            first_tab = this.tab_bar.firstElementChild,
            first_tab_attr = first_tab.dataset.type;
        Utils.setStyleOnElms(tab_contents, 'display', 'none');
        Utils.addClass(first_tab, 'active');
        document.querySelector(".tab_content[data-type='" + first_tab_attr + "']").style.display = 'block';

        if (tabs !== null) {
          [].forEach.call(tabs, function (tab) {
            tab.addEventListener('click', function (e) {
              Utils.removeClass(tabs, 'active');
              Utils.setStyleOnElms(tab_contents, 'display', 'none');
              Utils.addClass(tab, 'active');
              document.querySelector(".tab_content[data-type='" + tab.dataset.type + "']").style.display = 'block';
            });
          });
        }
      }
    }]);

    return Main;
  }();

  return Main;
});