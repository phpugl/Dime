'use strict';

/**
 * Dime - core/model/service.js
 *
 * Register Service model to namespace App.
 */
(function ($, App) {

    // create Service model and add it to App.Model
    App.provide('Model.Service', App.Model.Base.extend({
        urlRoot:App.Route.Services,
        relations:{
            tags:{
                collection:'App.Collection.Tags',
                model:'App.Model.Tag'
            }
        }
    }));

})(jQuery, Dime);

