/*
	Good to know:
	Do not add content-type: application/json if you send files,
	or want to receive the data with $_POST.
	The fetch API will automatically add the correct content-type


*/

class FetchRequest {

	constructor() {
		this.base_url = 'http://127.0.0.1:8888';
		this.url = window.location.pathname;
		this.method = 'POST';
		this.dataType = 'json';
		this.mode = 'same-origin';
		this.credentials = 'same-origin';
		this.body = {};
		this.headers = {'X-Requested-With': 'XMLHttpRequest'};
	}

	setMethod(method) {
		this.method = method;
		return this;
	}

	setUrl(url) {
		this.url = url;
		return this;
	}

	setBody(body = {}) {
		this.body = body;
		return this;
	}

	setMode(mode) {
		this.mode = mode;
		return this;
	}

	setCredentials(credentials) {
		this.credentials = credentials;
		return this;
	}

	setHeaders(headers = {}) {
		this.headers = headers;
		return this;
	}

	addHeader(header = {}) {
		this.headers = {...this.headers, ...header};
		return this;
	}

	getOptions() {
		return {
			method: this.method,
			body: this.body,
			mode: this.mode,
			credentials: this.credentials,
			headers: this.headers
		}
	}

	execute() {

		return new Promise((resolve, reject) => {
			fetch(this.base_url + this.url, this.getOptions())
				.then(response => this.checkStatus(response))
				.then((json) => {

					if(!json.ok) {
						if(json.status === 301) {
							window.location.href = json.content;
							return false;
						} else {
							console.log(json);return false;
							reject(json.content);
						}
					}
					this.checkRedirect(json);
					resolve(json.content);
				})
				.catch(error => reject(error));
		});

	}

	checkRedirect(data) {
		if(data.redirect !== void 0 && data.redirect !== '') {
			window.location.href = this.base_url + data.redirect;
			return false;
		}
	}

	checkStatus(response) {
		return new Promise((resolve) => response.json()
			.then((json) => resolve({
				status: response.status,
				ok: response.ok,
				content: json.content,
				redirect: json.redirect,
				json
			})
		));
	}

	static prepareFormDataFromObjects(inputs) {
		let data = new FormData();
		for(const input of inputs) {
			data.append(input.name, input.value);
		}
		return data;
	}

	static prepareDataFromObjects(inputs) {
		let data = {};
		for(const input of inputs) {
			data[input.name] = input.value;
		}
		return data;
	}
}

window.FetchRequest = FetchRequest;