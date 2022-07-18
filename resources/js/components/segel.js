import interact from 'interactjs';
import Grid from '../segel/grid';
import Interactions from '../segel/interactions';
import Store from '../store';

export default (initialState) => ({
    start: initialState.start,
    duration: initialState.duration,
    steps: initialState.steps,

    current: 0,
    selected: [],

    rulerElement: null,

    init() {
        this.rulerElement = this.$el.querySelector('.segel-ruler');

        setInterval(() => {
            this.current = Math.round(
                (new Date().getTime() - (new Date().getTimezoneOffset() * 60 * 1000))
                / 1000
            );
        }, 1000);

        this.setupTimestamps();
        this.calculateGrid();
        this.setupInteractions();

        this.$watch('start, duration, steps', this.setupTimestamps.bind(this));

        window.Livewire.hook('message.processed', (message, component) => {
            if (component.name === 'segel') {
                this.setupInteractions();
                this.diffSelected();
            }
        });

        Array.prototype.forEach.call(this.$el.querySelectorAll('[name="selected[]"]'), checkbox => {
            checkbox.checked = false;
        })

        Store.initialized = true;
    },

    base: {
        ['x-on:resize.window.debounce.500']() {
            this.handleResize();
        },
        ['x-on:segel-setexpanded.window'](event) {
            this.$wire.setExpanded(event.detail);
        },
        ['x-on:segel-setresources.window'](event) {
            this.$wire.setResources(event.detail);
        },
        ['x-on:segel-settimestamps.window'](event) {
            this.$wire.setTimestamps(event.detail.start, event.detail.end, event.detail.duration);
        }
    },

    selector: {
        ['x-on:click']() {
            if (this.$el.checked) {
                this.selected.push(this.$el.value);
            } else {
                this.selected.splice(this.selected.indexOf(this.$el.value), 1);
            }
        },
        ['x-bind:checked']() {
            return this.selected.indexOf(this.$el.value) > -1;
        },
    },

    diffSelected() {
        let checkboxes = this.$el.querySelectorAll('[name="selected[]"]');
        let available = Array.from(checkboxes).map(checkbox => {
            return checkbox.value;
        });

        this.selected = this.selected.filter(value => available.includes(value));
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
        let gridOffset = window
            .getComputedStyle(this.rulerElement, null)
            .getPropertyValue('padding-left')
            .replace('px', '');

        let gridWidth = this.$el.clientWidth - parseInt(gridOffset);

        Interactions.grid = Grid.create(gridWidth, 44, this.steps);
        Interactions.size = {
            min: {
                width: gridWidth / this.steps,
                height: 1
            },
            max: {
                width: gridWidth,
                height: 44
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
