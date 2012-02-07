/*
 * Dime - activity collection
 */

(function ($, app) {

  // register activity module with model, collection and views
  var collection = app.collection['activities'] = Backbone.Collection.extend({
      url: 'api/activities'
  });

})(jQuery, Dime);

