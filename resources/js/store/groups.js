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
                name: item.name,
            });
        },

        replace: function (state, item) {
            let index = state.items.findIndex(function (stored) {
                return stored.id === item.id;
            });

            state.items.splice(index, 1, {
                id: item.id,
                name: item.name,
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
            Api.get("groups", {params: parameters})
                .then(response => {
                    context.commit('items', response.data.data);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        store: function (context, item) {
            Api.post("groups", item)
                .then(response => {
                    context.commit('add', response.data);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        },

        update: function (context, item) {
            Api.put("groups/" + item.id, item)
                .then(response => {
                    context.commit('replace', response.data);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        },

        delete: function (context, id) {
            Api.delete("groups/" + id)
                .then(response => {
                    context.commit('remove', id);
                })
                .catch(error => {
                    // Log error.
                    console.log(error);
                });
        },
    },
};
