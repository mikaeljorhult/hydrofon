import Grid from './grid';

HYDROFON.Segel = {
    _component: null,
    _element: null,
    grid: null,
    size: null,
    set component(component) {
        this._component = component;
        this._element = component.el.el;

        this.grid = Grid.create(this._element.clientWidth, 40, this.component.data.steps);
        this.size = {
            min: {
                width: this._element.clientWidth / this.component.data.steps,
                height: 1
            },
            max: {
                width: this._element.clientWidth,
                height: 40
            }
        };
    },
    get component() {
        return this._component;
    },
};

// Object.defineProperty(HYDROFON.Segel.grid, 'context', { get: function() { return HYDROFON.Segel; } });
