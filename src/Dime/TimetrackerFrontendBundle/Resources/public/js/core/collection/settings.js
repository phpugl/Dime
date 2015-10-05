'use strict';

/**
 * Dime - collection/settings.js
 *
 * Register Settings collection to namespace App
 */
(function ($, App) {

    // Create Settings collection and add it to App.Collection
    App.provide('Collection.Settings', App.Collection.Base.extend({
        model:App.Model.Setting,
        url:App.Route.Settings,
        removeNamespace: function(namespace) {
            if (namespace) {
                var models = this.where({ namespace: namespace });

                if (models.length > 0) {
                    models[0].destroy();
                }
            }
            return this;
        },
        getSetting: function(namespace, name) {
            if (namespace && name) {
                var models = this.where({ namespace: namespace, name: name });
                if (models) {
                    if (models.length > 0) {
                        return models[0].get('value');
                    }
                }
            }
            return undefined;
        },
        hasSetting: function(namespace, name) {
            if (namespace && name) {
                var models = this.where({ namespace: namespace, name: name });
                return models && models.length > 0;
            }
            return false;
        },
        updateSetting: function(namespace, name, value) {
            var models = this.where({ namespace: namespace, name: name });

            if (value) {
                if (models.length > 0) {
                    models[0].save({ namespace: namespace, name: name, value: JSON.stringify(value) });
                } else {
                    this.create({ namespace: namespace, name: name, value: JSON.stringify(value) });
                }
            } else if (models.length > 0) {
                models[0].destroy();
            }

            return this;
        },
        values: function(namespace) {
            var result = {}, models;

            models = this.models;
            if (namespace) {
                models = this.where({ "namespace": namespace });
            }

            if (models) {
                for (var i=0; i<models.length; i++) {
                    result[models[i].get('name')] = models[i].get('value');
                }
            }

            return result;
        }
    }));

})(jQuery, Dime);

