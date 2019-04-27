define(function() {

	class User {

		constructor() {
			//this.$create_user_form = document.querySelector('#login_user_form').querySelectorAll('input');
			this.$edit_user_form = document.querySelector('#edit_user_form');
			this.$change_password_form = document.querySelector('#change_password_form');
		}

		loginForm() {
			this.prepareAjaxForm(document.querySelectorAll('.form_input'));
		}

		editForms() {
			this.prepareAjaxForm(document.querySelectorAll('.form_input'));
		}

		changePasswordForm() {
			this.prepareAjaxForm(this.$change_password_form[0]);
		}

		prepareAjaxForm(form, url = window.location.pathname) {
			let inputs = Utils.getInputsByForm(form);

			if(!Utils.validateObjectItems(inputs)) {
				(new window.FlashMessage()).execute({ type: 'error', message: 'Fyll i de obligatoriska fälten.' });
				return false;
			}

			if(inputs.find(input => (input.name === "password_1" || input.name === "password_2"))){
				let password_1 = inputs.find(input => (input.name === "password_1")),
				 	password_2 = inputs.find(input => (input.name === "password_2"));

				if(password_1.value !== password_2.value) {
					(new window.FlashMessage()).execute({ type: 'error', message: 'Lösenorden matchar inte.' });
					return false;
				}
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
					// let call = setInterval(this.scroll, 1);
					// let target = document.querySelector('#msg').offsetTop;
					// document.querySelector('body').scrollTop = target-10;
					(new window.FlashMessage()).execute({ type: 'success', message: data });
				})
				.catch((data) => {
					(new FlashMessage()).execute({ type: 'error', message: data });
				});
		}

		// scroll() {
		// 	let	offset = 0;
		// 	let call;
		//
		// 	if ((offset - document.documentElement.scrollTop) > 0) {
		// 		console.log(offset);
		// 		console.log(document.documentElement.scrollTop);
		// 		document.documentElement.scrollTop += 10
		// 	}
		// 	else if ((offset - document.documentElement.scrollTop) < 0) {
		// 		console.log(offset);
		// 		console.log(document.documentElement.scrollTop);
		// 		document.documentElement.scrollTop -= 10
		// 	}
		// 	else {
		// 		clearInterval(call)
		// 	}
		// };
	}

	return User;
});