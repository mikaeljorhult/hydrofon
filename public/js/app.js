(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app"],{

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/alpine.js");
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(alpinejs__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! interactjs */ "./node_modules/interactjs/dist/interact.min.js");
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(interactjs__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flatpickr__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flatpickr */ "./node_modules/flatpickr/dist/esm/index.js");
/* harmony import */ var _segel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./segel */ "./resources/js/segel/index.js");


window.interact = (interactjs__WEBPACK_IMPORTED_MODULE_1___default());

window.flatpickr = flatpickr__WEBPACK_IMPORTED_MODULE_2__.default;


/***/ }),

/***/ "./resources/js/segel/grid.js":
/*!************************************!*\
  !*** ./resources/js/segel/grid.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! interactjs */ "./node_modules/interactjs/dist/interact.min.js");
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(interactjs__WEBPACK_IMPORTED_MODULE_0__);
// Dependencies.

/**
 * Create an interact.js snap grid.
 *
 * @param {Object} coordinates - Size of grid in pixels.
 * @returns {Array} - interact.js snap grid.
 */

function snapGrid(coordinates) {
  return [interactjs__WEBPACK_IMPORTED_MODULE_0___default().createSnapGrid(coordinates)];
}
/**
 * Base grid object.
 *
 * @type {Object}
 */


var Grid = {};
/**
 * Create snap grid.
 *
 * @param {Number} width - Width of the available area in pixels.
 * @param {Number} height - Height of the grid in pixels.
 * @param {Number} steps - Number of steps to divide the horizontal space in.
 * @returns {Array} - interact.js snap grid.
 */

Grid.create = function (width, height, steps) {
  return snapGrid({
    x: width / steps,
    y: height
  });
}; // Return the instance.


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Grid);

/***/ }),

/***/ "./resources/js/segel/index.js":
/*!*************************************!*\
  !*** ./resources/js/segel/index.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! interactjs */ "./node_modules/interactjs/dist/interact.min.js");
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(interactjs__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var alpinejs_src_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! alpinejs/src/utils */ "./node_modules/alpinejs/src/utils.js");
/* harmony import */ var _grid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./grid */ "./resources/js/segel/grid.js");
/* harmony import */ var _interactions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./interactions */ "./resources/js/segel/interactions.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }





HYDROFON.Segel = {
  _component: null,
  _element: null,
  initialized: false,
  grid: null,
  size: null,
  interactions: _interactions__WEBPACK_IMPORTED_MODULE_2__.default,

  set component(component) {
    this._component = component;
    this._element = component.el;
    this.calculateGrid();
    this.initialized = true;
  },

  get component() {
    return this._component;
  },

  get element() {
    return this._element;
  },

  set resources(resources) {
    HYDROFON.Segel.component.call('setResources', resources);
  },

  set expanded(categories) {
    this._debounceExpanded(categories);
  },

  _debounceExpanded: (0,alpinejs_src_utils__WEBPACK_IMPORTED_MODULE_0__.debounce)(function (expanded) {
    HYDROFON.Segel.component.call('setExpanded', expanded);
  }, 1000),
  calculateGrid: function calculateGrid() {
    this.grid = _grid__WEBPACK_IMPORTED_MODULE_1__.default.create(this.element.clientWidth, 41, this.component.data.steps);
    this.size = {
      min: {
        width: this.element.clientWidth / this.component.data.steps,
        height: 1
      },
      max: {
        width: this.element.clientWidth,
        height: 41
      }
    };
  },
  handleResize: function handleResize() {
    this.calculateGrid();
    var bookings = this.element.querySelectorAll('.segel-booking');

    var _iterator = _createForOfIteratorHelper(bookings),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var booking = _step.value;

        if (!interactjs__WEBPACK_IMPORTED_MODULE_3___default().isSet(booking)) {
          continue;
        }

        var draggable = interactjs__WEBPACK_IMPORTED_MODULE_3___default()(booking).draggable();
        var resizable = interactjs__WEBPACK_IMPORTED_MODULE_3___default()(booking).resizable();
        draggable.modifiers[1].options.targets = this.grid;
        resizable.modifiers[2].options.targets = this.grid;
        resizable.modifiers[1].options.min = this.size.min;
        resizable.modifiers[1].options.max = this.size.max;
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }
};

