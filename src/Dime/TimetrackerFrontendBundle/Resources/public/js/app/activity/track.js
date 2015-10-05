'use strict';

/*
 * Dime - app/activity/track.js
 */
(function ($, moment, App) {

    // Initialize main view - bind on <body>
    App.hook.add({
        id:'activity:tracker',
        scope: 'initialize',
        callback:function () {
            var tracker = new App.Views.Activity.Track();
            tracker.render();
        }
    });

    // Activity track input view
    App.provide('Views.Activity.Track', Backbone.View.extend({
        el:'#activity-track',
        events:{
            'changeDate #activity-track-date':'updateTitle',
            'blur #activity-track-input': 'blurInput',
            'focus #activity-track-input': 'focusInput',
            'submit':'save'
        },
        initialize:function (config) {
            this.documentWidth = $(document).width();
            this.input = this.$('#activity-track-input');
            this.date = this.$('#activity-track-date');
            this.icon = this.$('i');
        },
        updateTitle:function (e) {
            var icon = $('#activity-track-date');
            icon.attr('title', icon.data('date'));

            return this;
        },
        blurInput:function(e) {
            if (e) {
                var component = $(e.currentTarget);
                if (this.inputWidth) {
                    component.width(this.inputWidth);
                }
            }
        },
        focusInput:function(e) {
            if (e) {
                var component = $(e.currentTarget);
                this.inputWidth = component.width();
                if (this.documentWidth > this.inputWidth * 2) {
                    component.width(this.inputWidth * 2);
                }
            }
        },
        save:function (e) {
            if (e) {
                e.preventDefault();
            }

            var that = this,
                data = this.input.val(),
                date = this.date.data('date') || moment().format('YYYY-MM-DD');

            if (data && data !== "") {
                var activity = new App.Model.Activity();
                this.icon.addClass('loading-14-white');

                activity.save({parse:data, date:date}, {
                    wait: true,
                    success:function (model, response) {
                        that.input.val('');
                        that.icon.removeClass('loading-14-white');

                        var activities = App.session.get('activities');
                        if (activities) {
                            activities.add(model);
                        }
                    },
                    error: function(model, response, scope) {
                        that.icon.removeClass('loading-14-white');
                        App.notify('Something goes wrong here. [' + response.status + ': ' + response.statusText  +']', 'error');
                    }
                });
            }
        }
    }));

})(jQuery, moment, Dime);
