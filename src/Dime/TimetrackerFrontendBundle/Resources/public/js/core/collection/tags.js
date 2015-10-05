'use strict';

/**
 * Dime - collection/tags.js
 *
 * Register Tags collection to namespace App
 */
(function ($, App) {

    // Create Tags collection and add it to App.Collection
    App.provide('Collection.Tags', App.Collection.Base.extend({
        model:App.Model.Tag,
        url:App.Route.Tags,
        tagArray: function(ignore, withSystem) {
            var result = [], models;
            if (ignore !== undefined && ignore.length > 0) {
                models = this.filter(function(item) {
                    return _.indexOf(ignore, item.get('name')) === -1;
                });
            } else {
                models = this.toArray()
            }

            for (var i=0; i<models.length; i++) {
                if (withSystem) {
                    result.push(models[i].get('name'));
                } else {
                    if (!models[i].get('system')) {
                        result.push(models[i].get('name'));
                    }
                }
            }

            return result;
        }
    }));

})(jQuery, Dime);

