'use strict';

/**
 * Dime - app/report/index.js
 */
(function ($, Backbone, _, App) {

    App.menu.add({
        id:"report",
        title:"Report",
        route:"report",
        weight: 0,
        callback:function () {
            App.menu.activateItem('report');
            App.router.switchView(new App.Views.Report.Index());
        }
    });

    App.provide('Views.Report.Index', App.Views.Core.Content.extend({
        events: {
            'change #load-report': 'loadReport',
            'click .save-report': 'saveReport',
            'click .delete-report': 'deleteReport',
            'blur #show-order': 'checkOrder',
            'change .show-order': 'changeOrder',
            'change #show-merged': 'changeMerged',
            'change #show-tags': 'showTagOptions'
        },
        template:'DimeReportBundle:Reports:index',
        initialize:function () {
            this.timeslices = new App.Collection.Timeslices();
        },
        render: function() {
            var that = this;

            var settings = App.session.get('settings');
            this.reports = new Backbone.Collection(settings.where({ namespace: 'reports' }));

            this.loadReportWidget = new App.Views.Core.Widget.Select({
                el: '#load-report',
                collection: this.reports
            }).render();

            this.tableView = new App.Views.Report.Table({
                collection: this.timeslices
            }).render();

            this.form = new App.Views.Report.Form({
                el: '#report-form',
                collection: this.timeslices,
                widgets: {
                    dateperiod: new App.Views.Core.Widget.DatePeriod({
                        el: '#tab-date',
                        ui: {
                            period: '#period',
                            from: '#period-from',
                            fromGroup: '#period-from-group',
                            to: '#period-to',
                            toGroup: '#period-to-group'
                        },
                        events:{
                            'change #period': 'periodChange'
                        }
                    }),
                    customer: new App.Views.Core.Widget.Select({
                        el: '#customer',
                        collection: App.session.get('customer-filter-collection', function () {
                            return new App.Collection.Customers();
                        }),
                        events: {
                            'change': function(e) {
                                var projects = App.session.get('project-filter-collection', function () {
                                    return new App.Collection.Projects();
                                });

                                var value = this.value();
                                if (value != ''){
                                    projects.fetch({data: {filter: {customer: value}}});
                                } else {
                                    projects.fetch();
                                }
                            }
                        }
                    }),
                    project: new App.Views.Core.Widget.Select({
                        el: '#project',
                        collection: App.session.get('project-filter-collection', function () {
                            return new App.Collection.Projects();
                        })
                    }),
                    service: new App.Views.Core.Widget.Select({
                        el: '#service',
                        collection: App.session.get('service-filter-collection', function () {
                            return new App.Collection.Services();
                        })
                    }),
                    withTags: new App.Views.Core.Widget.Select({
                        el: '#withTags',
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        })
                    }),
                    withoutTags: new App.Views.Core.Widget.Select({
                        el: '#withoutTags',
                        collection: App.session.get('tag-filter-collection', function () {
                            return new App.Collection.Tags();
                        })
                    })
                },
                callback: function(show) {
                    that.tableView.setTableOptions(show);
                }
            });
            this.form.render();

            return this;
        },
        loadReport: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var source = this.$(e.currentTarget),
                value = source.val();

            if (value != '') {
                var report = this.reports.get(value);
                this.form.bind(report.get('value'));
                this.$('.delete-report').prop('disabled', false);
            } else {
                this.$('.delete-report').prop('disabled', true);
                this.form.reset();
            }

        },
        saveReport: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var data = this.form.serialize(true),
                that = this;

            if (!data.show.name) {
                App.notify("Hey, you have missed some fields.", "error");
                App.Helper.UI.Form.Error.Bind(this.form.$el, { "show-name": "Please set a name" } );
            } else {
                App.Helper.UI.Form.Error.Clear(this.form.$el);
                var settings = App.session.get('settings');
                if (settings) {
                    var models = settings.where({ name: data.show.name, namespace: 'reports' }),
                        model,
                        saveBtn = this.$('.save');

                    if (models.length > 0) {
                        model = models[0];
                    } else {
                        model = new App.Model.Setting({
                            namespace: 'reports',
                            name: data.show.name,
                            value: ''
                        });
                        this.reports.add(model);
                        settings.add(model);
                    }

                    model.save({
                        value:JSON.stringify(data)
                    }, {
                        wait:true,
                        success:function (model, response) {
                            saveBtn.find('i.icon').remove();
                            that.loadReportWidget.value(model.id);
                            App.notify("This report has been saved successfully.", "success");
                        },
                        error:function (model, response, scope) {
                            saveBtn.find('i.icon').remove();
                            App.notify(response.status + ": " + response.statusText, "error");
                        }
                    });
                }

            }
        },
        deleteReport: function(e) {
            if (e) {
                e.stopPropagation();
            }

            if (confirm("Are you sure?")) {
                var component = this.$('#load-report'),
                    value = component.val(),
                    settings = App.session.get('settings');

                if (value != '') {
                    var report = this.reports.get(value);
                    this.reports.remove(report);
                    settings.remove(report);
                    report.destroy();
                    this.form.reset();
                    this.$('.delete-report').prop('disabled', true);
                }
            }
        },
        checkOrder: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var source = this.$(e.currentTarget),
                value = source.val(),
                values = [],
                checked = [],
                chb = this.$('.show-order');

            if (value != '') {
                // create available names
                chb.each(function(index) {
                    values.push(this.value);
                    if (this.checked) {
                        checked.push(this.value);
                    }
                });

                // check the checkboxes
                var order = _.intersection(value.replace(/\s*,\s*/g,',').split(','), values);
                chb.each(function(index) {
                    if (_.indexOf(order, this.value) == -1) {
                        this.checked = false;
                    } else {
                        this.checked = true;
                    }
                });
                source.val(order.join(','));
            }
        },
        changeOrder: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var source = this.$(e.currentTarget),
                component = this.$('#show-order'),
                value = component.val();

            if (value != '') {
                value = value.split(',');
            } else {
                value = [];
            }

            if (source.prop('checked')) {
                value.push(source.val());
            } else {
                value = _.without(value, source.val());
            }

            component.val(value.join(','));
        },
        changeMerged: function(e) {
            if (e) {
                e.stopPropagation();
            }
            var value = e.currentTarget.value,
                date = this.$('#show-date'),
                start = this.$('#show-start'),
                stop = this.$('#show-stop');

            // reset
            _.each([date, start, stop], function(item) {
                if (item.data('checkState') !== undefined
                        && item.prop('checked') != item.data('checkState')) {
                    item.prop('checked', item.data('checkState'))
                        .removeData('checkState')
                        .trigger('change');
                }
                item.prop('disabled', false);
            }, this);

            switch (value) {
                case 'date':
                    _.each([start, stop], function(item) {
                        item.data('checkState', item.prop('checked'))
                            .prop({ 'checked': false, 'disabled': true })
                            .trigger('change');
                    }, this);
                    break;
                case 'description':
                    _.each([date, start, stop], function(item) {
                        item.data('checkState', item.prop('checked'))
                            .prop({ 'checked': false, 'disabled': true })
                            .trigger('change');
                    }, this);
                    break;
            }
        },
        showTagOptions: function(e) {
            if (e) {
                e.stopPropagation();
            }

            if (e.currentTarget.checked) {
                this.$('#show-tags-controls').show();
            } else {
                this.$('#show-tags-controls').hide();
            }
        }
    }));


})(jQuery, Backbone, _, Dime);
