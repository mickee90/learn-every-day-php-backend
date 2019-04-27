define(function() {

	class Post {

		constructor() {
			this.$create_post_form = document.querySelector('#create_post_form');
			this.$edit_post_form = document.querySelector('#edit_post_form');
			//this.edit_post_inputs = document.querySelectorAll('.form_input');
			this.$create_message_form = document.querySelector('#post_message_form');
			this.events();
		}

		events() {

		}

		create() {
			this.prepareAjaxForm(document.querySelectorAll('.form_input'));
		}

		edit() {
			this.prepareAjaxForm(document.querySelectorAll('.form_input'));
		}

		sendMessage() {
			this.prepareAjaxForm(this.$create_message_form[0]);
		}

		prepareAjaxForm(form, url = window.location.pathname) {
			console.log(form);
			let inputs = Utils.prepareInputs(form);
			console.log(inputs);

			if(!Utils.validateObjectItems(inputs)) {
				(new window.FlashMessage()).execute({ type: 'error', message: 'Fyll i de obligatoriska fälten.' });
				return false;
			}

			const data = FetchRequest.prepareFormDataFromObjects(inputs);
			this.ajaxForm(data, url);
		}

		ajaxForm(data, url) {
			(new FetchRequest())
				.setUrl(url)
				.setBody(data)
				.execute()
				.then((data) => {
					(new window.FlashMessage()).execute({ type: 'success', message: data });
				})
				.catch((data) => {
					(new FlashMessage()).execute({ type: 'error', message: data });
				});
		}

		showForm() {
			document.querySelector("#post_message_form").classList.toggle('show');
		}
	}

	return Post;
});