'use strict';

/**
 * Dime - app/timeslice/item.js
 */
(function ($, App) {

    // activity item view
    App.provide('Views.Timeslice.Item', Backbone.View.extend({
        template:'#tpl-timeslice-item',
        events:{
            'click .edit':'edit',
            'click .delete':'delete'
        },
        options:{
            prefix:'timeslice-'
        },
        elId:function () {
            var id = this.$el.attr('id');
            return (id) ? id : this.options.prefix + this.model.get('id');
        },
        initialize:function (config) {
            this.model.bind('destroy', this.remove, this);
        },
        render:function () {
            var html = App.render(this.template, { model: this.model });

            // fill model date into template and push it into element html
            this.$el.html(html);

            // add element id with prefix
            this.$el.attr('id', this.elId());

            return this;
        },
        edit:function (e) {
            if (e) {
                e.stopPropagation();
            }
        },
        'delete':function (e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // confirm destroy action
            if (confirm("Are you sure?")) {
                this.model.destroy({wait:true});
                this.model.collection.remove(this.model);
            }
        }
    }));

})(jQuery, Dime);
