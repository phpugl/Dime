'use strict';

/**
 * Dime - app/tag/form.js
 */
(function ($, App) {

    App.provide('Views.Tag.Form', App.Views.Core.Form.Model.extend({
        template:'DimeTimetrackerFrontendBundle:Tags:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#tag-form',
                model: this.model,
                backNavigation:'tag'
            });
            this.form.render();

            return this;
        }
    }));

})(jQuery, Dime);
