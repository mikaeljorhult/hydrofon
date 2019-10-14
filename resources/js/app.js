import Vue from 'vue';

import Segel from 'segel';
import debounce from 'lodash/debounce';

import Events from './modules/events';
import Store from './store';

const app = new Vue({
    el: '#app',

    data: {
        categories: [],
        resources: [],
        treeSelected: [],
        updatedResources: new Map()
    },

    computed: {
        date: function () {
            return this.$store.state.date;
        },
        expandedCategories: function () {
            return this.categories.filter(category => category.expanded)
        },
        selectedResources: function () {
            return this.resources.filter(resource => this.treeSelected.indexOf(resource.id) > -1)
        }
    },

    store: Store,

    provide: function () {
        return {
            treeSelected: this.treeSelected
        }
    },

    methods: {
        initialData: function () {
            this.$store.commit('setDate', window.HYDROFON.date || new Date().setHours(0, 0, 0, 0) / 1000);

            this.categories = window.HYDROFON.categories || [];
            this.resources = window.HYDROFON.resources || [];

            let expandedCategories = [];

            if (sessionStorage.getItem('categories-expanded')) {
                expandedCategories = JSON.parse(sessionStorage.getItem('categories-expanded'));
            }

            this.categories.forEach(category => category.expanded = expandedCategories.indexOf(category.id) > -1);

            let selectedResources = [];

            if (window.HYDROFON.selectedResources && window.HYDROFON.selectedResources.length > 0) {
                selectedResources = window.HYDROFON.selectedResources;
            } else if (sessionStorage.getItem('resources-selected')) {
                selectedResources = JSON.parse(sessionStorage.getItem('resources-selected'));
            }

            Events.$emit('resources-selected', selectedResources.map(function (id) {
                return { id: id, selected: true };
            }));
        },

        fetchBookings: function () {
            // Only make HTTP request if there are selected resources.
            if (this.selectedResources.length > 0) {
                this.$store.dispatch('bookings/fetch', {
                    "resource_id": this.selectedResources.map(resource => resource.id),
                    "filter[between]": this.$store.state.date + "," + (this.$store.state.date + 86400),
                    "include": "user"
                });
            }
        },

        handleCreateBooking: function (booking) {
            this.$store.dispatch('bookings/store', booking);
        },

        handleUpdateBooking: function (booking) {
            this.$store.dispatch('bookings/update', booking);
        },

        handleDeleteBooking: function (booking) {
            this.$store.dispatch('bookings/delete', booking.id);
        },

        updateSelectedResources: debounce(function () {
            this.updatedResources.forEach((value, key) => {
                if (value) {
                    this.treeSelected.push(key);
                } else {
                    let i = this.treeSelected.length;

                    while(i--) {
                        if (this.treeSelected[i] === key) {
                            this.treeSelected.splice(i, 1);
                        }
                    }
                }
            });

            // Clear map when all resources have been updated.
            this.updatedResources.clear();
        }, 1250)
    },

    watch: {
        date: function () {
            // Update bookings whenever date is changed.
            this.fetchBookings();
        },
        expandedCategories: function () {
            sessionStorage.setItem('categories-expanded', JSON.stringify(this.expandedCategories.map(category => category.id)));
        },
        selectedResources: function () {
            // Update bookings whenever resources are updated.
            this.fetchBookings();

            sessionStorage.setItem('resources-selected', JSON.stringify(this.treeSelected));
        }
    },

    components: {
        'segel': Segel,
        'table-groups': require('./components/tables/GroupsTable').default,
        'calendar-header': require('./components/CalendarHeader').default,
        'resourcelist-root': require('./components/ResourceList').default,
        'topbar-impersonation': require('./components/TopbarImpersonation').default,
    },

    created: function () {
        Events.$on('resources-selected', resources => {
            resources.forEach((resource) => {
                this.updatedResources.set(resource.id, resource.selected);
            });

            this.updateSelectedResources();
        });

        Events.$on('categories-expanded', category => {
            let index = this.categories.findIndex(function (stored) {
                return stored.id === category.id;
            });

            // Replace object with copy of object with new status.
            this.$set(this.categories, index, Object.assign(this.categories[index], {
                expanded: category.expanded
            }));
        });

        // Read initial data.
        this.initialData();
    }
});
