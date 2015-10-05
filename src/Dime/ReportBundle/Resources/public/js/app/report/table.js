'use strict';

/**
 * Dime - app/report/table.js
 */
(function ($, Backbone, _, App) {

    /**
     * App.Helper.Format.DurationNumber
     *
     * @param data, a duration in given unit, e.g. seconds
     * @param format, format with http://numeraljs.com/
     * @return string formatted as Number
     */
    App.provide('Helper.Format.DurationNumber', function (data, format) {
        if (data !== undefined && _.isNumber(data)) {
            var duration = (data / 60) / 60;
            if (format != undefined) {
                return numeral(duration).format(format);
            }
            return duration;
        }
        return '';
    });

    App.provide('Model.Report.Timeslice', Backbone.Model.extend({
        duration: function(precision, unit) {
            var duration = this.get('duration');
            if (precision && unit) {
                var base = 0;
                switch (unit) {
                    case 's':
                        base = 1;
                        break;
                    case 'm':
                        base = 60;
                        break;
                    case 'h':
                        base = 3600;
                        break;
                }

                if (base > 0) {
                    if (precision > 0 && precision < 60) {
                        var part = base * precision,
                            future = Math.floor(duration / part) * part,
                            second = (duration % part);

                        if (second > 0 && second < part) {
                            future += part;
                        }
                        duration = future;
                    } else {
                        App.notify('Precision out of range [1,60]', 'error');
                    }
                }
            }

            return duration;
        },
        fuzzyStart: function(precision, unit) {
            switch (unit) {
                case 's':
                    return this.get('start').seconds(this.get('start').seconds() - this.get('start').seconds() % precision);
                case 'm':
                    return this.get('start').minutes(this.get('start').minutes() - this.get('start').minutes() % precision)
                        .seconds(0);
                case 'h':
                    return this.get('start').hours(this.get('start').hours() - this.get('start').hours() % precision)
                        .minutes(0)
                        .seconds(0);
                default:
                    return this.get('start');
            }
        },
        fuzzyStop: function(precision, unit) {
            return this.fuzzyStart(precision, unit).add('seconds', this.duration(precision, unit));
        },
        formatDuration:function (precision, unit) {
            return App.Helper.Format.Duration(this.duration(precision, unit));
        }
    }));

    App.provide('Collection.Report.Timeslices', Backbone.Collection.extend({
        duration: function(precision, unit) {
            var duration = 0;
            this.each(function(item) {
                if (item.get('duration')) {
                    duration += item.duration(precision, unit)
                }
            });
            return duration;
        },
        formatDuration:function (precision, unit) {
            return App.Helper.Format.Duration(this.duration(precision, unit));
        }
    }));

    App.provide('Views.Report.Table', Backbone.View.extend({
        template: '#tpl-report-table',
        el: '#report-results',
        events: {
            'click .save-tags': 'tagEntities'
        },
        initialize: function() {
            if (this.collection) {
                this.collection.on('add', this.render, this);
                this.collection.on('reset', this.render, this);
            }

            this.reset();
            this.tableOption = {};
        },
        reset: function() {
            this.timeslices = new App.Collection.Report.Timeslices();
            this.timeslices.comparator = function(first, second) {
                var firstDate = first.get('start'),
                    secondDate = second.get('start');

                if (firstDate && secondDate) {
                    if (firstDate.unix() > secondDate.unix()) {
                        return 1;
                    } else if (firstDate.unix() < secondDate.unix()) {
                        return -1;
                    } else {
                        return 0;
                    }
                } else {
                    return 1;
                }
            };
        },
        setTableOptions: function(options) {
            this.tableOption = options;
            if (this.tableOption && this.tableOption.order) {
                this.tableOption.order = this.tableOption.order.split(',');
            }
        },
        render: function() {
            var that = this;

            if (this.timeslices.length > 0) {
                this.reset();
            }
            if (this.collection && this.collection.length > 0) {
                this.collection.each(function(model) {
                    if (model && model.get('duration') && model.get('duration') > 0) {
                        var tags = [];

                        // Merge tags of Activities and Timeslices
                        if (that.tableOption.tags) {
                            _.each(that.tableOption.tags, function(item) {
                               switch (item) {
                                   case 'activity':
                                       tags = _.union(tags, model.getRelation('activity').getRelation('tags').tagArray());
                                       break;
                                   case 'timeslice':
                                       tags = _.union(tags, model.getRelation('tags').tagArray());
                                       break;
                               }
                            });
                        }

                        if (that.tableOption.merged) {
                            switch (that.tableOption.merged) {
                                case 'date':
                                    if (model.get('startedAt')) {
                                        var date = moment(model.get('startedAt'), 'YYYY-MM-DD HH:mm:ss');

                                        var ts = that.timeslices.get(date.format('YYYY-MM-DD'));
                                        if (ts) {
                                            ts.set('duration', ts.get('duration') + model.get('duration'));
                                        } else {
                                            ts = new App.Model.Report.Timeslice({
                                                id: date.format('YYYY-MM-DD'),
                                                date: date,
                                                description: model.get('activity.description', ''),
                                                duration: model.get('duration'),
                                                customerName: model.get('activity.customer.name', undefined),
                                                projectName: model.get('activity.project.name', undefined),
                                                tags: tags
                                            });
                                            that.timeslices.add(ts);
                                        }
                                    }
                                    break;
                                case 'description':
                                    var ts = that.timeslices.get(model.get('activity.id'));
                                    if (ts) {
                                        ts.set('duration', ts.get('duration') + model.get('duration'));
                                    } else {
                                        ts = new App.Model.Report.Timeslice({
                                            id: model.get('activity.id'),
                                            description: model.get('activity.description', ''),
                                            duration: model.get('duration'),
                                            customerName: model.get('activity.customer.name', undefined),
                                            projectName: model.get('activity.project.name', undefined),
                                            tags: tags
                                        });
                                        that.timeslices.add(ts);
                                    }
                                    break;
                            }
                        } else {
                            var date = model.get('startedAt');
                            if (!date) {
                                date = model.get('createdAt');
                            }

                            that.timeslices.add(new App.Model.Report.Timeslice({
                                date: moment(date, 'YYYY-MM-DD HH:mm:ss'),
                                start: model.get('startedAt') ? moment(model.get('startedAt'), 'YYYY-MM-DD HH:mm:ss') : undefined,
                                stop: model.get('stoppedAt') ? moment(model.get('stoppedAt'), 'YYYY-MM-DD HH:mm:ss') : undefined,
                                description: model.get('activity.description', ''),
                                duration: model.get('duration'),
                                customerName: model.get('activity.customer.name', undefined),
                                projectName: model.get('activity.project.name', undefined),
                                tags: tags
                            }));
                        }
                    }
                });
                this.$el.html(App.render(this.template, {opt: this.tableOption}));
            }
            this.update();

            return this;
        },
        update: function() {
            var that = this,
                thead = this.$('thead tr'),
                tbody = this.$('tbody'),
                tfoot = this.$('tfoot');

            thead.html('');
            _.each(this.tableOption.order, function(item) {
                thead.append(App.render(that.template + '-th-' + item, { opt: that.tableOption }));
            }, this);

            // reset
            tbody.html('');
            this.timeslices.each(function (model) {
                tbody.append(App.render(that.template + '-data', { opt: that.tableOption, model: model }));
            });


            var pos = _.indexOf(this.tableOption.order, 'duration');
            if (pos === -1) {
                pos = _.indexOf(this.tableOption.order, 'durationNumber');
            }

            if (pos > -1) {
                tfoot.html(
                    App.render(that.template + '-tfoot', {
                        opt: that.tableOption,
                        duration: this.timeslices.duration(this.tableOption.precision, this.tableOption.precisionUnit),
                        cols: pos
                    })
                );
            }
        },
        tagEntities: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var data = App.Helper.UI.Form.Serialize(this.$('#massive-tagging'), true);

            if (this.collection && data && data.tags) {
                var tags = App.Helper.Tags.Split(data.tags),
                    activities = {};
                this.collection.each(function(model) {
                    if (model && model.get('duration') && model.get('duration') > 0) {
                        if (data.what === 'all' || data.what === 'timeslices') {
                            App.Helper.Tags.Update(model, tags);
                        }

                        if (data.what === 'all' || data.what === 'activities') {
                            var activitiy = model.getRelation('activity');
                            if (!activities[activitiy.id]) {
                                activities[activitiy.id] = true;
                                App.Helper.Tags.Update(activitiy, tags);
                            }
                        }
                    }
                }, this);
            }
        }
    }));

})(jQuery, Backbone, _, Dime);
