/*
 * Dime - customer list view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.customer)) {
    app.views['customer'] = {};
  }

  // customer list view
  app.views.customer.list = Backbone.View.extend({
    el: '#customers',
    initialize: function(obj) {
      _.bindAll(this);

      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      if (obj && obj.form) {
        this.form = obj.form;
      } else {
        this.form = new customer.views.form({ el: $('#customer-form') });
        this.form.collection = this.collection;
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
      this.$el.append(new app.views.customer.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#customer-' + item.id).html(new app.views.customer.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
})(jQuery, Dime);
