/*
 * Dime - customer collection
 */

(function ($, app) {

  // register customer module with model, collection and views
  var collection = app.collection['customers'] = Backbone.Collection.extend({
      url: 'api/customers'
  });

})(jQuery, Dime);

