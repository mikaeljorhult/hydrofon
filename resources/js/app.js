import debounce from 'lodash/debounce';
import Events from './modules/events';

import flashMessages from './modules/flashMessages';
import impersonation from './modules/impersonation';

const app = new Vue({
    el: '#app',

    data: {
        date: null,
        categories: [],
        resources: [],
        bookings: [],
        updatedResources: new Map()
    },

    computed: {
        selectedResources: function() {
            return this.resources.filter(resources => resources.selected)
        }
    },

    methods: {
        fetchData: function() {
            this.date = window.HYDROFON.date || new Date().setHours(0, 0, 0, 0) / 1000;

            this.categories = window.HYDROFON.categories || [];
            this.resources = window.HYDROFON.resources || [];
            this.bookings = [];

            this.categories.forEach(category => category.expanded = false);
        },

        updateSelectedResources: debounce(function() {
            this.updatedResources.forEach((value, key) => {
                // Find index of updated booking.
                let index = this.resources.findIndex(function(stored) {
                    return stored.id === key;
                });

                // Replace object with copy of object with new status.
                this.$set(this.resources, index, Object.assign(this.resources[index], {
                    selected: value
                }));
            });
        }, 1250)
    },

    components: {
        'resourcelist-root': require('./components/ResourceList').default,
    },

    created: function() {
        this.fetchData();

        Events.$on('resources-selected', event => {
            this.updatedResources.set(event.id, event.selected);
            this.updateSelectedResources();
        });

        Events.$on('date-changed', newDate => {
            this.date = newDate
        });
    }
});