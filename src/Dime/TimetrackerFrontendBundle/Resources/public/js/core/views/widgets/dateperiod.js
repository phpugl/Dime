'use strict';

/**
 * Dime - core/views/widgets/dateperiod.js
 */
(function ($, Backbone, _, moment, App) {

    // Create Filter view in App.Views.Core
    App.provide('Views.Core.Widget.DatePeriod', Backbone.View.extend({
        options: {
            bind: 'filter-date',
            format: {
                day: 'YYYY-MM-DD',
                month: 'YYYY-MM',
                year: 'YYYY'
            },
            ui: {
                period: '#filter-period',
                from: '#filter-period-from',
                fromGroup: '#filter-period-from-group',
                to: '#filter-period-to',
                toGroup: '#filter-period-to-group'
            },
            events:{
                'change #filter-period': 'periodChange'
            }
        },
        render: function(parent) {
            if (this.options.templateEl) {
                this.setElement(this.options.templateEl);
            }

            // find elements
            this.periodComponent = this.$(this.options.ui.period);
            this.fromComponent = this.$(this.options.ui.from);
            this.fromComponent.datepicker('hide');
            this.fromGroupComponent = this.$(this.options.ui.fromGroup);
            this.toComponent = this.$(this.options.ui.to);
            this.toComponent.datepicker('hide');
            this.toGroupComponent = this.$(this.options.ui.toGroup);

            return this;
        },
        bind: function (data) {
            if (data) {
                var name = this.name().split('-'),
                    val = undefined;

                if (name) {
                    val = App.Helper.Object.Get(data, name);
                }

                this.value(this.periodComponent.val(),  val);
            }
            return this;
        },
        name: function() {
            return this.options.bind || this.el.name || this.el.id;
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
        value: function(period, value) {
            if (value) {
                if (_.isArray(value)) {
                    if (value.length > 1) {
                        this.toComponent.val(value[1]);
                    } else {
                        this.toComponent.val('');
                    }
                    this.fromComponent.val(value[0]);
                } else {
                    this.fromComponent.val(value);
                    this.toComponent.val('');
                }
            }

            if (period) {
                this.periodComponent.val(period);
                this.periodComponent.trigger('change');
            }

            value = undefined;
            var selection = this.periodComponent.val(),
                from = moment(this.fromComponent.data('date')).clone(),
                to = moment(this.toComponent.data('date')).clone();

            switch (selection) {
                case 'this-month':
                    value = moment().format(this.options.format.month);
                    break;
                case 'last-month':
                    value = moment().subtract('months', 1).format(this.options.format.month);
                    break;
                case 'last-4-weeks':
                    from = moment().subtract('weeks', 4);
                    to = moment();
                    value = [
                        from.format(this.options.format.day),
                        to.format(this.options.format.day)
                    ];
                    break;
                case 'this-week':
                    from = moment();
                    if (from.day() === 0) {
                        from = from.subtract('days', 1);
                    }
                    value = [
                        from.day(1).format(this.options.format.day),
                        from.day(7).format(this.options.format.day)
                    ];
                    break;
                case 'last-week':
                    from = moment().subtract('weeks', 1);
                    if (from.day() === 0) {
                        from = from.subtract('days', 1);
                    }
                    value = [
                        from.day(1).format(this.options.format.day),
                        from.day(7).format(this.options.format.day)
                    ];
                    break;
                case 'today':
                    value = moment().format(this.options.format.day);
                    break;
                case 'yesterday':
                    value = moment().subtract('days', 1).format(this.options.format.day);
                    break;
                case 'D':
                    value = from.format(this.options.format.day);
                    break;
                case 'W':
                    if (from.day() === 0) {
                        from = from.subtract('days', 1);
                    }
                    value = [from.day(1).format(dayFormat), from.day(7).format(dayFormat)];
                    break;
                case 'M':
                    value = from.format(this.options.format.month);
                    break;
                case 'Y':
                    value = from.format(this.options.format.year);
                    break;
                case 'R':
                    value = [ from.format(this.options.format.day), to.format(this.options.format.day) ];
                    break;
            }

            return value;
        },
        periodChange: function(e) {
            if (e) {
                e.preventDefault();
            }

            var selection = e.currentTarget.value;

            this.fromComponent.attr({ placeholder: this.options.format.day });
            this.fromComponent.data({
                'date-format': this.options.format.day,
                'date-period': 'D'
            });
            this.fromGroupComponent.hide();

            this.toComponent.attr({ placeholder: this.options.format.day });
            this.toComponent.data({
                'date-format': this.options.format.day,
                'date-period': 'D'
            });
            this.toGroupComponent.hide();

            switch (selection) {
                case 'this-month':
                    this.fromComponent.attr({
                        placeholder: this.options.format.month
                    }).data({
                        'date-format': this.options.format.month,
                        'date-period': 'M'
                    });
                    this.fromComponent.val(moment().format(this.options.format.month));
                    this.toComponent.val('');
                    break;
                case 'last-month':
                    this.fromComponent.attr({
                        placeholder: this.options.format.month
                    }).data({
                            'date-format': this.options.format.month,
                            'date-period': 'M'
                        });
                    this.fromComponent.val(moment().subtract('months', 1).format(this.options.format.month));
                    this.toComponent.val('');
                    break;
                case 'last-4-weeks':
                    var from = moment();
                    var to = from.subtract('weeks', 4);
                    this.fromComponent.val(from.format(this.options.format.day));
                    this.toComponent.val(to.format(this.options.format.day));
                    break;
                case 'this-week':
                    var from = moment();
                    if (from.day() === 0) {
                        from = from.subtract('days', 1);
                    }
                    this.fromComponent.val(from.day(1).format(this.options.format.day));
                    this.toComponent.val(from.day(7).format(this.options.format.day));
                    break;
                case 'last-week':
                    var from = moment().subtract('weeks', 1);
                    if (from.day() === 0) {
                        from = date.subtract('days', 1);
                    }
                    this.fromComponent.val(from.day(1).format(this.options.format.day));
                    this.toComponent.val(from.day(7).format(this.options.format.day));
                    break;
                case 'today':
                    this.fromComponent.val(moment().format(this.options.format.day));
                    this.toComponent.val('');
                    break;
                case 'yesterday':
                    this.fromComponent.val(moment().subtract('days', '1').format(this.options.format.day));
                    this.toComponent.val('');
                    break;
                case 'D':
                    this.fromGroupComponent.show();
                    this.toGroupComponent.hide();
                    break;
                case 'W':
                    this.fromGroupComponent.show();
                    this.toGroupComponent.show();
                    break;
                case 'M':
                    this.fromComponent.attr({
                        placeholder: this.options.format.month
                    }).data({
                        'date-format': this.options.format.month,
                        'date-period': 'M'
                    });
                    this.fromGroupComponent.show();
                    this.toGroupComponent.hide();
                    break;
                case 'Y':
                    this.fromComponent.attr({
                        placeholder: this.options.format.year
                    }).data({
                        'date-format': this.options.format.year,
                        'date-period': 'Y'
                    });

                    this.fromGroupComponent.show();
                    this.toGroupComponent.hide();
                    break;
                case 'R':
                    this.fromGroupComponent.show();
                    this.toGroupComponent.show();
                    break;
            }

            if (this.fromComponent.data('datepicker')) {
                this.fromComponent.data('datepicker').update();
                this.fromComponent.data('datepicker').setValue();
            }
            if (this.toComponent.data('datepicker')) {
                this.toComponent.data('datepicker').update();
                this.toComponent.data('datepicker').setValue();
            }
        }
    }));

})(jQuery, Backbone, _, moment, Dime);
