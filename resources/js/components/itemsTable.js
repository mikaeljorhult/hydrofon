export default (initialState) => ({
    selectedRows: initialState.selectedRows,

    init() {
        this.$wire.on('editing', () => {
            this.$el.querySelector('.is-editing').querySelector('select, input').focus();
        });

        this.$refs.selectall.checked = false;

        this.$watch('selectedRows', (newValue) => {
            let checkboxes = this.$root.querySelectorAll('[name="selected[]"]');
            this.$refs.selectall.indeterminate = newValue.length > 0 && newValue.length !== checkboxes.length;
        })
    },

    selectall: {
        ['x-on:click']() {
            if (this.$el.checked) {
                let checkboxes = this.$root.querySelectorAll('[name="selected[]"]');
                this.selectedRows = Array.from(checkboxes).map(checkbox => checkbox.value);
            } else {
                this.selectedRows = [];
            }
        },
    },
})
