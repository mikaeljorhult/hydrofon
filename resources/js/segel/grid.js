// Dependencies.
import interact from 'interactjs';

/**
 * Create an interact.js snap grid.
 *
 * @param {Object} coordinates - Size of grid in pixels.
 * @returns {Array} - interact.js snap grid.
 */
function snapGrid(coordinates) {
    return [interact.createSnapGrid(coordinates)];
}

/**
 * Base grid object.
 *
 * @type {Object}
 */
const Grid = {};

/**
 * Create snap grid.
 *
 * @param {Number} width - Width of the available area in pixels.
 * @param {Number} height - Height of the grid in pixels.
 * @param {Number} steps - Number of steps to divide the horizontal space in.
 * @returns {Array} - interact.js snap grid.
 */
Grid.create = function(width, height, steps) {
    return snapGrid({
        x: width / steps,
        y: height
    });
};

// Return the instance.
export default Grid;
