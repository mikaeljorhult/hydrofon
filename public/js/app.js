/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceList.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ResourceListCategory__ = __webpack_require__("./resources/js/components/ResourceListCategory.vue");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ResourceListCategory___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__ResourceListCategory__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ResourceListResource__ = __webpack_require__("./resources/js/components/ResourceListResource.vue");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ResourceListResource___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__ResourceListResource__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        'date': String,
        'categories': Array,
        'resources': Array
    },
    data: function data() {
        return {};
    },
    computed: {
        hasChildren: function hasChildren() {
            return this.categories && this.categories.length > 0 || this.resources && this.resources.length > 0;
        }
    },
    components: {
        'resourcelist-category': __WEBPACK_IMPORTED_MODULE_0__ResourceListCategory___default.a,
        'resourcelist-resource': __WEBPACK_IMPORTED_MODULE_1__ResourceListResource___default.a
    }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceListCategory.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ResourceListResource__ = __webpack_require__("./resources/js/components/ResourceListResource.vue");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ResourceListResource___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__ResourceListResource__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'resourcelist-category',
    props: {
        'item': Object
    },
    data: function data() {
        return {};
    },
    computed: {
        hasChildren: function hasChildren() {
            return this.item.categories && this.item.categories.length > 0 || this.item.resources && this.item.resources.length > 0;
        }
    },
    components: {
        'resourcelist-resource': __WEBPACK_IMPORTED_MODULE_0__ResourceListResource___default.a
    }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceListResource.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        'item': Object
    },
    data: function data() {
        return {};
    }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/component-normalizer.js":
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-5040dfb7\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceList.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("section", { staticClass: "resourcelist" }, [
    _c("section", { staticClass: "resourcelist-date" }, [
      _c("input", {
        directives: [
          {
            name: "model",
            rawName: "v-model",
            value: _vm.date,
            expression: "date"
          }
        ],
        staticClass: "field",
        attrs: { type: "text" },
        domProps: { value: _vm.date },
        on: {
          input: function($event) {
            if ($event.target.composing) {
              return
            }
            _vm.date = $event.target.value
          }
        }
      }),
      _vm._v(" "),
      _c("input", {
        staticClass: "btn btn-primary screen-reader",
        attrs: { type: "submit", value: "Show calendar" }
      })
    ]),
    _vm._v(" "),
    _vm.hasChildren
      ? _c(
          "ul",
          { staticClass: "list-reset p-4" },
          [
            _vm._l(_vm.categories, function(category) {
              return _c("resourcelist-category", {
                key: category.id,
                attrs: { item: category }
              })
            }),
            _vm._v(" "),
            _vm._l(_vm.resources, function(resource) {
              return _c("resourcelist-resource", {
                key: resource.id,
                attrs: { item: resource }
              })
            })
          ],
          2
        )
      : _vm._e(),
    _vm._v(" "),
    _c("a", {
      staticClass: "resourcelist-toggle",
      attrs: { href: "#", id: "resourcelist-toggle" }
    })
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5040dfb7", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-74c7b1e5\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceListResource.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("li", { staticClass: "resourcelist-resource" }, [
    _c("label", [
      _c("input", {
        attrs: { type: "checkbox", name: "resources[]", value: "item.id" }
      }),
      _vm._v("\n        " + _vm._s(_vm.item.name) + "\n    ")
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-74c7b1e5", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-b5ce9a56\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceListCategory.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("li", { staticClass: "resourcelist-category" }, [
    _c("span", [_vm._v("\n        " + _vm._s(_vm.item.name) + "\n    ")]),
    _vm._v(" "),
    _vm.hasChildren
      ? _c(
          "ul",
          { staticClass: "list-reset resourcelist-children" },
          [
            _vm._l(_vm.item.categories, function(category) {
              return _c("resourcelist-category", {
                key: category.id,
                attrs: { item: category }
              })
            }),
            _vm._v(" "),
            _vm._l(_vm.item.resource, function(resource) {
              return _c("resourcelist-resource", {
                key: resource.id,
                attrs: { item: resource }
              })
            })
          ],
          2
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-b5ce9a56", module.exports)
  }
}

/***/ }),

/***/ "./resources/js/app.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__modules_resourcelist__ = __webpack_require__("./resources/js/modules/resourcelist.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__modules_resourcelist___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__modules_resourcelist__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__modules_flashMessages__ = __webpack_require__("./resources/js/modules/flashMessages.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__modules_flashMessages___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__modules_flashMessages__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__modules_impersonation__ = __webpack_require__("./resources/js/modules/impersonation.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__modules_impersonation___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__modules_impersonation__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__modules_segel__ = __webpack_require__("./resources/js/modules/segel.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__modules_segel___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__modules_segel__);





var app = new Vue({
    el: '#app',
    components: {
        'resourcelist-root': __webpack_require__("./resources/js/components/ResourceList.vue")
    }
});

/***/ }),

/***/ "./resources/js/components/ResourceList.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceList.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-5040dfb7\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceList.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/ResourceList.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5040dfb7", Component.options)
  } else {
    hotAPI.reload("data-v-5040dfb7", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./resources/js/components/ResourceListCategory.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceListCategory.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-b5ce9a56\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceListCategory.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/ResourceListCategory.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b5ce9a56", Component.options)
  } else {
    hotAPI.reload("data-v-b5ce9a56", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./resources/js/components/ResourceListResource.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/js/components/ResourceListResource.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-74c7b1e5\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/js/components/ResourceListResource.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/ResourceListResource.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-74c7b1e5", Component.options)
  } else {
    hotAPI.reload("data-v-74c7b1e5", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./resources/js/modules/flashMessages.js":
/***/ (function(module, exports) {

var timer = void 0;
var messages = Array.from(document.getElementsByClassName('alert'));

// Check if there are any messages.
if (messages.length > 0) {
    // Attach event listener each message.
    messages.forEach(function (message) {
        message.addEventListener('click', clickListener, false);
    });

    // Trigger hiding of next message.
    nextMessage();
}

/**
 * Take last message in array and hide it.
 */
function nextMessage() {
    timer = setTimeout(function () {
        // Trigger hide on last message in DOM.
        hideMessage(messages.pop());

        // Trigger hiding of next message if there are more messages.
        if (messages.length > 0) {
            nextMessage();
        }
    }, 2500);
}

/**
 * Hide HTML element supplied and remove it from the DOM once hidden.
 *
 * @param messageElement
 */
function hideMessage(messageElement) {
    // Remove message once it has been hidden.
    messageElement.addEventListener('transitionend', function (event) {
        if (event.propertyName === 'visibility') {
            this.remove();
        }
    });

    // Add class to trigger hide.
    messageElement.classList.add('alert-hide');
}

function clickListener(event) {
    // Stop timer.
    clearTimeout(timer);

    // Hide current message.
    hideMessage(this);
    messages = messages.filter(function (message) {
        return message.classList.contains('alert-hide') === false;
    });

    // Restart counter for next message.
    nextMessage();

    // Prevent default click behaviour.
    event.preventDefault();
}

/***/ }),

/***/ "./resources/js/modules/impersonation.js":
/***/ (function(module, exports) {

var impersonationForm = document.querySelector('.topbar-impersonation form');

// Only add event listener if form is available.
if (impersonationForm) {
    var impersonationSelect = impersonationForm.querySelector('select');

    // Automatically submit form if value is changed.
    impersonationSelect.addEventListener('change', function () {
        impersonationForm.submit();
    });
}

/***/ }),

/***/ "./resources/js/modules/resourcelist.js":
/***/ (function(module, exports) {

var toggle = document.getElementById('resourcelist-toggle');

if (toggle) {
    toggle.addEventListener('click', function (event) {
        event.preventDefault();
        toggle.parentElement.classList.toggle('resourcelist__collapsed');
    });
}

/***/ }),

/***/ "./resources/js/modules/segel.js":
/***/ (function(module, exports) {

var segelElement = document.getElementById('segel');

if (segelElement !== null) {
    var resources = JSON.parse(segelElement.getAttribute('data-resources'));

    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
        for (var _iterator = resources[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var resource = _step.value;

            Segel.resources.add({
                id: resource.id,
                name: resource.name
            });

            if (resource.bookings.length > 0) {
                var _iteratorNormalCompletion2 = true;
                var _didIteratorError2 = false;
                var _iteratorError2 = undefined;

                try {
                    for (var _iterator2 = resource.bookings[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
                        var booking = _step2.value;

                        Segel.bookings.add({
                            id: booking.id,
                            resource: resource.id,
                            start: booking.start_time,
                            end: booking.end_time
                        });
                    }
                } catch (err) {
                    _didIteratorError2 = true;
                    _iteratorError2 = err;
                } finally {
                    try {
                        if (!_iteratorNormalCompletion2 && _iterator2.return) {
                            _iterator2.return();
                        }
                    } finally {
                        if (_didIteratorError2) {
                            throw _iteratorError2;
                        }
                    }
                }
            }
        }
    } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
    } finally {
        try {
            if (!_iteratorNormalCompletion && _iterator.return) {
                _iterator.return();
            }
        } finally {
            if (_didIteratorError) {
                throw _iteratorError;
            }
        }
    }

    Segel.time.set(segelElement.getAttribute('data-start'), segelElement.getAttribute('data-end'));

    // Segel('#segel');
}

/***/ }),

/***/ "./resources/sass/admin.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/app.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/js/app.js");
__webpack_require__("./resources/sass/app.scss");
module.exports = __webpack_require__("./resources/sass/admin.scss");


/***/ })

/******/ });