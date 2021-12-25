HYDROFON.Segel = {
    _component: null,
    _element: null,
    initialized: false,
    set component(component) {
        this._component = component;
        this._element = component.el;
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
        HYDROFON.Segel.component.call('setExpanded', categories);
    },
};
