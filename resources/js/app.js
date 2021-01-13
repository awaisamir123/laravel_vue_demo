import Vue from 'vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

//moment for date formatting


import {routes} from './routes';

//Register Routes
const router = new VueRouter({mode: 'history', routes: routes});
Vue.router = router;

//Import main component
import App from './components/App';

//Register Main Component
new Vue(Vue.util.extend({router}, App)).$mount('#app');
