/*
 * Dime - project collection
 */

(function ($, app) {

  // register project module with model, collection and views
  var collection = app.collection['projects'] = Backbone.Collection.extend({
      url: 'api/projects'
  });

})(jQuery, Dime);

