import Api from '../api';

export default {
    namespaced: true,

    state: {
        items: [],
    },

    mutations: {
        items: function (state, items) {
            state.items = items;
        },

        add: function (state, item) {
            state.items.push({
                id: item.id,
                resource: item.resource,
                user: item.user,
                start: item.start,
                end: item.end,
                status: item.status || 'confirmed',
                editable: HYDROFON.isAdmin || HYDROFON.user === item.user,
                classes: HYDROFON.user === item.user ? ['owner', 'bg-indigo-300'] : []
            });
        },

        replace: function (state, item) {
            let index = state.items.findIndex(function (stored) {
                return stored.id === item.id;
            });

            state.items.splice(index, 1, {
                id: item.id,
                resource: item.resource,
                user: item.user,
                start: item.start,
                end: item.end,
                status: item.status || 'confirmed',
                editable: HYDROFON.isAdmin || HYDROFON.user === item.user,
                classes: HYDROFON.user === item.user ? ['owner', 'bg-indigo-300'] : []
            });
        },

        remove: function (state, id) {
            let index = state.items.findIndex(function (stored) {
                return stored.id === id;
            });

            state.items.splice(index, 1);
        }
    },

    actions: {
        fetch: function (context, parameters) {
            Api.get("bookings", {params: parameters})
                .then(response => {
                    let bookings = response.data.data;

                    bookings.forEach((booking) => {
                        // TODO: User should be stored in Vuex state.
                        booking.editable = HYDROFON.isAdmin || HYDROFON.user === booking.user;
                        booking.classes = HYDROFON.user === booking.user ? ['owner', 'bg-indigo-300'] : [];
                    });

                    context.commit('items', bookings);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        store: function (context, item) {
            let newID = Math.random().toString(36).substring(2);

            context.commit('add', Object.assign({
                id: newID,
                user: HYDROFON.user,
                status: 'updating'
            }, item));

            Api.post("bookings", item)
                .then(response => {
                    context.commit('add', response.data);
                    context.commit('remove', newID);
                })
                .catch(error => {
                    context.commit('remove', newID);

                    // Log error.
                    console.log(error);
                });
        },

        update: function (context, item) {
            context.commit('replace', Object.assign({
                status: 'updating'
            }, item));

            Api.put("bookings/" + item.id, item)
                .then(response => {
                    context.commit('replace', response.data);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        },

        delete: function (context, id) {
            Api.delete("bookings/" + id)
                .then(response => {
                    context.commit('remove', id);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        }
    }
};
