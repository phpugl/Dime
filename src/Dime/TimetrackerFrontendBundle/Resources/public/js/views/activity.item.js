/*
 * Dime - activity item view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.activity)) {
    app.views['activity'] = {};
  }

  // activity item view
  app.views.activity.item = Backbone.View.extend({
    template: '#tpl-activity-item',
    events: {
      'click .edit': 'edit',
      'click .delete': 'clear',
      'click .continue': 'continue',
      'click .stop': 'stop'
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
      this.$el.attr('id', 'activity-' + this.model.get('id'));
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
    },
    'continue': function() {
      var newModel = new app.model.activity(this.model.toJSON());
      newModel.unset('id');
      newModel.unset('stopped_at');
      newModel.unset('duration');
      newModel.unset('user');

      //
      if (newModel.get('customer') && newModel.get('customer').id) {
          newModel.set('customer', newModel.get('customer').id);
      }
      if (newModel.get('project') && newModel.get('project').id) {
          newModel.set('project', newModel.get('project').id);
      }
      if (newModel.get('service') && newModel.get('service').id) {
          newModel.set('service', newModel.get('service').id);
      }
      newModel.set('started_at', moment(new Date).format('YYYY-MM-DD HH:mm:ss'));
      this.model.collection.create(newModel, {wait: true});
    },
    stop: function() {
      this.model.set('stopped_at', moment(new Date).format('YYYY-MM-DD HH:mm:ss')).save();
    }
  });
})(jQuery, dime);

