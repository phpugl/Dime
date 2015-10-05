'use strict';

/**
 * Dime - app/customer/form.js
 */
(function ($, App) {

    App.provide('Views.Customer.Form', App.Views.Core.Content.extend({
        template:'DimeTimetrackerFrontendBundle:Customers:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#customer-form',
                model: this.model,
                backNavigation:'customer',
                widgets: {
                    alias: new App.Views.Core.Widget.Alias({
                        el: '#widget-alias',
                        for: '#customer-name'
                    }),
                    tags: new App.Views.Core.Widget.Tags({
                        el: '#customer-tags'
                    })
                }
            });
            this.form.render();

            return this;
        }
    }));

})(jQuery, Dime);
