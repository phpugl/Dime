/*
 * Dime - project module
 */

(function ($, app) {

  // register project module with model, collection and views
  var project = app.module('project', {
    model: Backbone.Model.extend({}),
    collection: Backbone.Collection.extend({
      url: 'api/projects',
      model: this.model
    }),
    views: {}
  });
  
  // project list view
  project.views.list = Backbone.View.extend({
    el: $('#projects'),
    initialize: function(obj) {
      _.bindAll(this);
      
      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      if (obj && obj.form) {
        this.form = obj.form;
      } else {
        this.form = new project.views.form({ el: $('#project-form') });
        this.form.collection = this.collection;
      }
    },
    render: function() {
      return this;
    },
    addAll: function() {
      this.el.html('');
      this.collection.each(this.addOne);
    },
    addOne: function(item) {
      this.el.append(new project.views.item({model: item, form: this.form}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#project-' + item.id).html(new project.views.item({model: item, form: this.form}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
  
  // project item view
  project.views.item = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#tpl-project-item').html()),
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
      $(this.el).html(this.template(this.model.toJSON()));
      $(this.el).attr('id', 'project-' + this.model.get('id'));
      return this;
    },
    edit: function() {
      this.form.model = this.model;
      this.form.render();
    },
    remove: function() {
      $(this.el).remove();
    },
    clear: function() {
      if (confirm("Are you sure?")) {
        this.model.destroy();
      }
    }
  });

  // project form view
  project.views.form = Backbone.View.extend({
    events: {
      'click .save': 'save',
      'click .cancel': 'close'
    },
    initialize: function() {
        _.bindAll(this);
        this.form = this.el.form();
    },
    render: function() {
        this.form.clear();
        this.form.fill(this.model.toJSON());
        this.el.modal({backdrop: 'static', show: true});
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
        this.el.data('modal').hide();
    }
  });

})(jQuery, dime);
