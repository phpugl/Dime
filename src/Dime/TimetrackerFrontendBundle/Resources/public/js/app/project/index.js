'use strict';

/**
 * Dime - app/project/index.js
 */
(function ($, App) {

    // Add menu item to main menu
    App.menu.get('admin').submenu.add({
        id:"project",
        title:"Projects",
        route:"project",
        weight:0,
        callback:function () {
            App.menu.activateItem('admin.project');
            App.router.switchView(new App.Views.Project.Index());
        }
    });

    // Define Routes
    App.router.route("project/add", "project:add", function () {
        var model = new App.Model.Project();

        App.menu.activateItem('admin.project');
        App.router.switchView(new App.Views.Project.Form({
            model: model,
            title: 'Add project'
        }));
    });
    App.router.route("project/:id/edit", "project:edit", function (id) {
        var model = new App.Model.Project({id:id});
        model.fetch({async:false});

        App.menu.activateItem('admin.project');
        App.router.switchView(new App.Views.Project.Form({
            model: model,
            title: 'Edit project'
        }));
    });

    // Project view
    App.provide('Views.Project.Index', App.Views.Core.Content.extend({
        events: {
            'click .toggle-options': 'toggleOptions'
        },
        template:'DimeTimetrackerFrontendBundle:Projects:index',
        initialize:function () {
            this.projects = App.session.get('projects', function () {
                return new App.Collection.Projects();
            });
        },
        render:function () {
            // Render filter
            this.filter = new App.Views.Core.Form.Filter({
                el: '#project-filter',
                collection: this.projects,
                name: 'project-filter',
                widgets: {
                    customer: new App.Views.Core.Widget.Select({
                        el: '#filter-customer',
                        collection: App.session.get('customer-filter-collection', function () {
                            return new App.Collection.Customers();
                        }),
                        blankText: 'by customer'
                    }),
                    withTags: new App.Views.Core.Widget.Select({
                        el: '#filter-withTags',
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        }),
                        blankText: 'with tag'
                    }),
                    withoutTags: new App.Views.Core.Widget.Select({
                        el: '#filter-withoutTags',
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        }),
                        blankText: 'without tag'
                    })
                }
            }).render();

            // Render pager
            this.pager = new App.Views.Core.Pager({
                el: '.pagination',
                collection: this.projects,
                count: 25
            }).render();

            // Create project list
            this.list = new App.Views.Core.List({
                el:'#projects',
                collection:this.projects,
                prefix:'project-',
                emptyTemplate: '#tpl-project-empty',
                groupBy: {
                    key: 'customer.name',
                    template: '#tpl-project-header',
                    'undefined': 'No customer'
                },
                item:{
                    attributes:{ "class":"project box box-folded" },
                    tagName:"section",
                    template:'#tpl-project-item',
                    View:App.Views.Project.Item
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
            this.projects.off();

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

            this.$('#project-filter').toggle();

            return this;
        }
    }));

})(jQuery, Dime);
