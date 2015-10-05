'use strict';

/**
 * Dime - core/views/form.js
 */
(function ($, Backbone, App) {

    /**
     * App.Views.Core.Form.Model
     *
     * extend App.Views.Core.Content
     * bind model data to form item via prefix
     */
    App.provide('Views.Core.Form.Model', Backbone.View.extend({
        options: {
            events: {
                'click .save':'save',
                'click .close':'close',
                'click .cancel':'close',
                'submit': 'save'
            },
            backNavigation: undefined,
            ignore: {},
            rendered: false,
            widgets: {}
        },
        render: function() {
            // Load template
            if (this.options.template) {
                var html = App.render(this.options.template);
                this.$el.html(html);

                if (this.options.templateEl) {
                    this.setElement(this.options.templateEl);
                }
            }

            // Render ui items
            for(var name in this.options.widgets) {
                if (this.options.widgets.hasOwnProperty(name)) {
                    var widget = this.options.widgets[name];
                    // render widget
                    widget.render(this);

                    // fetch widget
                    if (widget.fetch) {
                        widget.fetch({ async: false });
                    }
                }
            }

            // bind model
            if (this.model) {
                this.bind(this.model.toJSON());
            }

            return this;
        },
        bind: function(data) {
            if (data) {
                // Bind data to form
                App.Helper.UI.Form.Bind(this.$el, data, this.ignore);

                // Bind data to widgets
                for(var name in this.options.widgets) {
                    if (this.options.widgets.hasOwnProperty(name)) {
                        var widget = this.options.widgets[name];

                        // fetch widget
                        if (widget.bind) {
                            widget.bind(data);
                        }
                    }
                }
            }
            return this;
        },
        close: function(e) {
            if (e) {
                e.preventDefault();
            }

            if (this.options.backNavigation) {
                App.router.navigate(this.options.backNavigation, { trigger:true });
            } else {
                this.$el.hide();
            }
        },
        save: function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            if (this.model) {
                var that = this,
                    data = this.serialize();

                this.$('.save').append(' <i class="icon loading-14-white"></i>');
                this.$('.cancel').attr('disabled', 'disabled');

                this.model.save(data, {
                    wait:true,
                    success:function () {
                        App.notify("Nice, all data are saved properly.", "success");
                        that.close();
                    },
                    error:function (model, response, scope) {
                        $('.save i.icon').remove();
                        $('.cancel').removeAttr('disabled');
                        var data = $.parseJSON(response.responseText);

                        if (data.errors) {
                            App.Helper.UI.Form.Error.Bind(that.$el, data.errors);
                            App.notify("Hey, you have missed some fields.", "error");
                        } else {
                            App.notify(response.status + ": " + response.statusText, "error");
                        }

                    }
                });
            }
        },
        serialize: function(withoutEmpty) {
            var data = App.Helper.UI.Form.Serialize(this.$el, this.options.ignore, withoutEmpty);

            // change data by widgets
            for(var name in this.options.widgets) {
                if (this.options.widgets.hasOwnProperty(name)) {
                    var widget = this.options.widgets[name];

                    if (widget.serialize) {
                        widget.serialize(data, withoutEmpty);
                    }
                }
            }

            return data;
        }
    }));

    /**
     * App.Views.Core.Form.Filter
     *
     * extend App.Views.Core.Form.Model
     */
    App.provide('Views.Core.Form.Filter', App.Views.Core.Form.Model.extend({
        options: {
            events:{
                'click .close': 'close',
                'click .reset': 'reset',
                'click .save': 'save',
                'click .submit': 'submit',
                'submit': 'submit'
            }
        },
        reset: function(e) {
            if (e) {
                e.preventDefault();
            }

            var settings = App.session.get('settings');

            if (settings) {
                var value = settings.getSetting('system', this.options.name);
                this.bind(value);
                this.submit();
            }

            this.$el.hide();
        },
        submit: function(e) {
            if (e) {
                e.preventDefault();
            }

            if (this.collection) {
                var data = this.serialize(true);
                this.collection.removeFetchData('filter');
                if (data) {
                    this.collection.addFetchData('filter', data);
                }
                this.collection.load();
            }
        },
        save: function(e) {
            if (e) {
                e.preventDefault();
            }

            var settings = App.session.get('settings');

            if (settings) {
                var models = settings.where({ name: this.options.name, namespace: 'system' }),
                    model,
                    data = this.serialize(true),
                    saveBtn = this.$('.save');

                saveBtn.append(' <i class="icon loading-14"></i>');

                if (models.length > 0) {
                    model = models[0];
                } else {
                    model = new App.Model.Setting({
                        namespace: 'system',
                        name: this.options.name,
                        value: ''
                    });
                    settings.add(model);
                }

                model.save({
                    value:JSON.stringify(data)
                }, {
                    wait:true,
                    success:function (model, response) {
                        saveBtn.find('i.icon').remove();
                        App.notify("This filter settings are saved properly.", "success");
                    },
                    error:function (model, response, scope) {
                        saveBtn.find('i.icon').remove();
                        App.notify(response.status + ": " + response.statusText, "error");
                    }
                });
                App.session.set(this.options.name, data);
            }
        }
    }));

})(jQuery, Backbone, Dime);
