'use strict';

/**
 * Dime - core/helper/session.js
 *
 */
(function ($, App, undefined) {

    /**
     * App.session
     */
    App.provide('session', function() {
        var store = {};

        return {
            /**
             * Set a key-value-pair to session
             * @param key, string
             * @param value, anything
             * @return {*}
             */
            set: function(key, value) {
                if (key === undefined) throw 'No key given to add(key, value) function';

                store[key] = value;

                return value;
            },
            /**
             * Get the value with the given key from the session
             * @param key, string
             * @param callback, function to auto create a value if key not exists
             * @return {*}, value or the whole session
             */
            get: function(key, callback) {
                if (key) {
                    if (store[key]) {
                        return store[key];
                    }

                    if (callback && typeof callback == 'function') {
                        return this.set(key, callback());
                    }
                    return undefined;
                } else {
                    return store;
                }
            },
            /**
             * Check if the key exists
             * @param key
             * @return {Boolean}
             */
            has: function(key) {
                return (key && store[key] !== undefined);
            },
            /**
             * remove the key when key given or the whole session will be cleaned
             * @param key, string, optional
             */
            remove: function(key) {
                if (key && this.has(key)) {
                    delete store[key];
                } else {
                    store = {};
                }
            }
        };
    }());

})(jQuery, Dime, undefined);

