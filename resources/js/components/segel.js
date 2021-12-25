import interact from 'interactjs';
import Grid from '../segel/grid';
import Interactions from '../segel/interactions';

export default (initialState) => ({
    start: initialState.start,
    duration: initialState.duration,
    steps: initialState.steps,

    current: 0,

    init() {
        setInterval(() => {
            this.current = Math.round(
                (new Date().getTime() - (new Date().getTimezoneOffset() * 60 * 1000))
                / 1000
            );
        }, 1000);

        this.setupTimestamps();
        this.calculateGrid();
        this.setupInteractions();

        this.$watch('start, duration, steps', this.setupTimestamps);
    },

    base: {
        ['x-on:resize.window.debounce.500']() {
            this.handleResize();
        }
    },

    setupTimestamps() {
        Interactions.steps = this.steps;
        Interactions.timestamps = {
            start: this.start,
            duration: this.duration,
        };
    },

    setupInteractions() {
        this.$el.querySelectorAll('.segel-resource').forEach(Interactions.resource);
        this.$el.querySelectorAll('.segel-booking').forEach(Interactions.booking);
    },

    calculateGrid() {
        Interactions.grid = Grid.create(this.$el.clientWidth, 41, this.steps);
        Interactions.size = {
            min: {
                width: this.$el.clientWidth / this.steps,
                height: 1
            },
            max: {
                width: this.$el.clientWidth,
                height: 41
            }
        };
    },

    handleResize() {
        this.calculateGrid();

        let bookings = this.$el.querySelectorAll('.segel-booking');

        for (const booking of bookings) {
            if (!interact.isSet(booking)) {
                continue;
            }

            let draggable = interact(booking).draggable();
            let resizable = interact(booking).resizable();

            draggable.modifiers[1].options.targets = Interactions.grid;
            resizable.modifiers[2].options.targets = Interactions.grid;
            resizable.modifiers[1].options.min = Interactions.size.min;
            resizable.modifiers[1].options.max = Interactions.size.max;
        }
    }
})
