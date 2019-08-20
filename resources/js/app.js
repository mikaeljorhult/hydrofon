import Vue from 'vue';

import Segel from 'segel';
import debounce from 'lodash/debounce';

import Api from './api';
import Events from './modules/events';

const app = new Vue({
    el: '#app',

    data: {
        date: null,
        categories: [],
        resources: [],
        bookings: [],
        treeSelected: [],
        updatedResources: new Map()
    },

    computed: {
        expandedCategories: function () {
            return this.categories.filter(category => category.expanded)
        },
        selectedResources: function () {
            return this.resources.filter(resource => this.treeSelected.indexOf(resource.id) > -1)
        }
    },

    provide: function () {
        return {
            treeSelected: this.treeSelected
        }
    },

    methods: {
        initialData: function () {
            this.date = window.HYDROFON.date || new Date().setHours(0, 0, 0, 0) / 1000;

            this.categories = window.HYDROFON.categories || [];
            this.resources = window.HYDROFON.resources || [];
            this.bookings = [];

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

        addBooking: function (booking) {
            this.bookings.push({
                id: booking.id,
                resource: booking.resource,
                user: booking.user,
                start: booking.start,
                end: booking.end,
                status: booking.status || 'confirmed',
                editable: HYDROFON.isAdmin || HYDROFON.user === booking.user,
                classes: HYDROFON.user === booking.user ? ['owner', 'bg-indigo-300'] : []
            });
        },

        replaceBooking: function (id, booking) {
            let index = this.bookings.findIndex(function (stored) {
                return stored.id === id;
            });

            this.bookings.splice(index, 1, {
                id: booking.id,
                resource: booking.resource,
                user: booking.user,
                start: booking.start,
                end: booking.end,
                status: booking.status || 'confirmed',
                editable: HYDROFON.isAdmin || HYDROFON.user === booking.user,
                classes: HYDROFON.user === booking.user ? ['owner', 'bg-indigo-300'] : []
            });
        },

        removeBooking: function (id) {
            let index = this.bookings.findIndex(function (stored) {
                return stored.id === id;
            });

            this.bookings.splice(index, 1);
        },

        fetchBookings: function () {
            // Only make HTTP request if there are selected resources.
            if (this.selectedResources.length > 0) {
                Api.get("bookings", {
                    params: {
                        "resource_id": this.selectedResources.map(resource => resource.id),
                        "filter[between]": this.date + "," + (this.date + 86400),
                        "include": "user"
                    }
                })
                    .then(response => {
                        let bookings = response.data.data;

                        bookings.forEach((booking) => {
                            booking.editable = HYDROFON.isAdmin || HYDROFON.user === booking.user;
                            booking.classes = HYDROFON.user === booking.user ? ['owner', 'bg-indigo-300'] : [];
                        });

                        this.bookings = bookings;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        handleCreateBooking: function (booking) {
            let newID = Math.random().toString(36).substring(2);

            this.addBooking(Object.assign({
                id: newID,
                user: HYDROFON.user,
                status: 'updating'
            }, booking));

            Api.post("bookings", booking)
                .then(response => {
                    this.replaceBooking(newID, response.data);
                })
                .catch(error => {
                    this.removeBooking(newID);

                    // Log error.
                    console.log(error);
                });
        },

        handleUpdateBooking: function (booking) {
            this.replaceBooking(booking.id, Object.assign({
                status: 'updating'
            }, booking));

            Api.put("bookings/" + booking.id, booking)
                .then(response => {
                    this.replaceBooking(booking.id, response.data);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        },

        handleDeleteBooking: function (booking) {
            Api.delete("bookings/" + booking.id)
                .then(response => {
                    this.removeBooking(booking.id);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
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

        Events.$on('date-changed', newDate => {
            this.date = newDate;
            history.pushState(null, null, window.HYDROFON.baseURL + '/calendar/' + new Date(newDate * 1000).toISOString().split('T')[0]);
        });

        // Read initial data.
        this.initialData();
    }
});
