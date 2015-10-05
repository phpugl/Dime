'use strict';

/**
 * Dime - app/service/index.js
 */
(function ($, Backbone, _, App) {

    // Add menu item to main menu
    App.menu.get('admin').submenu.add({
        id:"service",
        title:"Services",
        route:"service",
        weight:0,
        callback:function () {
            App.menu.activateItem('admin.service');
            App.router.switchView(new App.Views.Service.Index());
        }
    });

    // Define Routes
    App.router.route("service/add", "service:add", function () {
        var model = new App.Model.Service();

        App.menu.activateItem('admin.service');
        App.router.switchView(new App.Views.Service.Form({
            model: model,
            title: 'Add Service'
        }));
    });
    App.router.route("service/:id/edit", "service:edit", function (id) {
        var model = new App.Model.Service({id:id});
        model.fetch({async:false});

        App.menu.activateItem('admin.service');
        App.router.switchView(new App.Views.Service.Form({
            model: model,
            title: 'Edit Service'
        }));
    });

    // Service view
    App.provide('Views.Service.Index', App.Views.Core.Content.extend({
        events: {
            'click .toggle-options': 'toggleOptions'
        },
        template:'DimeTimetrackerFrontendBundle:Services:index',
        initialize:function () {
            this.services = App.session.get('services', function () {
                return new App.Collection.Services();
            });
        },
        render:function () {
            // Render filter
            this.filter = new App.Views.Core.Form.Filter({
                el: '#service-filter',
                collection: this.services,
                name: 'service-filter'
            }).render();

            // Render pager
            this.pager = new App.Views.Core.Pager({
                el: '.pagination',
                collection: this.services,
                count: 25
            }).render();

            // Render service list
            this.list = new App.Views.Core.List({
                el:'#services',
                collection:this.services,
                prefix:'service-',
                emptyTemplate: '#tpl-service-empty',
                item:{
                    attributes:{ "class":"service box box-folded" },
                    tagName:"section",
                    template:'#tpl-service-item',
                    View:App.Views.Service.Item
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
            this.services.off();

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

            this.$('#service-filter').toggle();

            return this;
        }
    }));

})(jQuery, Backbone, _, Dime);
