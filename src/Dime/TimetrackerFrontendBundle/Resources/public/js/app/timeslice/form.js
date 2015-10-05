'use strict';

/**
 * Dime - app/timeslice/form.js
 */
(function ($, moment, App) {

    App.provide('Views.Timeslice.Form',  App.Views.Core.Content.extend({
        events: {
            'blur #timeslice-startedAt-date':'copyDate',
            'click .calculate':'calculation'
        },
        template:'DimeTimetrackerFrontendBundle:Timeslices:form',
        render: function() {
            if (this.options.title) {
                this.$('header.page-header h1').text(this.options.title)
            }

            this.form = new App.Views.Core.Form.Model({
                el: '#timeslice-form',
                model: this.model,
                backNavigation:'activity',
                widgets: {
                    startedAt: new App.Views.Core.Widget.DateTime({
                        el: '#widget-datetime-startedAt',
                        bind: 'startedAt'
                    }),
                    stoppedAt: new App.Views.Core.Widget.DateTime({
                        el: '#widget-datetime-stoppedAt',
                        bind: 'stoppedAt'
                    }),
                    tags: new App.Views.Core.Widget.Tags({
                        el: '#timeslice-tags'
                    })
                }
            });
            this.form.render();

            return this;
        },
        copyDate:function (e) {
            if (e) {
                e.stopPropagation();
            }

            var value = this.$('#timeslice-stoppedAt-date').val();
            if (!value) {
                this.$('#timeslice-stoppedAt-date').val(this.$('#timeslice-startedAt-date').val());
            }
        },
        calculation:function (e) {
            if (e) {
                e.stopPropagation();
            }

            var start = moment(this.form.options.widgets.startedAt.value(),'YYYY-MM-DD HH:mm:ss'),
                stop = moment(this.form.options.widgets.stoppedAt.value(),'YYYY-MM-DD HH:mm:ss'),
                duration = stop.diff(start, 'seconds');

            this.$('#timeslice-formatDuration').val(App.Helper.Format.Duration(duration));
        }
    }));

})(jQuery, moment, Dime);
