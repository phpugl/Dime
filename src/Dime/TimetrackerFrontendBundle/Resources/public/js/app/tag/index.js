'use strict';

/**
 * Dime - app/tag/index.js
 */
(function ($, App) {

    // Add menu item to main menu
    App.menu.get('admin').submenu.add({
        id:"tag",
        title:"Tags",
        route:"tag",
        weight:0,
        callback:function () {
            App.menu.activateItem('admin.tag');
            App.router.switchView(new App.Views.Tag.Index());
        }
    });

    // Define Routes
    App.router.route("tag/add", "tag:add", function () {
        var model = new App.Model.Tag();

        App.menu.activateItem('admin.tag');
        App.router.switchView(new App.Views.Tag.Form({
            model: model,
            title: 'Add Tag'
        }));
    });
    App.router.route("tag/:id/edit", "tag:edit", function (id) {
        var model = new App.Model.Tag({id:id});
        model.fetch({async:false});

        App.menu.activateItem('admin.tag');
        App.router.switchView(new App.Views.Tag.Form({
            model: model,
            title: 'Edit Tag'
        }));
    });

    // Tag view
    App.provide('Views.Tag.Index', App.Views.Core.Content.extend({
        events: {
            'click .toggle-options': 'toggleOptions'
        },
        template:'DimeTimetrackerFrontendBundle:Tags:index',
        initialize:function () {
            this.tags = App.session.get('tags', function () {
                return new App.Collection.Tags();
            });
        },
        render:function () {
            // Render filter
            this.filter = new App.Views.Core.Form.Filter({
                el: '#tag-filter',
                collection: this.tags,
                name: 'tag-filter'
            }).render();

            // Render pager
            this.pager = new App.Views.Core.Pager({
                el: '.pagination',
                collection: this.tags,
                count: 25
            }).render();

            // Render tag list
            this.list = new App.Views.Core.List({
                el:'#tags',
                collection:this.tags,
                prefix:'tag-',
                emptyTemplate: '#tpl-tag-empty',
                item:{
                    attributes:{ "class":"tag box" },
                    tagName:'section',
                    template:'#tpl-tag-item',
                    View:App.Views.Tag.Item
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
            this.tags.off();

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

            this.$('#tag-filter').toggle();

            return this;
        }
    }));

})(jQuery, Dime);
