requirejs.config({
	baseUrl: '/Resources/js',
	paths: {
		jquery: 'vendor/jquery',
		jquerymigrate: 'vendor/jquery-migrate',
		ckeditor: 'vendor/ckeditor/ckeditor'
	}
});

requirejs(['jquery', 'account', 'post', 'main'], function($, Account, Post, Main) {

	$(function() {

		let initiated = false;

		class Core {

			constructor() {
				initiated = this;

				this.$body = $("body");
				this.$html = $("html");

				this.events();
			}

			events() {
				this.$body.on('click', '.js-btn', function(e) {
					let $target = $(e.currentTarget),
						task = $target.data('task') || '';


					//Account.getInstance();

					let $account = new Account();
					console.log($account);
					//Account.helloWorld();

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

			static getInstance() {
				if(initiated) return initiated;
				else return new Core();
			}
		}

		Core.getInstance();
	});
});