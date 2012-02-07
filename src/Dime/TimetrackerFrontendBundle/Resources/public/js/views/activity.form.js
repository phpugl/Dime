/*
 * Dime - activity form view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.activity)) {
    app.views['activity'] = {};
  }

  // activity form view
  app.views.activity.form = app.views.base.form.extend({
    render: function() {
      this.form.clear();
      this.form.fill(this.model.toJSON());

      var customers = new app.collection.customers();
      var selectBox = new app.views.customer.select({el: this.form.get('customer'), collection: customers, selected: this.model.get('customer')});
      customers.fetch();
      
      var services  = new app.collection.services();
      var selectBox = new app.views.service.select({el: this.form.get('service'), collection: services, selected: this.model.get('service')});
      services.fetch();
      
      var projects  = new app.collection.projects();
      var selectBox = new app.views.project.select({el: this.form.get('project'), collection: projects, selected: this.model.get('project')});
      projects.fetch();

      this.$el.modal({backdrop: 'static', show: true});
      return this;
    }
  });
})(jQuery, Dime);

