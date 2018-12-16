import resourceList from './modules/resourcelist';
import flashMessages from './modules/flashMessages';
import impersonation from './modules/impersonation';
import segelModule from './modules/segel';

const app = new Vue({
    el: '#app',
    components: {
        'resourcelist-root': require('./components/ResourceList'),
    }
});