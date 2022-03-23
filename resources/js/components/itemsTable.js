export default (initialState) => ({
    init() {
        this.$wire.on('editing', () => {
            this.$el.querySelector('.is-editing').querySelector('select, input').focus();
        });
    },
})
