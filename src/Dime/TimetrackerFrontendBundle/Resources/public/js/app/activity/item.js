'use strict';

/**
 * Dime - app/activity/item.js
 */
(function ($, _, moment, App) {

    // activity item view
    App.provide('Views.Activity.Item', App.Views.Core.ListItem.extend({
        events:{
            'click .edit':'edit',
            'click .delete':'delete',
            'click .track':'track',
            'click .box-foldable':'showDetails',
            'click .show-timeslices':'showDetails',
            'click .timeslice-checkall': 'checkAll',
            'click .timeslice-save-tags': 'saveTags'
        },
        render:function () {
            // Call parent contructor
            App.Views.Core.ListItem.prototype.render.call(this);

            // activate timer if any running timeslice is found
            var timeslice = this.model.running(true);
            if (timeslice) {
                var button = $('.duration', this.$el),
                    model = this.model;

                button.data('start', App.Helper.Format.Date(timeslice.get('startedAt')));
                this.timer = setInterval(function () {
                    var d = moment().diff(button.data('start'), 'seconds');
                    button.text(App.Helper.Format.Duration(button.data('duration') + d));
                }, 1000);
            }

            // activate contenteditable
            var ce = new App.Views.Core.Editor({
                el:this.el,
                model:this.model
            }).render();

            // show timeslice table
            this.timeslices = new App.Views.Core.List({
                templateEl:$('.box-details table tbody', this.$el),
                model:this.model,
                collection:this.model.getRelation('timeslices'),
                prefix:'timeslice-',
                emptyTemplate:'#tpl-timeslice-empty',
                item:{
                    attributes:{ "class":"timeslice" },
                    prepend:true,
                    prependNew:true,
                    tagName:"tr",
                    View:App.Views.Timeslice.Item
                }
            }).render();

            return this;
        },
        showDetails:function (e) {
            e.preventDefault();
            e.stopPropagation();

            this.$el.toggleClass('box-folded box-unfolded');
        },
        details:function (e) {
            e.stopPropagation();
        },
        edit:function (e) {
            e.stopPropagation();
        },
        'delete':function (e) {
            e.preventDefault();
            e.stopPropagation();

            // confirm destroy action
            if (window.confirm("Are you sure?")) {
                this.model.destroy({wait:true});
            }
        },
        track:function (e) {
            e.preventDefault();
            e.stopPropagation();

            var button = $('.duration', '#' + this.elId()),
                model = this.model,
                that = this,
                activities = App.session.get('activities');

            if (!button.hasClass('btn-warning')) {
                button.data('start', moment());
                this.model.start({
                    wait:true,
                    success:function (timeslice) {
                        button.addClass('btn-warning');
                        that.timer = setInterval(function () {
                            var d = moment().diff(button.data('start'), 'seconds');
                            button.text(App.Helper.Format.Duration(button.data('duration') + d));
                        }, 1000);
                        model.save({}, {success:function () {
                            model.collection.sort();
                        }});
                    }
                });
            } else {
                if (that.timer) {
                    clearInterval(that.timer);
                }

                var width = button.width();
                button.removeClass('btn-warning');
                button.html('<i class="icon loading-14"></i>');
                button.width(width);
                this.model.stop({
                    wait:true,
                    success:function (timeslice) {
                        var d = moment().diff(button.data('start'), 'seconds');
                        button.text(App.Helper.Format.Duration(button.data('duration') + d));

                        model.save({}, {success:function () {
                            model.collection.sort();
                        }});
                    }
                });
            }
        },
        checkAll: function(e) {
            if (e) {
                e.stopPropagation();
            }

            this.$('.timeslice-checkbox').prop('checked', e.currentTarget.checked);
        },
        saveTags: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var data = App.Helper.UI.Form.Serialize(this.$('.box-details'), true),
                timeslices = this.model.getRelation('timeslices');

            if (timeslices && data && data.tags && data.timeslice) {
                var tags = App.Helper.Tags.Split(data.tags);

                // Save new tags
                for (var i=0; i<data.timeslice.length; i++) {
                    var ts = timeslices.get(data.timeslice[i]);
                    if (ts) {
                        App.Helper.Tags.Update(ts, tags);
                    }
                }

                this.$('.timeslice-checkall').prop('checked', false);
                this.$('.timeslice-tags').val('');
            }
        }
    }));

})(jQuery, _, moment, Dime);

