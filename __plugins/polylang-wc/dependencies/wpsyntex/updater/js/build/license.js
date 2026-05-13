const pllSettingsLicenses = {
	/**
	 * Init.
	 */
	init: () => {
		if (document.readyState !== 'loading') {
			pllSettingsLicenses.ready();
		} else {
			document.addEventListener('DOMContentLoaded', pllSettingsLicenses.ready);
		}
	},
	ready: () => {
		wp.hooks.addAction('pll_settings_saved', 'polylang-updater', pllSettingsLicenses.manageLicenses.onUpdate);
		pllSettingsLicenses.manageLicenses.display();
	},
	manageLicenses: {
		display: () => {
			const table = document.getElementById('pllu-licenses-table');
			if (!table) {
				return;
			}
			const action = table.getAttribute('data-action');
			const nonce = table.getAttribute('data-nonce');
			if (!action || !nonce) {
				return;
			}
			const urlParams = {
				action,
				_pll_nonce: nonce,
				pll_ajax_settings: 1
			};
			const url = wp.url.addQueryArgs(ajaxurl, urlParams);
			fetch(url).then(response => {
				return response.json();
			}).then(json => {
				if (!json.success) {
					return;
				}
				json.data.row.forEach(item => {
					table.insertAdjacentHTML('beforeend', item);
				});
				pllSettingsLicenses.manageLicenses.attachDeactivationEvent();
			});
		},
		attachDeactivationEvent: () => {
			let table = document.querySelector('#pllu-licenses-table');
			if (!table) {
				return;
			}
			table.addEventListener('click', el => {
				if (!el.target.classList.contains('pllu-deactivate-license')) {
					return;
				}
				const data = new FormData();
				data.set('action', 'pllu_deactivate_license');
				data.set('pll_ajax_settings', 1);
				data.set('id', el.target.id);
				data.set('_pll_nonce', document.querySelector('#_pll_nonce').value);

				// POST request using fetch().
				fetch(ajaxurl, {
					method: "POST",
					body: data
				}).then(response => {
					return response.json();
				}).then(json => {
					if (!json.success) {
						return;
					}

					// Data comes from `License::get_form_field()`, where everything is escaped.
					const license = document.querySelector('#pllu-license-' + json.data.id);
					if (license) {
						license.outerHTML = json.data.html;
					}
				});
			});
		},
		onUpdate: response => {
			if ('pllu-license-update' === response.what) {
				const license = document.querySelector(`#pllu-license-${response.data}`);
				if (license) {
					license.outerHTML = response.supplemental.html;
				}
			}
		}
	}
};
pllSettingsLicenses.init();
