'use strict';

/**
 * Dime - core/model/setting.js
 *
 * Register Setting model to namespace App.
 */
(function ($, App) {

    // create Setting model and add it to App.Model
    App.provide('Model.Setting', Backbone.Model.extend({
        urlRoot:App.Route.Settings,
        parse: function(response, options) {

            if (response.value) {
                try {
                    response.value = $.parseJSON(response.value);
                } catch (SyntaxError) {
                }
            }

            return response;
        }
    }));

})(jQuery, Dime);

