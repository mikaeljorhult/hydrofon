import Alpine from 'alpinejs';
import ResourceTree from './components/resourceTree';
Alpine.data('resourceTree', ResourceTree);
window.Alpine = Alpine;
Alpine.start();

import interact from 'interactjs';
window.interact = interact;

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

import Segel from './segel';
