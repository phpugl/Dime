'use strict';

/**
 * Dime - app/user/form.js
 */
(function ($, App) {

  App.provide('Views.User.Form', App.Views.Core.Content.extend({
      template:'DimeTimetrackerFrontendBundle:Users:form',
      render: function() {
          if (this.options.title) {
              this.$('header.page-header h1').text(this.options.title)
          }

          this.form = new App.Views.Core.Form.Model({
              el: '#user-form',
              model: this.model
          });
          this.form.render();

          return this;
      }
  }));

})(jQuery, Dime);
