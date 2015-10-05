'use strict';

/**
 * Dime - core/model/activity.js
 *
 * Register Activity model to namespace App.
 */
(function ($, Backbone, App) {

    // Create Activity model and add it to App.Model
    App.provide('Model.Activity', App.Model.Base.extend({
        urlRoot:App.Route.Activities,
        defaults:{
            duration:0
        },
        relations: {
            customer: {
                model: 'App.Model.Customer'
            },
            project: {
                model: 'App.Model.Project'
            },
            service: {
                model: 'App.Model.Service'
            },
            timeslices: {
                collection: 'App.Collection.Timeslices',
                model: 'App.Model.Timeslice',
                belongsTo: 'activity:id'
            },
            tags: {
                collection: 'App.Collection.Tags',
                model: 'App.Model.Tag',
                belongsTo: 'activity:id'
            }
        },
        start:function (opt) {
            var timeslices = this.getRelation('timeslices');
            if (timeslices && timeslices.running() === undefined) {
                timeslices.create(new App.Model.Timeslice({
                    activity:this.id,
                    startedAt: App.Helper.Format.Date()
                }, { parse: true }), opt);
            }
        },
        stop:function (opt) {
            var timeslices = this.getRelation('timeslices');
            if (timeslices) {
                var timeslice = timeslices.running(true);
                if (timeslice !== undefined) {
                    timeslice.save(
                        {
                            'stoppedAt': App.Helper.Format.Date()
                        },
                        opt
                    );
                }
            }
        },
        /**
         * Get the running timeslices
         * @param first
         * @return {undefined|Array|Model}
         */
        running:function (first) {
            return (this.hasRelation('timeslices')) ? this.getRelation('timeslices').running(first) : undefined;
        },
        /**
         * Calculate the total timeslice duration
         * @return {Number}
         */
        duration: function() {
            if (this.hasRelation('timeslices')) {
                return this.getRelation('timeslices').duration();
            }
            return 0;
        },
        addTimeslice: function (timeslice) {
            if (timeslice && this.hasRelation('timeslices')) {
                this.getRelation('timeslices').add(timeslice);
            }
        }
    }));

})(jQuery, Backbone, Dime);

