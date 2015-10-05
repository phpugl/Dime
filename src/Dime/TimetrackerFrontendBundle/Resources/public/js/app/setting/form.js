'use strict';

/**
 * Dime - app/setting/form.js
 */
(function ($, App) {

    App.provide('Views.Setting.Form', App.Views.Core.Content.extend({
        template:'DimeTimetrackerFrontendBundle:Settings:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#setting-form',
                model: this.model,
                backNavigation:'setting'
            });
            this.form.render();

            return this;
        }
    }));

})(jQuery, Dime);
