/*
 * Dime - service module
 */

(function ($, app) {

  // register service module with model, collection and views
  var service = app.module('service', {
    model: Backbone.Model.extend({}),
    collection: Backbone.Collection.extend({
      url: 'api/services'
    //  model: this.model
    }),
    views: {}
  });
  
  // service list view
  service.views.list = Backbone.View.extend({
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
    },
    render: function() {
      return this;
    },
    addAll: function() {
      this.$el.html('');
      this.collection.each(this.addOne);
    },
    addOne: function(item) {
      this.$el.append(new service.views.item({model: item, form: this.form}).render().el);
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

  // service form view
  service.views.form = Backbone.View.extend({
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
  
  service.views.options = Backbone.View.extend({
    tagName: "option",
    initialize: function() {
        _.bindAll(this, 'render');
    },
    render: function() {
        this.$el.attr('value', this.model.get('id')).html(this.model.get('name'));
        return this;
    }
  });

  service.views.select = Backbone.View.extend({
    initialize: function(opt){
        _.bindAll(this, 'addOne', 'addAll');
        this.collection.bind('reset', this.addAll);

        // grep selected option can be service object or just the id
        if (opt && opt.selected) {
          this.selectedId = (opt.selected.id) ? opt.selected.id : opt.selected;
        }
    },
    addOne: function(obj){
        var optionView = new service.views.options({ model: obj });
        this.selectViews.push(optionView);
        this.$el.append(optionView.render().el);
    },
    addAll: function() {
        // clear select
        this.$el.html('');

        _.each(this.selectViews, function(optionView) { optionView.remove(); });
        this.selectViews = [];
        this.collection.each(this.addOne);

        // select option if selectedId exists
        if (this.selectedId) {
            this.$el.val(this.selectedId);
        }
    }
  });

})(jQuery, dime);
