'use strict';

/**
 * Dime - collection/base.js
 *
 * Register Customers collection to namespace App
 */
(function ($, Backbone, _, App) {

    // Create Base collection and add it to App.Collection
    App.provide('Collection.Base', Backbone.Collection.extend({
        load: function() {
            var opt = this.joinFetchDataCache();
            if (opt) {
                this.fetch(opt);
            } else {
                this.fetch();
            }
        },
        joinFetchDataCache: function() {
            var fetchOpt = this.fetchOptions || { data:{} };
            if (this.fetchDataCache) {
                fetchOpt.data = {};
                for(var name in this.fetchDataCache) {
                    if (this.fetchDataCache.hasOwnProperty(name)) {
                        fetchOpt.data = _.extend(fetchOpt.data, this.fetchDataCache[name]);
                    }
                }
            }

            return fetchOpt;
        },
        addFetchData: function(name, data) {
            if (!this.fetchDataCache) {
                this.fetchDataCache = {};
            }
            if (name) {
                this.fetchDataCache[name] = data;
            }
            return this;
        },
        removeFetchData: function(name) {
            if (name && this.fetchDataCache && this.fetchDataCache[name]) {
                delete this.fetchDataCache[name];
            }
        },
        setFetchOption: function(name, opt) {
            if (!this.fetchOptions) {
                this.fetchOptions = {};
            }
            if (name) {
                this.fetchOptions[name] = opt;
            }
        },
        removeFetchOption: function(name) {
            if (name && this.fetchOptions && this.fetchOptions[name]) {
                delete this.fetchOptions[name];
            }
        }
    }));

})(jQuery, Backbone, _, Dime);

