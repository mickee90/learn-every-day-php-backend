define(function() {

	class Main {

		constructor() {
			this.tab_bar = document.querySelector("#tab_bar");
			this.$datepicker = document.querySelector("#datepicker");
			this.events();

		}

		events() {
			if(this.tab_bar)
				// this.handleTabs();

			// if(this.$datepicker)
			// 	this.$datepicker.datepicker({maxDate: '90', dateFormat: "yy-mm-dd"});

				console.log('fail ck_editor');
			try {
				CKEDITOR.replace('ck_editor',{
					toolbar: [
						{ name: 'clipboard', items: [ 'Undo', 'Redo'] },
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'RemoveFormat'] },
						{ name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
					],
				});
			} catch {
			}
		}

		handleTabs() {
			let tab_contents = document.querySelectorAll(".tab_content"),
				tabs = document.querySelectorAll(".tab"),
				first_tab = this.tab_bar.firstElementChild,
				first_tab_attr = first_tab.dataset.type;

			Utils.setStyleOnElms(tab_contents, 'display', 'none');
			Utils.addClass(first_tab, 'active');
			document.querySelector(".tab_content[data-type='" + first_tab_attr + "']").style.display = 'block';

			if(tabs !== null) {
				[].forEach.call(tabs,tab => {
					tab.addEventListener('click', (e) => {
						Utils.removeClass(tabs, 'active');
						Utils.setStyleOnElms(tab_contents, 'display', 'none');
						Utils.addClass(tab, 'active');
						document.querySelector(".tab_content[data-type='" + tab.dataset.type + "']").style.display = 'block';
					});
				});
			}
		}
	}

	return Main;
});