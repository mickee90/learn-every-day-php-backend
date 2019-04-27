class AjaxRequest {

	constructor() {
		this.base_url = 'http://127.0.0.1:8888/';
		this.url = window.location.pathname;
		this.type = 'POST';
		this.dataType = 'json';
		this.contentType = true;
		this.processData = true;
		this.data = {};
	}

	setType(type) {
		this.type = type;
		return this;
	}

	setUrl(url) {
		this.url = url;
		return this;
	}

	setContentType(type) {
		this.contentType = type;
		return this;
	}

	setProcessData(data) {
		this.processData = data;
		return this;
	}

	setData(data = {}) {
		this.data = data;
		return this;
	}

	getOptions() {
		return {
			url: this.base_url + this.url,
			type: this.type,
			dataType: this.dataType,
			contentType: this.contentType,
			processData: this.processData,
			data: this.data
		}
	}

	execute() {
		const options = this.getOptions();
		const { type, url, dataType, data, contentType, processData } = options;

		return new Promise((resolve, reject) => {

			$.ajax({
				type, url, data, dataType, contentType, processData,
				headers: {'X-Requested-With': 'XMLHttpRequest'},
				async: false,
				cache: false,
			})
				.done(data => {
					AjaxRequest.redirect(data);

					resolve(data);
				})
				.fail((xhr, status, error) => {

					switch(xhr.status) {
						case 301: {
							window.location.href = xhr.responseJSON.content;
							break;
						}

						default: {
							let error_response = xhr.responseJSON.content || error;
							reject(error_response);
							break;
						}
					}
				})
				.always(() => { });
		});

	}

	static redirect(data) {
		console.log(data);
		if(data.redirect !== void 0 && data.redirect !== '') {
			window.location.href = base_url + data.redirect;
		}
	}

	static prepareDataFromObjects(inputs) {
		let data = {};
		for(const input of inputs) {
			data[input.name] = input.value;
		}
		return data;
	}
}

window.AjaxRequest = AjaxRequest;