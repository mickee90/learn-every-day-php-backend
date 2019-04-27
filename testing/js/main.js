"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

define(function () {
  var Main = function Main() {
    _classCallCheck(this, Main);

    var $tab_bar = $("#tab_bar"),
        $tab_content = $(".tab_content"),
        $tab_container = $(".tab_container"),
        $tab = $(".tab");

    if ($tab_bar.length) {
      $tab_content.hide();
      var first_tab = $tab_bar.children().first(),
          first_tab_attr = first_tab.attr('data-type');
      first_tab.addClass("active");
      $(".tab_content[data-type='" + first_tab_attr + "']").show();
    }

    $tab_bar.on('click', '.tab', function () {
      $tab.removeClass("active");
      $tab_content.hide();
      $(this).addClass("active");
      $(".tab_content[data-type='" + $(this).attr('data-type') + "']").show();
    });
    $("#datepicker").datepicker({
      maxDate: '90',
      dateFormat: "yy-mm-dd"
    });
  };

  return Main;
});