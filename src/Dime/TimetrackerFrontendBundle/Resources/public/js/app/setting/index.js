'use strict';

/**
 * Dime - app/setting/index.js
 */
(function ($, Backbone, _, App) {

    // Add menu item to main menu
    App.menu.get('admin').submenu.add({
        id:"setting",
        title:"Settings",
        route:"setting",
        weight:0,
        callback:function () {
            App.menu.activateItem('admin.setting');
            App.router.switchView(new App.Views.Setting.Index());
        }
    });

    // Define Routes
    App.router.route("setting/add", "setting:add", function () {
        var model = new App.Model.Setting();

        App.menu.activateItem('admin.setting');
        App.router.switchView(new App.Views.Setting.Form({
            model: model,
            title: 'Add Setting'
        }));
    });
    App.router.route("setting/:id/edit", "setting:edit", function (id) {
        var model = new App.Model.Setting({id:id});
        model.fetch({async:false});

        App.menu.activateItem('admin.setting');
        App.router.switchView(new App.Views.Setting.Form({
            model: model,
            title: 'Edit Setting'
        }));
    });

    // Setting view
    App.provide('Views.Setting.Index', App.Views.Core.Content.extend({
        events: {
            'click .toggle-options': 'toggleOptions'
        },
        template:'DimeTimetrackerFrontendBundle:Settings:index',
        initialize:function () {
            this.settings = App.session.get('settings', function () {
                return new App.Collection.Settings();
            });
        },
        render:function () {
            // Render filter
            this.filter = new App.Views.Core.Form.Filter({
                el: '#setting-filter',
                collection: this.settings,
                name: 'setting-filter'
            }).render();

            // Render pager
            this.pager = new App.Views.Core.Pager({
                el: '.pagination',
                collection: this.settings,
                count: 25
            }).render();

            // Render setting list
            this.list = new App.Views.Core.List({
                el:'#settings',
                collection:this.settings,
                prefix:'setting-',
                emptyTemplate: '#tpl-setting-empty',
                item:{
                    tagName:"tr",
                    template:'#tpl-setting-item',
                    View:App.Views.Setting.Item
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
            this.settings.off();

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

            this.$('#setting-filter').toggle();

            return this;
        }
    }));

})(jQuery, Backbone, _, Dime);
