import Alpine from 'alpinejs';
import ResourceTree from './components/resourceTree';
import Segel from './components/segel';
Alpine.data('resourceTree', ResourceTree);
Alpine.data('segel', Segel);
window.Alpine = Alpine;
Alpine.start();

import interact from 'interactjs';
window.interact = interact;

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;
