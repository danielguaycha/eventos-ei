window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('./configs');
} catch (e) {}
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {

    if(!error.response) {
        return Promise.reject("Error desconocido, contacte con soporte!");
    }

    const { response: { status }} = error;
    if(status === 401) {
        // para sacar al usuario de la vista actual y mandarlo al login
        if (secureStorage.getItem(sysConfig.getSlug()) !== null) {
            secureStorage.removeItem(sysConfig.getSlug());
            //Vue.auth.destroyToken(); //destroy token
            window.location.href = "/";
        }
        return Promise.reject("No autorizado");
    } else {
        let err = "";
        const {response: {data}} = error;

        if(data.error || data.errors) {
            let errors = data.error;
            if (data.error) {
                errors = data.error;
            }
            if (data.errors) {
                errors = data.errors;
            }

            if (Array.isArray(errors)) { // si es un array de errores
                if(errors.length === 0) {
                    if(data.message) {
                        err = data.message;
                    } else {
                        err = 'Error #001 desconocido contacte con soporte!';
                    }
                }
                else if(errors.length > 0 && errors.length <=2) {
                    err = [...new Set(errors)].join(', ');
                } else {
                    err = errors[0];
                }
            } else {
                err = errors;
            }
        } else {
            if (data.message) {
                err = data.message;
            } else {
                err = "Error #002 desconocido contacte con soporte!";
            }
        }
        return Promise.reject(err);
    }

});

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
