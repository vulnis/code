
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
    require('moment');
    require('flatpickr');
    //require("flatpickr/dist/l10n/af.js");
    require("flatpickr/dist/l10n/ar.js");
    require("flatpickr/dist/l10n/bg.js");
    //require("flatpickr/dist/l10n/bp.js");
    //require("flatpickr/dist/l10n/ca.js");
    require("flatpickr/dist/l10n/cs.js");
    require("flatpickr/dist/l10n/da.js");
    require("flatpickr/dist/l10n/de.js");
    //require("flatpickr/dist/l10n/el.js");
    require("flatpickr/dist/l10n/es.js");
    require("flatpickr/dist/l10n/fi.js");
    require("flatpickr/dist/l10n/fr.js");
    require("flatpickr/dist/l10n/he.js");
    require("flatpickr/dist/l10n/hi.js");
    require("flatpickr/dist/l10n/hu.js");
    require("flatpickr/dist/l10n/it.js");
    require("flatpickr/dist/l10n/ja.js");
    require("flatpickr/dist/l10n/ko.js");
    require("flatpickr/dist/l10n/nl.js");
    require("flatpickr/dist/l10n/no.js");
    require("flatpickr/dist/l10n/pl.js");
    require("flatpickr/dist/l10n/pt.js");
    require("flatpickr/dist/l10n/ro.js");
    require("flatpickr/dist/l10n/ru.js");
    require("flatpickr/dist/l10n/sk.js");
    require("flatpickr/dist/l10n/sr.js");
    require("flatpickr/dist/l10n/sv.js");
    require("flatpickr/dist/l10n/tr.js");
    require("flatpickr/dist/l10n/uk.js");
    //require("flatpickr/dist/l10n/vi.js");
    require("flatpickr/dist/l10n/zh.js");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
