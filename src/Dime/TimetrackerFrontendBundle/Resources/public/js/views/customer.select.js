/*
 * Dime - customer select view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.customer)) {
    app.views['customer'] = {};
  }

  // customer option view
  app.views.customer.option = Backbone.View.extend({
    tagName: "option",
    initialize: function() {
        _.bindAll(this, 'render');
    },
    render: function() {
        this.$el.attr('value', this.model.get('id')).html(this.model.get('name'));
        return this;
    }
  });

  app.views.customer.select = Backbone.View.extend({
    initialize: function(opt){
        _.bindAll(this, 'addOne', 'addAll');
        this.collection.bind('reset', this.addAll);

        // grep selected option can be customer object or just the id
        if (opt && opt.selected) {
          this.selectedId = (opt.selected.id) ? opt.selected.id : opt.selected;
        }
    },
    addOne: function(obj){
        var optionView = new app.views.customer.option({ model: obj });
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