/***/ }),

/***/ "./resources/js/segel/interactions.js":
/*!********************************************!*\
  !*** ./resources/js/segel/interactions.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! interactjs */ "./node_modules/interactjs/dist/interact.min.js");
/* harmony import */ var interactjs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(interactjs__WEBPACK_IMPORTED_MODULE_0__);
// Dependencies.

/**
 * Base interactions object.
 *
 * @type {Object}
 */

var Interactions = {};
var position = {
  x: 0,
  y: 0
};
var bookingClone = null;

Interactions.resource = function (resource) {
  // Bail if interact has already been setup.
  if (interactjs__WEBPACK_IMPORTED_MODULE_0___default().isSet(resource)) {
    return;
  }

  interactjs__WEBPACK_IMPORTED_MODULE_0___default()(resource).dropzone({
    listeners: {
      dragenter: function dragenter(event) {
        event.target.classList.add('droppable');
      },
      dragleave: function dragleave(event) {
        event.target.classList.remove('droppable');
      },
      drop: function drop(event) {
        var resourceNode = event.target;
        var bookingNode = event.relatedTarget;
        var booking = {
          id: parseInt(bookingNode.dataset.id),
          user_id: parseInt(bookingNode.dataset.user),
          start_time: parseInt(bookingNode.dataset.start),
          end_time: parseInt(bookingNode.dataset.end),
          resource_id: parseInt(resourceNode.dataset.id)
        };
        var startTime = Math.round((bookingNode.getBoundingClientRect().left - resourceNode.getBoundingClientRect().left) / resourceNode.clientWidth * HYDROFON.Segel.component.data.timestamps.duration + HYDROFON.Segel.component.data.timestamps.start);
        var endTime = booking.end_time - (booking.start_time - startTime);
        booking.start_time = startTime;
        booking.end_time = endTime;
        var progressBar = document.createElement('div');
        progressBar.classList.add('progress');
        bookingNode.appendChild(progressBar);
        HYDROFON.Segel.component.call(event.dragEvent.altKey ? 'createBooking' : 'updateBooking', booking);
        bookingNode.classList.remove('droppable');
        position = {
          x: 0,
          y: 0
        };
      }
    }
  });
  interactjs__WEBPACK_IMPORTED_MODULE_0___default()(resource).on('doubletap', function (event) {
    // Ignore propagating clicks from other elements.
    if (!event.target.classList.contains('segel-resource') && !event.target.classList.contains('segel-bookings')) {
      return;
    }

    var resourceNode = event.target.classList.contains('segel-resource') ? event.target : event.target.parentNode;
    var position = Math.round(event.offsetX / resourceNode.clientWidth * HYDROFON.Segel.component.data.timestamps.duration);
    var size = Math.round(HYDROFON.Segel.component.data.timestamps.duration / HYDROFON.Segel.component.data.steps);
    var startTime = position + HYDROFON.Segel.component.data.timestamps.start;
    var endTime = startTime + size * 2;
    HYDROFON.Segel.component.call('createBooking', {
      resource_id: parseInt(resourceNode.dataset.id),
      start_time: startTime,
      end_time: endTime
    });
  });
};

