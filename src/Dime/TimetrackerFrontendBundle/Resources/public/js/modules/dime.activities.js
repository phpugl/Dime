/*
 * Dime - activity module
 */

(function ($, app) {

  // register activity module with model, collection and views
  var activity = app.module('activity', {
    model: Backbone.Model.extend({}),
    collection: Backbone.Collection.extend({
      url: 'api/activities'
    }),
    views: {}
  });

  // activity list view
  activity.views.list = Backbone.View.extend({
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
      this.$el.prepend(new activity.views.item({model: item, form: this.form, tagName: this.itemTagName}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#activity-' + item.id).html(new activity.views.item({model: item, form: this.form}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });

  // activity item view
  activity.views.item = Backbone.View.extend({
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
      var newModel = new activity.model(this.model.toJSON());
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

  // activity form view
  activity.views.form = Backbone.View.extend({
    events: {
      'click .save': 'save',
      'click .cancel': 'close'
    },
    initialize: function() {
      _.bindAll(this);
      this.form = this.$el.form();
    },
    render: function() {
      this.form.clear();
      this.form.fill(this.model.toJSON());

      var customerMod = app.module('customer');
      var customers = new customerMod.collection();
      var selectBox = new customerMod.views.select({el: this.form.get('customer'), collection: customers, selected: this.model.get('customer')});
      customers.fetch();
      
      var serviceMod = app.module('service');
      var services = new serviceMod.collection();
      var selectBox = new serviceMod.views.select({el: this.form.get('service'), collection: services, selected: this.model.get('service')});
      services.fetch();
      
      var projectMod = app.module('project');
      var projects = new projectMod.collection();
      var selectBox = new projectMod.views.select({el: this.form.get('project'), collection: projects, selected: this.model.get('project')});
      projects.fetch();

      this.$el.modal({backdrop: 'static', show: true});
      return this;
    },
    save: function() {
      if (this.model) {
        if (this.model.isNew()) {
          this.model.set(this.form.data());
          if (this.collection) {
            this.collection.create(this.model, {success: this.close});
          }
        } else {
          this.model.save(this.form.data(), {success: this.close});
        }
      }
    },
    close: function() {
        this.$el.data('modal').hide();
    }
  });

})(jQuery, dime);
