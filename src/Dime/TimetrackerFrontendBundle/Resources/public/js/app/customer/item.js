'use strict';

/**
 * Dime - app/customer/item.js
 */
(function ($, App) {

    // Create item view in App.Views.Customer
    App.provide('Views.Customer.Item', App.Views.Core.ListItem.extend({
        events:{
            'click .delete':'delete',
            'click .edit':'edit'
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
        edit:function (e) {
            e.stopPropagation();
            App.router.navigate('#customer/' + this.model.id + '/edit', { trigger:true });
        },
        'delete':function (e) {
            e.preventDefault();
            e.stopPropagation();

            this.model.bind('destroy', this.remove, this);

            // confirm destroy action
            if (confirm("Are you sure?")) {
                this.model.destroy({wait:true});
            }
        }
    }));

})(jQuery, Dime);
