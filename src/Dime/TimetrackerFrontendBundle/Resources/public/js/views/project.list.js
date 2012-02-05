/*
 * Dime - project list view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.project)) {
    app.views['project'] = {};
  }

  // project list view
  app.views.project.list = Backbone.View.extend({
    el: '#projects',
    initialize: function(obj) {
      _.bindAll(this);

      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      if (obj && obj.form) {
        this.form = obj.form;
      }
    },
    render: function() {
      return this;
    },
    addAll: function() {
      this.$el.html('');
      this.collection.each(this.addOne);
    },
    addOne: function(item) {
      this.$el.append(new app.views.project.item({model: item, form: this.form}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#project-' + item.id).html(new app.views.project.item({model: item, form: this.form}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
})(jQuery, dime);
