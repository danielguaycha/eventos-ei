import Vue from 'vue';
import Toasted from 'vue-toasted';

Vue.use(Toasted, {
    theme: "bubble",
    position: "bottom-right",
    duration: 3500,
    iconPack: 'mdi',
    className: "alert-family",
    closeOnSwipe: true
});

Vue.prototype.$alert = {
    err(message){
        Vue.toasted.error(message, {icon: 'alert-circle'})
    },
    ok(message){
        Vue.toasted.success(message, {icon: 'feather'})
    }
};

window.toast = Vue.toasted;
