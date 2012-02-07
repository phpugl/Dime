/*
 * Dime - project form view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.project)) {
    app.views['project'] = {};
  }

  // project form view
  app.views.project.form = app.views.base.form.extend({
    render: function() {
      this.form.clear();
      this.form.fill(this.model.toJSON());

      var customers = new app.collection.customers;
      var selectBox = new app.views.customer.select({el: this.form.get('customer'), collection: customers, selected: this.model.get('customer')});
      customers.fetch();

      this.$el.modal({backdrop: 'static', show: true});
      return this;
    }
  });

})(jQuery, Dime);
