'use strict';

/**
 * Dime - collection/customer.js
 *
 * Register Customers collection to namespace App
 */
(function ($, App) {

  // Create Customers collection and add it to App.Collection
  App.provide('Collection.Customers', App.Collection.Base.extend({
    model: App.Model.Customer,
    url: App.Route.Customers
  }));

})(jQuery, Dime);

