define(function() {

	class Media {

		constructor() {
			this.$image_uploader = document.querySelector("#image_uploader");
			this.image_list = document.querySelector("#image_list");

			this.events();
		}

		events() {
			if(this.$image_uploader !== null)
				this.$image_uploader.addEventListener("change", (e) => { this.uploadImages(e); });

		}

		uploadImages(e) {
			let $target = e.target,
				file_list = $target.files,
				data = new FormData();

			if(file_list.length === 0)
				return false;

			for(let $file of file_list) {
				data.append('files[]', $file);
			}

			this.ajaxForm(data, '/media/upload', 'appendData');
		}

		appendData(data) {
			data.forEach((media) => {
				let new_media_input = "<div class='image_item'>" +
					"<div class='image'><img src='" + media.url + "' alt='" + media.nice_name + "' /></div>" +
					"<input class='form_input' type='hidden' name='images[]' value='" + media.id + "' />" +
					"<div class='btn_list link_btn js-btn' data-task='Media.remove'>Ta bort</div>" +
					"</div>";
				this.image_list.innerHTML = this.image_list.innerHTML + new_media_input;
			});

		}

		remove(e) {
			e.target.parentNode.remove();
		}

		ajaxForm(data, url, method) {
			(new FetchRequest())
				.setUrl(url)
				.setBody(data)
				.execute()
				.then((data) => {
					(new Media)[method](data);
				})
				.catch((data) => {
					(new window.FlashMessage()).execute({ type: 'error', message: data });
				});
		}

	}

	return Media;

});