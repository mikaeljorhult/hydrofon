export default (initialState) => ({
    start: initialState.start,
    duration: initialState.duration,

    current: null,

    init() {
        setInterval(() => {
            this.current = Math.round(
                (new Date().getTime() - (new Date().getTimezoneOffset() * 60 * 1000))
                / 1000
            );
        }, 1000);
    },

    base: {
        ['x-on:resize.window.debounce.500']() {
            window.HYDROFON.Segel.handleResize();
        }
    }
})
