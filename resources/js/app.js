import Alpine from 'alpinejs';
import ResourceTree from './components/resourceTree';
import SegelComponent from './components/segel';
Alpine.data('resourceTree', ResourceTree);
Alpine.data('segel', SegelComponent);
window.Alpine = Alpine;
Alpine.start();

import interact from 'interactjs';
window.interact = interact;

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

import Segel from './segel';
