define(function() {

	class Contact {

		constructor() {
			this.events();
		}

		events() {

		}

		sendForm() {
			this.prepareAjaxForm(document.querySelector("#contact_form")[0]);
		}

		prepareAjaxForm(form, url = window.location.pathname) {
			let inputs = Utils.getInputsByForm(form);

			if(!Utils.validateObjectItems(inputs)) {
				(new window.FlashMessage()).execute({ type: 'error', message: 'Fyll i de obligatoriska fälten.' });
				return false;
			}

			const data = FetchRequest.prepareDataFromObjects(inputs);
			this.ajaxForm(data, url);
		}

		ajaxForm(data, url) {
			(new FetchRequest())
				.setUrl(url)
				.setData(data)
				.execute()
				.then((data) => {
					(new window.FlashMessage()).execute({ type: 'success', message: data.content });
				})
				.catch((data) => {
					(new window.FlashMessage()).execute({ type: 'error', message: data });
				});
		}
	}

	return Contact;
});