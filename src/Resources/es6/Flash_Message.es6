class FlashMessage {

	constructor() {
		this.flash_message = '';
		this.messages = [];
	}

	setMessages(messages = []) {
		this.messages = messages;
		return this;
	}

	addMessage(message) {
		this.messages = this.messages.push(message);
		return this;
	}

	execute(quick_message = '') {
		let messages = (quick_message) ? quick_message : this.messages;

		if(!Array.isArray(messages))
			messages = [messages];

		for(let message of messages) {
			this.flash_message += `<div class='flash_message flash_message_${message.type}'>${message.message}</div>`;
		}

		document.querySelector("#msg").innerHTML = this.flash_message;
		this.scrollToSmoothly(0, 10);
	}

	scrollToSmoothly(pos, time) {
		let currentPos = window.scrollY || window.screenTop;
		if(currentPos < pos) {
			let t = 10;
			for(let i = currentPos; i <= pos; i += 10) {
				t += 10;
				setTimeout(function() {
					window.scrollTo(0, i);
				}, t / 2);
			}
		} else {
			time = time || 2;
			let i = currentPos;
			let x;
			x = setInterval(function() {
				window.scrollTo(0, i);
				i -= 10;
				if(i <= pos) {
					clearInterval(x);
				}
			}, time);
		}
	}

}

window.FlashMessage = FlashMessage;