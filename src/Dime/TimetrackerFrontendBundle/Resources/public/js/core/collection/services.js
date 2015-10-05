'use strict';

/**
 * Dime - collection/services.js
 *
 * Register Services collection to namespace App
 */
(function ($, App) {

  // Create Services collection and add it to App.Collection
  App.provide('Collection.Services', App.Collection.Base.extend({
    model: App.Model.Service,
    url: App.Route.Services
  }));

})(jQuery, Dime);

