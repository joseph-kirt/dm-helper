import axios from 'axios';
import {createApp} from 'vue';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.path = Routing;
window.axios = axios;
window.createMyApp = (options) => {
      const app = createApp(options);
      app.config.globalProperties.$http = axios;
      app.config.globalProperties.$routing = Routing;

      return app;
};

document.addEventListener('DOMContentLoaded', function() {
    // Placeholder for any Javascript to be ran after page load
});
