'use strict';

/**
 * Dime - app/activity/form.js
 */
(function ($, App) {

    App.provide('Views.Activity.Form',  App.Views.Core.Content.extend({
        events: {
            'change #activity-customer': 'customerChange',
            'change #activity-project':'projectChange',
            'change #activity-rate':'rateChange',
            'change #activity-service':'setPrice'
        },
        template:'DimeTimetrackerFrontendBundle:Activities:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#activity-form',
                model: this.model,
                backNavigation:'activity',
                widgets: {
                    customer: new App.Views.Core.Widget.Select({
                        el: '#activity-customer',
                        collection: App.session.get('customer-filter-collection', function () {
                            return new App.Collection.Customers();
                        })
                    }),
                    project: new App.Views.Core.Widget.Select({
                        el: '#activity-project',
                        collection: App.session.get('project-filter-collection', function () {
                            return new App.Collection.Projects();
                        })
                    }),
                    service: new App.Views.Core.Widget.Select({
                        el: '#activity-service',
                        collection: App.session.get('service-filter-collection', function () {
                            return new App.Collection.Services();
                        })
                    }),
                    tags: new App.Views.Core.Widget.Tags({
                        el: '#activity-tags'
                    })
                }
            });
            this.form.render();

            return this;
        },
        customerChange: function(e) {
            if (e) {
                e.preventDefault();

                var $component = $(e.currentTarget);

                if ($component.val() !== '') {
                    this.form.options.widgets.project.collection.fetch({ data: { filter: { customer: $component.val() } }, wait: true });
                } else {
                    this.form.options.widgets.project.collection.fetch();
                }
            }
            return this;
        },
        projectChange: function(e) {
            if (e) {
                e.preventDefault();

                var $component = $(e.currentTarget);

                if ($component.val() !== '') {
                    var project = this.form.options.widgets.project.collection.get($component.val());
                    this.form.options.widgets.customer.value(project.get('customer'));
                }
                this.setPrice();
            }
            return this;
        },
        rateChange: function(e) {
            if (e) {
                e.preventDefault();

                var $component = $(e.currentTarget),
                    rateRef = this.$('#activity-rateReference');

                if ($component.val() === '') {
                    rateRef.val('');
                } else {
                    rateRef.val('manual');
                }
            }
            return this;
        },
        setPrice: function(e) {
            if (e) {
                e.preventDefault();
            }

            var rate = this.$('#activity-rate'),
                rateRef = this.$('#activity-rateReference'),
                rateRefValue = rateRef.val();

            if (rateRefValue == '' || rateRefValue.search(/service|customer|project/) != -1) {
                if (this.form.options.widgets.service.value()) {
                    var service = this.form.options.widgets.service.collection.get(this.form.options.widgets.service.value());
                    if (service.get('rate')) {
                        rate.val(service.get('rate'));
                        rateRef.val('service');
                    }
                } else {
                    rate.val('');
                    rateRef.val('');
                }
                if (this.form.options.widgets.project.value()) {
                    var project = this.form.options.widgets.project.collection.get(this.form.options.widgets.project.value());
                    if (project.get('rate')) {
                        rate.val(project.get('rate'));
                        rateRef.val('project');
                    }
                }
            }

            return this;
        }
    }));

})(jQuery, Dime);
