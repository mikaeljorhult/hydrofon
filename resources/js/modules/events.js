/**
 * Create base events object.
 * This is just a separate Vue instance that only handles emitting and listening to events.
 *
 * @type {Vue}
 */
const Events = new Vue();

// Return the instance.
export default Events;
