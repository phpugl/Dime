/*
 * Dime - activity list view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.activity)) {
    app.views['activity'] = {};
  }

  // activity list view
  app.views.activity.list = Backbone.View.extend({
    el: '#activities',
    initialize: function(obj) {
      _.bindAll(this);

      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      if (obj && obj.form) {
        this.form = obj.form;
      }

      this.itemTagName = (obj && obj.itemTagName) ? obj.itemTagName : "div";
    },
    render: function() {
      return this;
    },
    addAll: function() {
      this.$el.html('');
      this.collection.each(this.addOne);
    },
    addOne: function(item) {
      this.$el.prepend(new app.views.activity.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#activity-' + item.id).replaceWith(new app.views.activity.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
})(jQuery, dime);

