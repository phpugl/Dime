'use strict';

/**
 * Dime - core/helper/hooks.js
 */
(function ($, Backbone, App) {

    App.provide('Model.Hook', Backbone.Model.extend({
        defaults: {
            weight: 0,
            scope: 'default'
        }
    }));

    App.provide('Collection.Hooks', Backbone.Collection.extend({
        model: App.Model.Hook,
        comparator:function (model) {
            return model.get('weight');
        }
    }));

    /**
     * Initialization hook
     *
     * @param item Object {id: 'Unique name', scope: '', weight: 0, callback: func}
     * @return Dime
     */
    App.provide('hook', new App.Collection.Hooks());
})(jQuery, Backbone, Dime);
