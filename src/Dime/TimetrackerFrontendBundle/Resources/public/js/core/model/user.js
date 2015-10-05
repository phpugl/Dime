'use strict';

/**
 * Dime - core/model/user.js
 *
 * Register User model to namespace App.
 */
(function ($, App) {

    // create User model and add it to App.Model
    App.provide('Model.User', Backbone.Model.extend({
        urlRoot:App.Route.Users
    }));

})(jQuery, Dime);

