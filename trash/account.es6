define(function() {

	class Account {

		constructor() {

		}

		postLoginForm() {
			let $login_account_form = $("#login_account_form"),
				task = "postLoginForm",
				username = ($login_account_form.find("input[name=username]").val()) ? $login_account_form.find("input[name=username]").val() : '',
				password = ($login_account_form.find("input[name=password]").val()) ? $login_account_form.find("input[name=password]").val() : '',
				url = window.location.pathname;

			if(username !== '' && password !== '') {

				let data = { task, username, password };

				(new AjaxRequest())
					.setUrl(url)
					.setData(data)
					.execute()
					.then((data) => {
						(new window.FlashMessage()).execute({type: 'success', message: data.content});
					})
					.catch((data) => {
						(new window.FlashMessage()).execute({type: 'error', message: data});
					});
			} else {
				console.log("Fyll i båda fälten");
			}
		}

		editForm() {
			let $edit_user_form = $("#edit_user_form"),
				task = "editForm",
				username = ($edit_user_form.find("input[name=username]").val()) ? $edit_user_form.find("input[name=username]").val() : '',
				first_name = ($edit_user_form.find("input[name=first_name]").val()) ? $edit_user_form.find("input[name=first_name]").val() : '',
				last_name = ($edit_user_form.find("input[name=last_name]").val()) ? $edit_user_form.find("input[name=last_name]").val() : '',
				address = ($edit_user_form.find("input[name=address]").val()) ? $edit_user_form.find("input[name=address]").val() : '',
				zip_code = ($edit_user_form.find("input[name=zip_code]").val()) ? $edit_user_form.find("input[name=zip_code]").val() : '',
				city = ($edit_user_form.find("input[name=city]").val()) ? $edit_user_form.find("input[name=city]").val() : '',
				email = ($edit_user_form.find("input[name=email]").val()) ? $edit_user_form.find("input[name=email]").val() : '',
				phone = ($edit_user_form.find("input[name=phone]").val()) ? $edit_user_form.find("input[name=phone]").val() : '',
				password_1 = ($edit_user_form.find("input[name=password_1]").val()) ? $edit_user_form.find("input[name=password_1]").val() : '',
				password_2 = ($edit_user_form.find("input[name=password_2]").val()) ? $edit_user_form.find("input[name=password_2]").val() : '',
				id = ($edit_user_form.find("input[name=id]").val()) ? $edit_user_form.find("input[name=id]").val() : '',
				url = window.location.pathname;

			if((password_1 === '' && password_2 !== '') || (password_1 !== '' && password_2 === '')) {
				alert('Fyll i båda lösenorden');
				return false;
			}

			if(password_1 !== password_2) {
				alert('Lösenorden matchar inte');
				return false;
			}

			if(!username || !first_name || !last_name || !address || !zip_code || !city || !email || !id) {
				alert('Fyll i de obligatoriska fälten');
				return false;
			}

			console.log(username, first_name, last_name, address, zip_code, city, email, phone, password_1, password_1, id);

			let data = {
				task,
				username,
				first_name,
				last_name,
				address,
				zip_code,
				city,
				email,
				phone,
				password_1,
				password_2,
				id
			};

			(new AjaxRequest())
				.setUrl(url)
				.setData(data)
				.execute()
				.then((data) => {
					(new window.FlashMessage()).execute({type: 'success', message: data.content});
				})
				.catch((data) => {
					(new window.FlashMessage()).execute({type: 'error', message: data});
				});
		}
	}

	return Account;
});