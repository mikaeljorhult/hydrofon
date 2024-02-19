import { Livewire, Alpine } from '../../../vendor/livewire/livewire/dist/livewire.esm';

export default (initialState) => ({
    start: initialState.start,
    duration: initialState.duration,
    steps: initialState.steps,

    current: 0,
    selected: [],

    rulerElement: null,

    drag: {
        type: 'move',
        element: null,
        drop: null,
        grid: {
            offset: 0,
            x: 0,
            y: 0,
        },
        position: {
            x: 0,
            y: 0,
        },
    },

    init() {
        this.rulerElement = this.$el.querySelector('.segel-ruler');

        setInterval(() => {
            this.current = Math.round(
                (new Date().getTime() - (new Date().getTimezoneOffset() * 60 * 1000))
                / 1000
            );
        }, 1000);

        new ResizeObserver(() => {
            this.calculateGrid()
        }).observe(this.$el);

        Livewire.hook('morph.adding', ({ el, component }) => {
            if (component.name === 'segel') {
                this.diffSelected();
            }
        })

        Livewire.hook('morph.removed', ({ el, component }) => {
            if (component.name === 'segel') {
                this.diffSelected();
            }
        })

        Array.prototype.forEach.call(this.$el.querySelectorAll('[name="selected[]"]'), checkbox => {
            checkbox.checked = false;
        })

        Alpine.store('segelInitialized', true)
    },

    snapToGrip(value, gridSize) {
        return gridSize * Math.round(value / gridSize)
    },

    base: {
        ['x-on:booking-created.window'](event) {
            let checkboxes = Array.from(this.$el.querySelectorAll('input[type="checkbox"]'))
                .map(checkbox => checkbox.value);

            if (checkboxes.indexOf(event.detail.resource_id) > -1) {
                this.$wire.$refresh();
            }
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
        let available = Array.from(checkboxes).map(checkbox => checkbox.value);

        this.selected = this.selected.filter(value => available.includes(value));
    },

    calculateGrid() {
        const gridOffset = window
            .getComputedStyle(this.rulerElement, null)
            .getPropertyValue('padding-left')
            .replace('px', '');

        let gridWidth = this.$el.getBoundingClientRect().width - parseInt(gridOffset);

        this.drag.grid = {
            x: gridWidth / this.steps,
            y: 44,
            offset: parseInt(gridOffset),
        }
    },

    resource: {
        ['x-on:dblclick.self']() {
            let position = Math.round(
                (this.$event.offsetX / this.$el.clientWidth)
                * this.duration
            );

            let size = Math.round(
                this.duration
                / this.steps
            );

            let startTime = position + this.start
            let endTime = startTime + size * 2

            this.$root.dispatchEvent(new CustomEvent(
                'createbooking', {
                    detail: {
                        resource_id: [parseInt(this.$el.dataset.id)],
                        start_time: startTime,
                        end_time: endTime,
                    }
                }
            ))
        },

        ['x-on:dragover.prevent.throttle.50ms']() {
            const position = this.snapToGrip(this.drag.position.x + this.$event.clientX, this.drag.grid.x)

            switch (this.drag.type) {
                case 'move':
                    this.drag.element.style.left = position + 'px'
                    break

                case 'resize-left':
                    this.drag.element.parentElement.style.left = (this.drag.initial.x + position) + 'px'
                    this.drag.element.parentElement.style.width = (this.drag.initial.width + (position * -1)) + 'px'
                    break

                case 'resize-right':
                    this.drag.element.parentElement.style.width = (this.drag.initial.width + position) + 'px'
                    break
            }
        },

        ['x-on:dragenter.prevent']() {
            if (this.drag.type !== 'move') {
                return;
            }

            this.$el.classList.add('droppable')
            this.$event.dataTransfer.dropEffect = this.$event.altKey ? 'copy' : 'move'
            this.drag.element.style.top = (this.$el.offsetTop - this.drag.element.closest('[x-bind="resource"]').offsetTop) + 'px'
        },

        ['x-on:dragleave.prevent']() {
            this.$el.classList.remove('droppable')
        },

        ['x-on:drop.prevent']() {
            this.$el.classList.remove('droppable')
            this.drag.drop = this.$el.dataset.id
        },
    },

    booking: {
        ['x-on:dblclick.stop']() {
            this.$root.dispatchEvent(new CustomEvent(
                'deletebooking', {
                    detail: {
                        id: parseInt(this.$el.dataset.id),
                    }
                }
            ))
        },

        ['x-on:dragstart.self']() {
            this.$el.classList.add('is-dragging')

            this.drag.type = 'move'
            this.drag.element = this.$el

            const image = new Image();
            this.$event.dataTransfer.setDragImage(image, 0, 0)
            this.$event.dataTransfer.setData('text', this.$el.dataset.id)
            this.$event.dataTransfer.effectAllowed = 'copyMove'

            this.drag.position.x = this.$el.offsetLeft - this.$event.clientX
            this.drag.position.y = this.$el.offsetTop - this.$event.clientY

            this.drag.initial = {
                x: this.$el.offsetLeft,
                y: 0,
            }

            this.drag.element.style.pointerEvents = 'none'
        },

        ['x-on:dragend.self']() {
            if (this.$event.dataTransfer.dropEffect === 'none') {
                this.$el.style.left = this.drag.initial.x + 'px'
                this.$el.style.top = this.drag.initial.y + 'px'
                return;
            }

            this.$el.classList.remove('is-dragging')
            this.drag.element.style.pointerEvents = 'all'

            let booking = {
                id: parseInt(this.drag.element.dataset.id),
                user_id: parseInt(this.drag.element.dataset.user),
                start_time: parseInt(this.drag.element.dataset.start),
                end_time: parseInt(this.drag.element.dataset.end),
                resource_id: parseInt(this.drag.drop),
            };

            let startTime = Math.round(
                (
                    (this.drag.element.getBoundingClientRect().left - this.$root.getBoundingClientRect().left - this.drag.grid.offset)
                    / (this.$root.clientWidth - this.drag.grid.offset)
                )
                * this.duration
                + this.start
            );

            let endTime = booking.end_time - (booking.start_time - startTime);

            booking.start_time = startTime;
            booking.end_time = endTime;

            this.addProgressBar(this.drag.element)

            this.$root.dispatchEvent(new CustomEvent(
                this.$event.dataTransfer.dropEffect === 'copy'
                    ? 'createbooking'
                    : 'updatebooking', {
                    detail: booking
                }
            ));
        },
    },

    resizeLeft: {
        ['x-on:dragstart.self.stop']() {
            this.$el.parentElement.classList.add('is-resizing')

            this.drag.type = 'resize-left'
            this.drag.element = this.$el

            const image = new Image();
            this.$event.dataTransfer.setDragImage(image, 0, 0)
            this.$event.dataTransfer.effectAllowed = 'move'

            this.drag.position.x = 0 - this.$event.clientX

            this.drag.initial = {
                x: this.$el.parentElement.offsetLeft,
                y: 0,
                width: this.$el.parentElement.getBoundingClientRect().width,
            }
        },

        ['x-on:dragend.self']() {
            if (this.$event.dataTransfer.dropEffect === 'none') {
                this.$el.parentElement.style.left = this.drag.initial.left + 'px'
                this.$el.parentElement.style.width = this.drag.initial.width + 'px'
                return;
            }

            this.$el.classList.remove('is-resizing')

            let booking = {
                id: parseInt(this.drag.element.parentElement.dataset.id),
                user_id: parseInt(this.drag.element.parentElement.dataset.user),
                start_time: parseInt(this.drag.element.parentElement.dataset.start),
                end_time: parseInt(this.drag.element.parentElement.dataset.end),
                resource_id: parseInt(this.drag.element.parentElement.dataset.resource),
            }

            booking.start_time = Math.round(
                (
                    (this.drag.element.parentElement.getBoundingClientRect().left - this.$root.getBoundingClientRect().left - this.drag.grid.offset)
                    / (this.$root.clientWidth - this.drag.grid.offset)
                )
                * this.duration
                + this.start
            );

            booking.end_time = Math.round(
                (
                    (this.drag.element.parentElement.getBoundingClientRect().width)
                    / (this.$root.clientWidth - this.drag.grid.offset)
                )
                * this.duration
                + booking.start_time
            )

            this.addProgressBar(this.drag.element.parentElement)

            this.$root.dispatchEvent(new CustomEvent(
                'updatebooking', {
                    detail: booking
                }
            ));
        },
    },

    resizeRight: {
        ['x-on:dragstart.self.stop']() {
            this.$el.parentElement.classList.add('is-resizing')

            this.drag.type = 'resize-right'
            this.drag.element = this.$el

            const image = new Image();
            this.$event.dataTransfer.setDragImage(image, 0, 0)
            this.$event.dataTransfer.effectAllowed = 'move'

            this.drag.position.x = 0 - this.$event.clientX

            this.drag.initial = {
                x: this.$el.offsetLeft,
                y: 0,
                width: this.$el.parentElement.getBoundingClientRect().width,
            }
        },

        ['x-on:dragend.self']() {
            if (this.$event.dataTransfer.dropEffect === 'none') {
                this.$el.parentElement.style.width = this.drag.initial.width + 'px'
                return;
            }

            this.$el.classList.remove('is-resizing')

            let booking = {
                id: parseInt(this.drag.element.parentElement.dataset.id),
                user_id: parseInt(this.drag.element.parentElement.dataset.user),
                start_time: parseInt(this.drag.element.parentElement.dataset.start),
                end_time: parseInt(this.drag.element.parentElement.dataset.end),
                resource_id: parseInt(this.drag.element.parentElement.dataset.resource),
            }

            booking.end_time = Math.round(
                (
                    (this.drag.element.parentElement.getBoundingClientRect().width)
                    / (this.$root.clientWidth - this.drag.grid.offset)
                )
                * this.duration
                + booking.start_time
            )

            this.addProgressBar(this.drag.element.parentElement)

            this.$root.dispatchEvent(new CustomEvent(
                'updatebooking', {
                    detail: booking
                }
            ));
        },
    },

    addProgressBar(element) {
        let progressBar = document.createElement('div')
        progressBar.classList.add('progress')
        element.appendChild(progressBar)
    }
})
