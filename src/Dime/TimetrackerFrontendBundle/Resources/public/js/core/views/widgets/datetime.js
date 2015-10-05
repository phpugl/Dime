'use strict';

/**
 * Dime - core/views/widgets/datetime.js
 */
(function ($, Backbone, _, moment, App) {

    App.provide('Views.Core.Widget.DateTime', Backbone.View.extend({
        options: {
            format: {
                date: 'YYYY-MM-DD',
                time: 'HH:mm:ss'
            }
        },
        name: function() {
            return this.options.bind || this.el.name || this.el.id;
        },
        render: function(parent) {
            // find elements
            this.dateComponent = this.$('.date');
            this.timeComponent = this.$('.time');
            return this;
        },
        bind: function (data) {
            if (data) {
                var name = this.name().split('-'),
                    val = undefined;

                if (name) {
                    val = App.Helper.Object.Get(data, name);
                }

                this.value(val);
            }
            return this;
        },
        serialize: function(data, withoutEmpty) {
            var value = this.value();

            if (withoutEmpty) {
                if (value === undefined || _.isEmpty(value)) {
                    return this;
                }
            }

            if (data) {
                App.Helper.Object.Set(data, this.name().split('-'), value);
            }

            return this;

        },
        value: function(value) {
            if (value) {
                value = moment(value);

                this.dateComponent.val(value.format(this.options.format.date));
                this.timeComponent.val(value.format(this.options.format.time));
            }

            var date = this.dateComponent.val(),
                time = this.timeComponent.val();

            value = undefined;
            if (date !== undefined && !_.isEmpty(date)
                && time !== undefined && !_.isEmpty(time)) {
                value = moment(date + ' ' + time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
            }

            return value;
        }
    }));

})(jQuery, Backbone, _, moment, Dime);
