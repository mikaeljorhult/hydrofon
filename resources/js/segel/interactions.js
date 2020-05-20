// Dependencies.
import interact from 'interactjs';

/**
 * Base interactions object.
 *
 * @type {Object}
 */
const Interactions = {};

Interactions.resource = function (resource) {
    // Bail if interact has already been setup.
    if (interact.isSet(resource)) {
        return;
    }

    interact(resource).dropzone({
        listeners: {
            dragenter: function (event) {
                event.target.classList.add('droppable');
            },
            dragleave: function (event) {
                event.target.classList.remove('droppable');
            },
            drop: function (event) {
                let resourceNode = event.target;
                let bookingNode = event.relatedTarget;

                let booking = {
                    id: parseInt(bookingNode.dataset.id),
                    user_id: parseInt(bookingNode.dataset.user),
                    start_time: parseInt(bookingNode.dataset.start),
                    end_time: parseInt(bookingNode.dataset.end),
                    resource_id: parseInt(resourceNode.dataset.id),
                };

                let startTime = Math.round(
                    (
                        (bookingNode.getBoundingClientRect().left - resourceNode.getBoundingClientRect().left)
                        / resourceNode.clientWidth
                    )
                    * HYDROFON.Segel.component.data.timestamps.duration
                    + HYDROFON.Segel.component.data.timestamps.start
                );

                let endTime = booking.end_time - (booking.start_time - startTime);

                booking.start_time = startTime;
                booking.end_time = endTime;

                let progressBar = document.createElement('div');
                progressBar.classList.add('progress');
                bookingNode.appendChild(progressBar);

                HYDROFON.Segel.component.call('updateBooking', booking);

                bookingNode.classList.remove('droppable');
            }
        }
    });

    interact(resource).on('doubletap', function (event) {
        let resourceNode = event.target;

        // Ignore propagating clicks from other elements.
        if (resourceNode.className !== 'segel-resource') {
            return;
        }

        let position = Math.round(
            (event.offsetX / resourceNode.clientWidth)
            * HYDROFON.Segel.component.data.timestamps.duration
        );

        let size = Math.round(
            HYDROFON.Segel.component.data.timestamps.duration
            / HYDROFON.Segel.component.data.steps
        );

        let startTime = position + HYDROFON.Segel.component.data.timestamps.start;
        let endTime = startTime + size * 2;

        HYDROFON.Segel.component.call('createBooking', {
            resource_id: parseInt(event.target.dataset.id),
            start_time: startTime,
            end_time: endTime,
        });
    });
};

Interactions.booking = function (booking) {
    // Bail if interact has already been setup.
    if (interact.isSet(booking) || !booking.classList.contains('editable')) {
        if (!booking.classList.contains('editable')) {
            interact(booking).unset();
        }

        return;
    }

    const position = {x: 0, y: 0};

    interact(booking).draggable({
        listeners: {
            start: function (event) {
                event.target.classList.add('is-dragging');
            },
            move: function (event) {
                position.x += event.dx;
                position.y += event.dy;

                event.target.style.transform = `translate(${position.x}px, ${position.y}px)`;
            },
            end: function (event) {
                event.target.classList.remove('is-dragging');
            }
        },
        modifiers: [
            interact.modifiers.restrict({
                restriction: '.segel-resources'
            }),
            interact.modifiers.snap({
                targets: HYDROFON.Segel.grid,
                offset: 'startCoords'
            })
        ],
    });

    interact(booking).resizable({
        edges: {
            top: false,
            bottom: false,
            left: ".segel-resize-handle__left",
            right: ".segel-resize-handle__right"
        },
        listeners: {
            start: function (event) {
                event.target.classList.add('is-resizing');
            },
            move: function (event) {
                let {x, y} = event.target.dataset;

                x = (parseFloat(x) || 0) + event.deltaRect.left;
                y = (parseFloat(y) || 0) + event.deltaRect.top;

                Object.assign(event.target.style, {
                    width: `${event.rect.width}px`,
                    height: `${event.rect.height}px`,
                    transform: `translate(${x}px, ${y}px)`
                });

                Object.assign(event.target.dataset, {x, y});
            },
            end: function (event) {
                let bookingNode = event.target;
                let resourceNode = bookingNode.parentNode;

                let {x} = bookingNode.dataset;
                x = parseFloat(x) || 0;

                let booking = {
                    id: parseInt(bookingNode.dataset.id),
                    user_id: parseInt(bookingNode.dataset.user),
                    start_time: parseInt(bookingNode.dataset.start),
                    end_time: parseInt(bookingNode.dataset.end),
                    resource_id: parseInt(resourceNode.dataset.id),
                };

                let startTime = Math.round(
                    (
                        (bookingNode.getBoundingClientRect().left - resourceNode.getBoundingClientRect().left)
                        / resourceNode.clientWidth
                    )
                    * HYDROFON.Segel.component.data.timestamps.duration
                    + HYDROFON.Segel.component.data.timestamps.start
                );

                let endTime = Math.round(
                    (
                        bookingNode.getBoundingClientRect().width
                        / resourceNode.clientWidth
                    )
                    * HYDROFON.Segel.component.data.timestamps.duration
                    + startTime
                );

                booking.start_time = startTime;
                booking.end_time = endTime;

                let progressBar = document.createElement('div');
                progressBar.classList.add('progress');
                bookingNode.appendChild(progressBar);

                HYDROFON.Segel.component.call('updateBooking', booking);

                event.target.classList.remove('is-resizing');
            }
        },
        modifiers: [
            interact.modifiers.restrict({
                restriction: '.segel-resources'
            }),
            interact.modifiers.restrictSize(
                HYDROFON.Segel.size
            ),
            interact.modifiers.snap({
                targets: HYDROFON.Segel.grid,
                offset: 'startCoords'
            }),
        ],
    });

    interact(booking).on('doubletap', function (event) {
        HYDROFON.Segel.component.call('deleteBooking', {
            id: parseInt(event.target.dataset.id),
        });
    });
};

// Return the instance.
export default Interactions;
