// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import VueResource from 'vue-resource';
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);
Vue.use(VueResource);
Vue.config.productionTip = false

var shared = new Vue(
  {
      data: {
          user_data: {
              username: '',
              full_name: '',
              id_user: ''
          },
          access_data: {
              access_token: '',
              expires_in: '',
              refresh_token: '',
              scope: '',
              token_type: ''
          },
          error_access: true
      }
  })
shared.install = function () {
    Object.defineProperty(Vue.prototype, '$globalVariables', {
        get()
        {
            return shared
        }
    })
}
Vue.use(shared);

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    components: {App},
    template: '<App/>',
})
