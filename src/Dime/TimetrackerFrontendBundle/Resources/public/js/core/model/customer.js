'use strict';

/**
 * Dime - core/model/customer.js
 *
 * Register Customer model to namespace App.
 */
(function ($, App) {

    // Create Customer model and add it to App.Model
    App.provide('Model.Customer', App.Model.Base.extend({
        urlRoot:App.Route.Customers,
        relations:{
            tags:{
                collection:'App.Collection.Tags',
                model:'App.Model.Tag'
            }
        }
    }));

})(jQuery, Dime);

