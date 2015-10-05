'use strict';

/**
 * Dime - core/views/widgets/select.js
 */
(function ($, Backbone, _, App) {

    App.provide('Views.Core.Widget.SelectOption', Backbone.View.extend({
        tagName: "option",
        options:{
            name:'name',
            value:'id',
            blank:''
        },
        render:function () {
            if (this.model) {
                this.$el
                    .attr('value', this.model.get(this.options.value))
                    .text(this.model.get(this.options.name));
            } else {
                this.$el
                    .attr('value', '')
                    .text(this.options.blank);
            }
            return this;
        }
    }));

    /**
     * Views.Core.Component.Select
     *
     * var select = new App.Views.Core.Component.Select({
     *   el: '#element',
     *
     * });
     */
    App.provide('Views.Core.Widget.Select', Backbone.View.extend({
        options: {
            withBlank:true,
            blankText:'',
            selected:undefined,
            bind:undefined,
            optionView: App.Views.Core.Widget.SelectOption,
            optionSetting: {}
        },
        items: [],
        initialize:function (config) {
            if (this.collection) {
                this.collection.on('add', this.addOne, this);
                this.collection.on('remove', this.addAll, this);
                this.collection.on('reset', this.addAll, this);
                this.collection.on('change', this.addAll, this);
            }
        },
        render: function(parent) {
            if (this.options.templateEl) {
                this.setElement(this.options.templateEl);
            }

            if (this.collection && this.collection.length > 0) {
                this.addAll();
            }

            return this;
        },
        addOne:function (model) {
            var view;

            if (model) {
                view = new this.options.optionView({
                    model:model,
                    options: this.options.optionSetting
                });
            } else {
                view = new this.options.optionView({ options:{ blank:this.options.blankText }});
            }

            this.items.push(view);
            this.$el.append(view.render().el);
        },
        addAll:function () {
            // clear select
            for (var i=0; i<this.items.length; i++) {
                this.items[i].remove();
            }
            this.items = [];
            this.$el.html('');

            if (this.options.withBlank) {
                this.addOne();
            }

            if (this.collection) {
                this.collection.each(this.addOne, this);
            }

            // select option if selectedId exists
            if (this.options.selected) {
                this.$el.val(this.options.selected);
            }
        },
        bind: function (data) {
            if (data) {
                var name = this.name().split('-'),
                    val = undefined;

                if (name) {
                    val = App.Helper.Object.Get(data, name);
                }

                this.options.selected = val;
                this.$el.val(this.options.selected);
            }
            return this;
        },
        fetch:function (opt) {
            if (this.collection) {
                this.collection.fetch(opt);
            }
            return this;
        },
        name: function() {
            return this.options.bind || this.el.name || this.el.id;
        },
        serialize: function(data, withoutEmpty) {
            var name = this.name().split('-'),
                value = this.$el.val();

            if (withoutEmpty) {
                if (value == undefined || _.isEmpty(value)) {
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

            return this.$el.val();
        }
    }));

})(jQuery, Backbone, _, Dime);
