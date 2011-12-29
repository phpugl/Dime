/*
 * Dime - service module
 */

(function ($, app) {

  // register service module with model, collection and views
  var service = app.module('service', {
    model: Backbone.Model.extend({}),
    collection: Backbone.Collection.extend({
      url: 'api/services',
      model: this.model
    }),
    views: {}
  });
  
  // service list view
  service.views.list = Backbone.View.extend({
    el: $('#services'),
    initialize: function(obj) {
      _.bindAll(this);
      
      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      if (obj && obj.form) {
        this.form = obj.form;
      } else {
        this.form = new service.views.form({ el: $('#service-form') });
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
      this.el.append(new service.views.item({model: item, form: this.form}).render().el);
    },
    change: function(item) {
      if (item.id != undefined) {
        $('#service-' + item.id).html(new service.views.item({model: item, form: this.form}).render().el);
      } else {
        this.addAll();
      }
    },
    destroy: function() {
      // not needed at the moment
    }
  });
  
  // service item view
  service.views.item = Backbone.View.extend({
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
      $(this.el).html(temp(this.model.toJSON()));
      $(this.el).attr('id', 'service-' + this.model.get('id'));
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

  // service form view
  service.views.form = Backbone.View.extend({
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
