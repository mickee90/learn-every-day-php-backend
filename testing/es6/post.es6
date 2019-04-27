define(function() {

	class Post {

		constructor() {
			this.events();
		}

		helloWorld() {
			console.log('Hello World Post');
		}

		events() {
			let self = this;

			$("body").on('click', ".js-btn", function(e) {
				let method = $(this).attr('data-method');
				self[method].call();
			});
		}

		showPostForm() {
			$("#post_form").show();
		}
	}

	return Post;
});