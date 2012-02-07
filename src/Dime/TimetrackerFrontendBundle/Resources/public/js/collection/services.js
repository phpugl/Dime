/*
 * Dime - service collection
 */

(function ($, app) {

  // register service module with model, collection and views
  var collection = app.collection['services'] = Backbone.Collection.extend({
      url: 'api/services'
  });

})(jQuery, Dime);

