'use strict';

/**
 * Dime - app/customer/index.js
 */
(function ($, App) {

    // Add menu item to main menu
    App.menu.get('admin').submenu.add({
        id:"customer",
        title:"Customers",
        route:"customer",
        weight:0,
        callback:function () {
            App.menu.activateItem('admin.customer');
            App.router.switchView(new App.Views.Customer.Index());
        }
    });

    // Define Routes
    App.router.route("customer/add", "customer:add", function () {
        var model = new App.Model.Customer();

        App.menu.activateItem('admin.customer');
        App.router.switchView(new App.Views.Customer.Form({
            model: model,
            title: 'Add Customer'
        }));
    });
    App.router.route("customer/:id/edit", "customer:edit", function (id) {
        var model = new App.Model.Customer({id: id});
        model.fetch({ async:false });

        App.menu.activateItem('admin.customer');
        App.router.switchView(new App.Views.Customer.Form({
            model: model,
            title: 'Edit Customer'
        }));
    });

    // Customer view
    App.provide('Views.Customer.Index', App.Views.Core.Content.extend({
        events: {
            'click .toggle-options': 'toggleOptions'
        },
        template:'DimeTimetrackerFrontendBundle:Customers:index',
        initialize:function () {
            this.customers = App.session.get('customers', function () {
                return new App.Collection.Customers();
            });
        },
        render:function () {
            // Render filter
            this.filter = new App.Views.Core.Form.Filter({
                el: '#customer-filter',
                collection: this.customers,
                name: 'customer-filter',
                widgets: {
                    withTags: new App.Views.Core.Widget.Select({
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        }),
                        templateEl: '#filter-withTags',
                        blankText: 'with tag'
                    }),
                    withoutTags: new App.Views.Core.Widget.Select({
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        }),
                        templateEl: '#filter-withoutTags',
                        blankText: 'without tag'
                    })
                }
            }).render();

            // Render pager
            this.pager = new App.Views.Core.Pager({
                el: '.pagination',
                collection: this.customers,
                count: 25
            }).render();

            // Render customer list
            this.list = new App.Views.Core.List({
                el:'#customers',
                collection:this.customers,
                prefix:'customer-',
                emptyTemplate: '#tpl-customer-empty',
                item:{
                    attributes:{ "class":"customer box" },
                    tagName:'section',
                    template:'#tpl-customer-item',
                    View:App.Views.Customer.Item
                }
            }).render();

            // fetch filter settings
            var settings = App.session.get('settings');
            if (settings && settings.hasSetting('system', this.filter.options.name)) {
                this.filter.bind(settings.getSetting('system', this.filter.options.name));
            }
            this.filter.submit();

            return this;
        },
        remove:function () {
            // Unbind events
            this.customers.off();

            this.list.remove();
            this.filter.remove();
            this.pager.remove();

            // remove element from DOM
            this.$el.empty().detach();

            return this;
        },
        toggleOptions: function(e) {
            if (e) {
                e.stopPropagation();
            }

            this.$('#customer-filter').toggle();

            return this;
        }
    }));

})(jQuery, Dime);
