require('./bootstrap');
window.Vue = require('vue')
import router from './router'
import ViewUI from 'view-design';
import store from './store';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);
Vue.component('mainapp',require('./components/mainapp').default)
import common from './common'
import jsonToHtml from './jsonToHtml'
Vue.mixin(common);
Vue.mixin(jsonToHtml);

import Editor from 'vue-editor-js/src/index'

Vue.use(Editor)
const app = new Vue({
    el: '#app',
    router,
    store
})
