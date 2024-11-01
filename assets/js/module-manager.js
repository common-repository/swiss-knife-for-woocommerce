( function( $ ) {

	const skw_modules = () => {

		const form = document.querySelector('#skw-module-manager');

		if(form === null){
			return;
		}

		const modules = document.querySelectorAll('.skw-module-action');

		modules.forEach( module => {
            module.addEventListener('click', function(e){

				e.preventDefault();

				const cta = e.target;
				const module_id = e.target.dataset.moduleid;
				const module_action = e.target.dataset.action;

				cta.classList.add('updating');

				const data = {
					module_id,
					module_action,
					'action' : 'skw_module_manager',
					'_wpnonce' : skw_module.skw_mm_nonce
				}
				// Run ajax

				$.ajax({
					url: skw_module.ajax_url,
					method: 'post',
					data,
					success: function(res){

						const modules = res.modules;

						for(module in modules){

							let module_anchor = document.querySelector(`[data-moduleid='${module}']`);
							let status = modules[module]['active'];

							if(status == 1){

								module_anchor.textContent = 'Deactivate';
								module_anchor.dataset.action = 'deactivate';
								module_anchor.parentElement.parentElement.classList.remove('skw-disabled');
                                module_anchor.parentElement.parentElement.classList.add('skw-enabled');

							}else{

								module_anchor.textContent = 'Activate';
								module_anchor.dataset.action = 'activate';
								module_anchor.parentElement.parentElement.classList.remove('skw-enabled');
                                module_anchor.parentElement.parentElement.classList.add('skw-disabled');

							}

							module_anchor.classList.remove('updating');

						}
					}
				});



			});
		});



	}


	document.addEventListener('DOMContentLoaded', function () {
		skw_modules();
	});


} )( jQuery );
