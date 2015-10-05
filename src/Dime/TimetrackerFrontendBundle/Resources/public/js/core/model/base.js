'use strict';

/**
 * Dime - model/activity.js
 *
 * Register Activity model to namespace App.
 */
(function ($, Backbone, _, App) {

    // Create Activity model and add it to App.Model
    App.provide('Model.Base', Backbone.Model.extend({
        relations: {},
        /**
         * Get a value on an attribute
         * enhanced - get relation via dot-separation
         *
         * @param attr 'id' or 'relation.id'
         * @param defaultValue
         * @return {*}
         */
        get: function(attr, defaultValue) {
            if (attr.search(/\./) > -1) {
                var parts = attr.split('.'),
                    next = parts.shift(),
                    relation = this.get('relation');
                if (next && relation && relation[next]) {
                    return relation[next].get(parts.join('.'), defaultValue);
                }
            }
            return Backbone.Model.prototype.get.call(this, attr) || defaultValue;
        },
        getRelation: function(name) {
            var relations = this.get('relation');
            if (this.hasRelation(name)) {
                return relations[name];
            }
            return undefined;
        },
        hasRelation: function(name) {
            var relations = this.get('relation');
            return (name && relations && relations[name]);
        },
        parse: function(response, options) {
            if (this.relations) {
                response.relation = {};

                // cycle through model relations
                for (var name in this.relations) {
                    if (this.relations.hasOwnProperty(name)) {
                        var relatedTo = this.relations[name];
                        // name does exist in response
                        if (response[name]) {
                            // relation has defined model
                            if (relatedTo.model) {
                                // get model function
                                var modelFunc = relatedTo.model;
                                if (_.isString(modelFunc)) {
                                    modelFunc = App.provide(modelFunc.replace('App.', ''));
                                }
                                // relation is a collection
                                if (relatedTo.collection) {
                                    // get collection function
                                    var collectionFunc = relatedTo.collection;
                                    if (_.isString(collectionFunc)) {
                                        collectionFunc = App.provide(collectionFunc.replace('App.', ''));
                                    }

                                    // create new collection
                                    var collection = new collectionFunc(),
                                        ids = [];

                                    _.each(response[name], function (item) {
                                        // create back ref to model
                                        if (relatedTo.belongsTo) {
                                            var split = relatedTo.belongsTo.split(':');
                                            if (split[1]) {
                                                item[split[0]] = response[split[1]];
                                            } else {
                                                item[split[0]] = response['id'];
                                            }
                                        }
                                        // create submodel
                                        var newModel = new modelFunc(item, { parse: true });
                                        // store model ids in list
                                        ids.push(newModel.id);
                                        // save submodel in collection
                                        collection.add(newModel);
                                    }, this);

                                    // store collection in relation
                                    response.relation[name] = collection;
                                    // store submodel ids in response
                                    response[name] = ids;
                                } else {
                                    // important because submodels can be only an id
                                    if (_.isObject(response[name])) {
                                        response.relation[name] = new modelFunc(response[name], { parse: true });
                                        response[name] = response[name].id;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return response;
        },
        toString: function() {
            return this.get('name') ? this.get('name') : this.get('id');
        }
    }));

})(jQuery, Backbone, _, Dime);
