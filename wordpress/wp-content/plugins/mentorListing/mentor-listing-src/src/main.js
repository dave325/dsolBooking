import Vue from 'vue'
import App from './App.vue'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'
import 'bootstrap/dist/css/bootstrap.css'
import router from './router'
import moment from 'moment'
//const renderer = require('vue-server-renderer').createRenderer()
//this allows moment to be called from vue components 
Vue.prototype.moment = moment;

Vue.config.productionTip = false
Vue.use(VueMaterial)

//console.log(localized);
var vm = new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
