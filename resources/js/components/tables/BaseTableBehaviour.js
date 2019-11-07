export default {
    components: {
        'table-base': require('./BaseTable').default,
    },

    props: {
        items: {
            type: Array,
            required: false,
            default: function () {
                return [];
            },
        },
        sort: {
            type: String,
            required: false,
        }
    },

    reactiveProvide: {
        name: 'relationships',
        include: [],
    },

    methods: {
        onDelete: function (ids) {
            ids.forEach((id) => {
                this.$store.dispatch(this.type + '/delete', id);
            });
        },

        onEdit: function (ids) {
            this.editItem = ids[0];
        },

        onSave: function (item) {
            this.isSaving = true;

            this.$store.dispatch(this.type + '/update', item).then(() => {
                this.isSaving = false;
                this.editItem = 0;
            });
        },

        onCancel: function () {
            this.editItem = 0;
        },
    },

    created: function () {
        if (this.items.length > 0) {
            this.$store.commit(this.type + '/items', this.items);
        }
    },
};
