'use strict';

/**
 * Dime - app/service/form.js
 */
(function ($, App) {

    App.provide('Views.Service.Form',App.Views.Core.Content.extend({
        template:'DimeTimetrackerFrontendBundle:Services:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#service-form',
                model: this.model,
                backNavigation:'service',
                widgets: {
                    alias: new App.Views.Core.Widget.Alias({
                        el: '#widget-alias',
                        for: '#service-name'
                    }),
                    tags: new App.Views.Core.Widget.Tags({
                        el: '#service-tags'
                    })
                }
            });
            this.form.render();

            return this;
        }
    }));

})(jQuery, Dime);
