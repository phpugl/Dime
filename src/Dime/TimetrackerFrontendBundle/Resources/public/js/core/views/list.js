'use strict';

/**
 * Dime - core/views/list.js
 */
(function ($, Backbone, _, App) {

    // Create list item view in App.Views.Core
    App.provide('Views.Core.ListItem', Backbone.View.extend({
        prefix:'',
        initialize:function (config) {
            if (config && config.template) {
                this.template = config.template;
            }
        },
        elId:function () {
            var id = this.$el.attr('id');
            return (id) ? id : this.options.prefix + this.model.get('id');
        },
        render:function () {
            // grep template with jquery and generate template stub
            var html = App.render(this.template, { model:this.model });

            // fill model date into template and push it into element html
            this.$el.html(html);

            // add element id with prefix
            this.$el.attr('id', this.elId());

            return this;
        },
        remove:function () {
            // remove element from DOM
            this.$el.empty().detach();
        }
    }));


    // provide list view in App.Views.Core
    App.provide('Views.Core.List', Backbone.View.extend({
        options:{
            emptyTemplate: false,
            prefix:'',
            item:{
                attributes:{},
                prepend:false,
                prependNew:false,
                tagName:'div',
                template:'',
                View:App.Views.Core.ListItem
            }
        },
        initialize:function (config) {
            // Assign function to collection events
            if (this.collection) {
                this.collection.on('reset', this.addAll, this);
                this.collection.on('sort', this.addAll, this);
                this.collection.on('add', this.addItem, this);
                this.collection.on('change', this.changeItem, this);
                this.collection.on('remove', this.removeItem, this);
            }
        },
        render:function (parent) {
            // grep template with jquery and generate template stub
            if (this.options.template) {
                var html = App.render(this.options.template, { model:this.model });

                // fill model date into template and push it into element html
                this.$el.html(html);
            }

            if (this.options.templateEl) {
                this.setElement(this.options.templateEl);
            }

            if (this.collection) {
                this.addAll();
            }

            return this;
        },
        groupBy: function(opt) {
            this.options.groupBy = opt;
            this.addAll();
        },
        renderItem:function (model) {
            return new this.options.item.View({
                model:model,
                collection: this.collection,
                prefix:this.options.prefix,
                attributes:this.options.item.attributes,
                tagName:this.options.item.tagName,
                template:this.options.item.template
            }).render();
        },
        remove:function () {
            if (this.collection) {
                this.collection.off();
            }
            return this;
        },
        addAll: function () {
            var tEmpty = '', that = this;

            if (this.options.emptyTemplate) {
                tEmpty = App.render(this.options.emptyTemplate);
            }

            // remove all content
            this.$el.addClass('loading').html('');

            // run addItem on each collection item
            if (this.collection && this.collection.length > 0) {
                this.options.isEmpty = false;

                if (this.options.groupBy && that.options.groupBy.key) {
                    var groupTemplate = App.render(this.options.groupBy.template),
                        items = this.collection.groupBy(that.options.groupBy.key),
                        sorted = _.without(_.keys(items), 'undefined').sort();

                    for (var i=0; i<sorted.length; i++) {
                        var name = sorted[i];
                        this.addElement($(groupTemplate({ title: name })));
                        _.each(items[name], function(model) {
                            that.addElement(that.renderItem(model).$el);
                        });
                    }

                    if (items['undefined'] && this.options.groupBy['undefined']) {
                        this.addElement($(groupTemplate({ title: this.options.groupBy['undefined'] })));
                        _.each(items['undefined'], function(model) {
                            that.addElement(that.renderItem(model).$el);
                        });
                    }

                } else {
                    this.collection.each(function(model) {
                        that.addElement(that.renderItem(model).$el);
                    });
                }
            }

            this.$el.removeClass('loading');

            if (this.options.emptyTemplate && this.$el.is(":empty")) {
                this.$el.html(tEmpty());
                this.options.isEmpty = true;
            }
            return this;
        },
        addElement: function($el) {
            if (!$el.is(':empty')) {
                if (this.options.item.prepend) {
                    this.$el.prepend($el);
                } else {
                    this.$el.append($el);
                }
            }
        },
        addItem:function (model) {
            if (this.options.isEmpty) {
                this.$el.html('');
                this.options.isEmpty = false;
            }

            if (this.options.item.prependNew) {
                this.$el.prepend(this.renderItem(model).el);
            } else {
                this.$el.append(this.renderItem(model).el);
            }

            return this;
        },
        changeItem:function (model) {
            if (model.id !== undefined) {
                var item = $('#' + this.options.prefix + model.id),
                    newItem = this.renderItem(model),
                    classes = item.attr('class');

                newItem.$el.addClass(classes);
                item.replaceWith(newItem.el);
            } else { // run addAll if item has no Id
                this.addAll();
            }
            return this;
        },
        removeItem:function(model) {
            if (model.id !== undefined) {
                $('#' + this.options.prefix + model.id).empty().detach();
                if (this.collection.length === 0) {
                    this.collection.reset();
                }
            }
            return this;
        }
    }));

})(window.jQuery, window.Backbone, window._, window.Dime);
