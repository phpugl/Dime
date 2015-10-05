'use strict';

/**
 * Dime - views/service.item.js
 */
(function ($, App) {

    // Create item view in App.Views.Service
    App.provide('Views.Service.Item', App.Views.Core.ListItem.extend({
        events:{
            'click .edit':'edit',
            'click .delete':'delete',
            'click':'showDetails'
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
        showDetails:function () {
            this.$el.toggleClass('box-folded box-unfolded');
        },
        edit:function (e) {
            e.stopPropagation();
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
