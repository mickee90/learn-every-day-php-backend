requirejs.config({
	enforceDefine: false,
	baseUrl: '/Resources/js',
	paths: {
		ckeditor: 'vendor/ckeditor/ckeditor'
	},
	shim: {
		'datepicker': {
			deps: ['jquery-ui']
		}
	},
	urlArgs: "bust=" + (new Date()).getTime()
});

requirejs(['ckeditor', 'User', 'Post', 'Main', 'Contact', 'Fetch', 'Flash_Message', 'Utils', 'Media'], function(CKEDITOR, User, Post, Main, Contact, FetchRequest, FlashMessage, Utils, Media) {


		let initiated = false;

		const classes = { User, Post, Main, Contact, Media };

		class Core {

			constructor() {
				initiated = this;

				this.$body = document.querySelector("body");
				this.$html = document.querySelector("html");
				this.$js_btn = document.querySelector('.js-btn');

				this.events();

				this.main = new Main();
				this.Media = new Media();
			}

			events() {
				document.addEventListener('click', (e) => {
					if(e.target && e.target.className.includes('js-btn')){
						Core.router(e);
					}
				});

				// if(this.$js_btn) {
				// 	this.$js_btn.addEventListener('click', (e) => {
				// 		Core.router(e);
				// 	});
				// }
			}
			
			static router(e) {
				let $target = e.target,
					task = $target.getAttribute('data-task') || false;

				if(!task)
					return false;

				if(task.includes('.')) {
					let tasks = task.split('.'),
						$class = tasks[0],
						$method = tasks[1];

					if(typeof new classes[$class] === 'object' && typeof (new classes[$class])[$method] === 'function') {
						(new classes[$class])[$method](e);
					}

				} else {
					if(typeof Core[task] === "function") Core[task]();
				}
			}

			static getInstance() {
				if(initiated) return initiated;
				else return new Core();
			}
		}

		Core.getInstance();
});