'use strict';

/**
 * Dime - core/views/content.js
 */
(function ($, Backbone, App) {

    /**
     * Dime.Views.Core.Content
     *
     * extended Backbone.View with remove-function
     */
    App.provide('Views.Core.Content', Backbone.View.extend({
        tagName:'div',
        attributes:{
            'class':'content-view'
        },
        remove:function () {
            // remove element from DOM
            this.$el.empty().detach();

            return this;
        }
    }));

})(jQuery, Backbone, Dime);
