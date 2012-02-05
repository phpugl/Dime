/*
 * Dime - service item view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.service)) {
    app.views['service'] = {};
  }
  
  // service item view
  app.views.service.item = Backbone.View.extend({
    tagName: 'div',
    template: '#tpl-service-item',
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
      var temp = _.template($(this.template).html());
      this.$el.html(temp(this.model.toJSON()));
      this.$el.attr('id', 'service-' + this.model.get('id'));
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
