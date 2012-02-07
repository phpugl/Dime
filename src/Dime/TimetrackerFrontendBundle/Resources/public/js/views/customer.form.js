/*
 * Dime - customer form view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.customer)) {
    app.views['customer'] = {};
  }

  // customer form view
  app.views.customer.form = app.views.base.form.extend({
    render: function() {
        this.form.clear();
        this.form.fill(this.model.toJSON());
        this.$el.modal({backdrop: 'static', show: true});
        return this;
    }
  });
})(jQuery, Dime);
