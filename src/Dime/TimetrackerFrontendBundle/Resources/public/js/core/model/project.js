'use strict';

/**
 * Dime - core/model/project.js
 *
 * Register Project model to namespace App.
 */
(function ($, App) {

    // Create Project model and add it to App.Model
    App.provide('Model.Project', App.Model.Base.extend({
        urlRoot:App.Route.Projects,
        relations: {
            customer: {
                model: 'App.Model.Customer'
            },
            tags: {
                collection: 'App.Collection.Tags',
                model: 'App.Model.Tag'
            }
        }
    }));

})(jQuery, Dime);

