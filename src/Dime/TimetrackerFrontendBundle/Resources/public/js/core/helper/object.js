'use strict';

/**
 * Dime - core/helper/object.js
 */
(function (App, $, _) {

    /**
     * Dime.Helper.Object.Set
     *
     * @param obj
     * @param path dot-separated string or array
     * @param value
     * @return obj
     */
    App.provide('Helper.Object.Set', function(obj, path, value) {
        if (_.isString(path)) {
            path = path.split('.');
        }
        var parent = obj,
            len = path.length;

        for (var i=0; i<len; i++) {
            var name = path[i];
            if (i >= len - 1) {
                if (name.search(/\[\]/) !== -1) {
                    name = name.replace('[]', '');
                    if (parent[name] === undefined) {
                        parent[name] = [];
                    }
                    parent[name].push(value);
                } else {
                    parent[name] = value;
                }
            } else if (parent[name] === undefined) {
                parent[name] = {};
            }
            parent = parent[name];
        }

        return obj;
    });

    /**
     * Dime.Helper.Object.Get
     *
     * @param obj
     * @param path dot-separated string or array
     * @return value of path or undefined
     */
    App.provide('Helper.Object.Get', function(obj, path) {
        if (obj && path) {
            if (_.isString(path)) {
                path = path.split('.');
            }

            var parent = obj;
            for (var i=0; i<path.length; i++) {
                var name = path[i].replace('[]', '');
                if (parent[name]) {
                    parent = parent[name];
                } else {
                    parent = undefined;
                    break;
                }
            }

            return parent;
        }
        return undefined;
    });

})(Dime, jQuery, _);
