'use strict';

/**
 * Dime - app/tag/item.js
 */
(function ($, _, moment, App) {

  // tag item view
    App.provide('Views.Tag.Item', App.Views.Core.ListItem.extend({
        events: {
            'click .delete': 'delete',
            'click .edit': 'edit'
        },
        render:function () {
            // Call parent contructor
            App.Views.Core.ListItem.prototype.render.call(this);

            // activate contenteditable
            var ce = new App.Views.Core.Editor({
                el:this.el,
                model:this.model
            }).render();

            return this;
        },
        edit: function(e) {
            e.stopPropagation();
            App.router.navigate('#tag/' + this.model.id + '/edit', { trigger: true });
        },
        'delete': function(e) {
            e.preventDefault();
            e.stopPropagation();

            this.model.bind('destroy', this.remove, this);

            // confirm destroy action
            if (window.confirm("Are you sure?")) {
                this.model.destroy({wait: true});
            }
        }
    }));

})(jQuery, _, moment, Dime);

