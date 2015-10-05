'use strict';

/**
 * Dime - app/project/form.js
 */
(function ($, App) {

    App.provide('Views.Project.Form', App.Views.Core.Content.extend({
        template:'DimeTimetrackerFrontendBundle:Projects:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#project-form',
                model: this.model,
                backNavigation:'project',
                widgets: {
                    alias: new App.Views.Core.Widget.Alias({
                        el: '#widget-alias',
                        for: '#project-name'
                    }),
                    tags: new App.Views.Core.Widget.Tags({
                        el: '#project-tags'
                    }),
                    customer: new App.Views.Core.Widget.Select({
                        el: '#project-customer',
                        collection: App.session.get('customer-filter-collection', function () {
                            return new App.Collection.Customers();
                        })
                    })
                }
            });
            this.form.render();

            return this;
        }
    }));

})(jQuery, Dime);
