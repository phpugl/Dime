'use strict';

/**
 * Dime - core/views/widgets/alias.js
 */
(function ($, Backbone, _, App) {

    App.provide('Views.Core.Widget.Alias', Backbone.View.extend({
        events:{
            'click .slugify':'slugify',
            'keypress .alias':'alias'
        },
        initialize: function(config) {
            if (config && config.for) {
                this.forElement = $(config.for);
            }
            this.aliasElement = this.$('.alias');
        },
        name: function() {
            return this.options.bind || this.el.name || this.el.id;
        },
        slugify:function (e) {
            if (e) {
                e.stopPropagation();
            }

            if (this.aliasElement) {
                this.aliasElement.val(App.Helper.Format.Slugify($.trim(this.forElement.val())));
            }
        },
        alias:function (e) {
            var keyCode = (e.keyCode) ? e.keyCode : e.which,
                keyChar = String.fromCharCode(keyCode);

            if ((keyCode == null) || $.inArray(keyCode, [0,8,9,13,27,37,39,46]) > -1) {
                return true;
            }

            if (keyChar.match(/[a-zA-Z0-9-_]/)) {
                return true;
            } else {
                return false;
            }
        }
    }));

})(jQuery, Backbone, _, Dime);
