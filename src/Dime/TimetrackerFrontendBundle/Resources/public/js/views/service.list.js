/*
 * Dime - service list view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.service)) {
    app.views['service'] = {};
  }
  
  // service list view
  app.views.service.list = Backbone.View.extend({
    el: '#services',
    initialize: function(obj) {
      _.bindAll(this);
      
      this.collection.bind('reset', this.addAll, this);
      this.collection.bind('add', this.addOne, this);
      this.collection.bind('change', this.change, this);
      this.collection.bind('destroy', this.destroy, this);

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
      this.$el.append(new app.views.service.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#service-' + item.id).html(new app.views.service.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
})(jQuery, dime);
