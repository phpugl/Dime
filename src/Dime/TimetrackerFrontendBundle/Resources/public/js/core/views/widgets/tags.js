'use strict';

/**
 * Dime - core/views/widgets/tags.js
 */
(function ($, Backbone, _, App) {

    App.provide('Views.Core.Widget.Tags', Backbone.View.extend({
        options: {
            delimiter: ' '
        },
        bind: function(data) {
            if (data && data.relation && data.relation.tags) {
                this.$el.val(data.relation.tags.pluck('name').join(this.options.delimiter));
            } else {
                this.$el.val('');
            }

            return this;
        },
        name: function() {
            return this.options.bind || this.el.name;
        },
        serialize: function(data, withoutEmpty) {
            var name = this.name().split('-'),
                value = this.value();

            if (withoutEmpty) {
                if (value === undefined || _.isEmpty(value)) {
                    return this;
                }
            }

            if (data) {
                App.Helper.Object.Set(data, name, value);
            }

            return this;
        },
        value: function(value) {
            if (value) {
                this.$el.val(value);
            }

            value = $.trim(this.$el.val());

            if (value && value.length > 0) {
                value = _.compact(value.split(this.options.delimiter));
            } else {
                value = []
            }

            return value;
        }
    }));

})(jQuery, Backbone, _, Dime);
