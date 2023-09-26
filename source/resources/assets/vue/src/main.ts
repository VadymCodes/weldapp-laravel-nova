import Vue from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import VueObserveVisibility from 'vue-observe-visibility';
import * as GmapVue from 'gmap-vue';
import InstantSearch from 'vue-instantsearch';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMapMarkerAlt, faHome, faUser } from '@fortawesome/free-solid-svg-icons';
import { faGoogle, faFacebook } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import VueProgress from 'vue-progress-path';
import 'vue-progress-path/dist/vue-progress-path.css';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import App from './App.vue';
import router from './router';
import store from './store';
import VueGtag from "vue-gtag";
import Hotjar from 'vue-hotjar';
import VueMixpanelBrowser from 'vue-mixpanel-browser';

// Google Analytics & Hotjar & Mixpanel
Vue.use(VueGtag, {
  config: { id: process.env.MIX_VUE_APP_GOOGLE_TRACKING_ID }
}, router);
Vue.use(Hotjar, {
  id: process.env.MIX_VUE_APP_HOTJAR_ID
});
Vue.use(VueMixpanelBrowser, {
  token: process.env.MIX_VUE_APP_MIXPANEL_TOKEN,
  config: {}
});

library.add(faMapMarkerAlt);
library.add(faHome);
library.add(faUser);
library.add(faGoogle);
library.add(faFacebook);
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(InstantSearch);
Vue.use(VueObserveVisibility);
Vue.use(GmapVue, {
  load: {
    key: process.env.MIX_VUE_APP_GOOGLE_PLACES_API_KEY,
    libraries: 'places',
  },
  installComponents: true,
});
Vue.use(VueProgress);

Vue.use(VueToast);

localforage.config({
  driver: localforage.LOCALSTORAGE,
  name: 'weldapp',
});

require('./interceptors');

axios.defaults.baseURL = process.env.MIX_VUE_APP_API_URI;
axios.defaults.withCredentials = true;

Vue.config.productionTip = false;

store.dispatch('auth/user')
  .then(() => {
    new Vue({
      router,
      store,
      render: (h) => h(App),
    }).$mount('#app');
  });