Interactions.booking = function (booking) {
  // Bail if interact has already been setup.
  if (interactjs__WEBPACK_IMPORTED_MODULE_0___default().isSet(booking) || !booking.classList.contains('editable')) {
    if (!booking.classList.contains('editable')) {
      interactjs__WEBPACK_IMPORTED_MODULE_0___default()(booking).unset();
    }

    return;
  }

  interactjs__WEBPACK_IMPORTED_MODULE_0___default()(booking).draggable({
    listeners: {
      start: function start(event) {
        if (event.altKey) {
          bookingClone = event.target.cloneNode(false);
          event.target.parentNode.appendChild(bookingClone);
        }

        event.target.classList.add('is-dragging');
      },
      move: function move(event) {
        position.x += event.dx;
        position.y += event.dy;
        event.target.style.transform = "translate(".concat(position.x, "px, ").concat(position.y, "px)");
      },
      end: function end(event) {
        event.target.classList.remove('is-dragging');

        if (bookingClone) {
          if (event.altKey) {
            HYDROFON.Segel.interactions.booking(bookingClone);
          } else {
            bookingClone.parentNode.removeChild(bookingClone);
          }

          bookingClone = null;
        }
      }
    },
    modifiers: [interactjs__WEBPACK_IMPORTED_MODULE_0___default().modifiers.restrict({
      restriction: '.segel-resources'
    }), interactjs__WEBPACK_IMPORTED_MODULE_0___default().modifiers.snap({
      targets: HYDROFON.Segel.grid,
      offset: 'startCoords'
    })]
  });
  interactjs__WEBPACK_IMPORTED_MODULE_0___default()(booking).resizable({
    edges: {
      top: false,
      bottom: false,
      left: ".segel-resize-handle__left",
      right: ".segel-resize-handle__right"
    },
    listeners: {
      start: function start(event) {
        event.target.classList.add('is-resizing');
      },
      move: function move(event) {
        var _event$target$dataset = event.target.dataset,
            x = _event$target$dataset.x,
            y = _event$target$dataset.y;
        x = (parseFloat(x) || 0) + event.deltaRect.left;
        y = (parseFloat(y) || 0) + event.deltaRect.top;
        Object.assign(event.target.style, {
          width: "".concat(event.rect.width, "px"),
          height: "".concat(event.rect.height, "px"),
          transform: "translate(".concat(x, "px, ").concat(y, "px)")
        });
        Object.assign(event.target.dataset, {
          x: x,
          y: y
        });
      },
      end: function end(event) {
        var bookingNode = event.target;
        var resourceNode = bookingNode.parentNode;
        var x = bookingNode.dataset.x;
        x = parseFloat(x) || 0;
        var booking = {
          id: parseInt(bookingNode.dataset.id),
          user_id: parseInt(bookingNode.dataset.user),
          start_time: parseInt(bookingNode.dataset.start),
          end_time: parseInt(bookingNode.dataset.end),
          resource_id: parseInt(resourceNode.dataset.id)
        };
        var startTime = Math.round((bookingNode.getBoundingClientRect().left - resourceNode.getBoundingClientRect().left) / resourceNode.clientWidth * HYDROFON.Segel.component.data.timestamps.duration + HYDROFON.Segel.component.data.timestamps.start);
        var endTime = Math.round(bookingNode.getBoundingClientRect().width / resourceNode.clientWidth * HYDROFON.Segel.component.data.timestamps.duration + startTime);
        booking.start_time = startTime;
        booking.end_time = endTime;
        var progressBar = document.createElement('div');
        progressBar.classList.add('progress');
        bookingNode.appendChild(progressBar);
        HYDROFON.Segel.component.call('updateBooking', booking);
        event.target.classList.remove('is-resizing');
      }
    },
    modifiers: [interactjs__WEBPACK_IMPORTED_MODULE_0___default().modifiers.restrict({
      restriction: '.segel-resources'
    }), interactjs__WEBPACK_IMPORTED_MODULE_0___default().modifiers.restrictSize(HYDROFON.Segel.size), interactjs__WEBPACK_IMPORTED_MODULE_0___default().modifiers.snap({
      targets: HYDROFON.Segel.grid,
      offset: 'startCoords'
    })]
  });
  interactjs__WEBPACK_IMPORTED_MODULE_0___default()(booking).on('doubletap', function (event) {
    HYDROFON.Segel.component.call('deleteBooking', {
      id: parseInt(event.target.dataset.id)
    });
  });
}; // Return the instance.


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Interactions);

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ "use strict";
/******/ 
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/app","/js/vendor"], () => (__webpack_exec__("./resources/js/app.js"), __webpack_exec__("./resources/sass/app.scss")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);