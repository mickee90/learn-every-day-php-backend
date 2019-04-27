class Utils {

	constructor() {
		console.log('Utils');
	}

	static validateItems(items) {
		return items.every(item => (item));
	}

	static validateObjectItems(items) {
		for(const item of items) {
			if(item.required && !item.value) return false;
		}
		return true;
	}

	static prepareInputs(inputs) {
		return [...inputs].map((input) => {
			let value = (input['id'] && input['id'] === 'ck_editor') ? CKEDITOR.instances.ck_editor.getData() : input['value'];
			return {
				name: input['name'],
				value: value,
				required: input['required']
			}
		});
	}

	static getInputsByForm(form) {
		return [...form].map((input) => {
			let value = (input['id'] && input['id'] === 'ck_editor') ? CKEDITOR.instances.ck_editor.getData() : input['value'];
			return {
				name: input['name'],
				value: value,
				required: input['required']
			}
		});
	}

	static setStyleOnElms(elements, style_name, style_value) {
		elements.forEach(elm => elm.style[style_name] = style_value);
		return this;
	}

	static addClass(element, class_name) {
		if(NodeList.prototype.isPrototypeOf(element)) {
			element.forEach(elem => elem.classList.add(class_name));
		} else if(HTMLCollection.prototype.isPrototypeOf(element)) {
			console.log('htmlcollector');
		} else {
			element.classList.add(class_name);
		}
		return this;
	}

	static removeClass(element, class_name) {
		if(NodeList.prototype.isPrototypeOf(element)) {
			element.forEach(elem => elem.classList.remove(class_name));
		} else if(HTMLCollection.prototype.isPrototypeOf(element)) {
			console.log('htmlcollector');
		} else {
			element.classList.remove(class_name);
		}
		return this;
	}

	static toggleClass(element, class_name) {
		if(NodeList.prototype.isPrototypeOf(element)) {
			element.forEach(elem => elem.classList.toggle(class_name));
		} else if(HTMLCollection.prototype.isPrototypeOf(element)) {
			console.log('htmlcollector');
		} else {
			element.classList.toggle(class_name);
		}
		return this;
	}

	/*
	static getInputsByForm(form) {
		console.log('hej');
		return [...form.elements].map((input) => {
			let value = (input['id'] && input['id'] === 'ck_editor') ? CKEDITOR.instances.ck_editor.getData() : input['value'];
			return {
				name: input['name'],
				value: value,
				required: input['required']
			}
		});
		console.log('hej1');
	}
*/
}

window.Utils = Utils;