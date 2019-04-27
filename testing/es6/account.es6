define(function() {

	let initiated = false;

	class Account {

		constructor() {
			initiated = this;

			console.log('hi Account');
			this.helloWorld();
		}

		helloWorld() {
			let $html = $(html);

			console.log($html);
			return 'yas';

		}

		postLoginForm() {
			let $login_account_form = $("#login_account_form"),
				task = "postLoginForm",
				username = ($login_account_form.find("input[name=username]").val()) ? $login_account_form.find("input[name=username]").val() : '',
				password = ($login_account_form.find("input[name=password]").val()) ? $login_account_form.find("input[name=password]").val() : '',
				url = window.location.pathname;

			if(username !== '' && password !== '') {
				$.ajax({
					type: "POST",
					url: url,
					async: false,
					dataType: "json",
					headers: { "X-Request-With": "XMLHttpRequest" },
					data: {
						task,
						username,
						password,
					},
					cache: false,
					success: function(data) {
						if(data['redirect'] !== '') {
							window.location.href = data['redirect'];
						}
					},
					error: function(data) {
						console.log('no');
					}
				});
			} else {
				console.log("Fyll i båda fälten");
			}
		}

		static getInstance() {
			if(initiated) return initiated;
			else return new Account();
		}
	}

	return Account;
});