/*
 * Dime - customer item view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.customer)) {
    app.views['customer'] = {};
  }

  // customer item view
  app.views.customer.item = Backbone.View.extend({
    tagName: 'div',
    template: '#tpl-customer-item',
    events: {
      'click .edit': 'edit',
      'click .delete': 'clear'
    },
    initialize: function(obj) {
      _.bindAll(this);
      if (obj && obj.form) {
        this.form = obj.form;
      }
      this.model.bind('destroy', this.remove, this);
    },
    render: function() {
      var template = _.template($(this.template).html());
      this.$el.html(template(this.model.toJSON()));
      this.$el.attr('id', 'customer-' + this.model.get('id'));
      return this;
    },
    edit: function() {
      this.form.model = this.model;
      this.form.render();
    },
    remove: function() {
      this.$el.remove();
    },
    clear: function() {
      if (confirm("Are you sure?")) {
        this.model.destroy();
      }
    }
  });
})(jQuery, dime);
