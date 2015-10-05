'use strict';

/**
 * Dime - core/collection/services.js
 *
 * Register Timeslices collection to namespace App
 */
(function ($, App) {

    /**
     * Timeslice collection
     */
    App.provide('Collection.Timeslices', App.Collection.Base.extend({
        model:App.Model.Timeslice,
        url:App.Route.Timeslices,
        /**
         * Get the total duration of this collection
         * @return {Number}
         */
        duration:function () {
            var duration = 0;
            this.each(function (model) {
                if (model.get('duration')) {
                    duration += model.get('duration');
                }
            });
            return duration;
        },
        /**
         * Get an array of running timeslices
         *
         * @param first return only the first in array
         * @return {*} undefined, array of models, model
         */
        running:function (first) {
            var models = this.filter(function(model) {
                return (model) ? model.isRunning() : false;
            });

            if (models && models.length > 0) {
                if (first) {
                    return models[0];
                }
                return models;
            }
            return undefined;
        }
    }));

})(jQuery, Dime);

