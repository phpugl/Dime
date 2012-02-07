/*
 * Dime - service form view
 */

(function ($, app) {

  // init views
  if ('undefined' == typeof(app.views.service)) {
    app.views['service'] = {};
  }
  
  // service form view
  app.views.service.form = app.views.base.form.extend({
    render: function() {
        this.form.clear();
        this.form.fill(this.model.toJSON());
        this.$el.modal('show');
        return this;
    }
  });
})(jQuery, Dime);
