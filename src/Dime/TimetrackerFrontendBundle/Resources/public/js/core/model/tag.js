'use strict';

/**
 * Dime - core/model/tag.js
 *
 * Register Tag model to namespace App.
 */
(function ($, Backbone, App) {

    // create Tag model and add it to App.Model
    App.provide('Model.Tag', Backbone.Model.extend({
        urlRoot:App.Route.Tags
    }));

})(window.jQuery, window.Backbone, window.Dime);

