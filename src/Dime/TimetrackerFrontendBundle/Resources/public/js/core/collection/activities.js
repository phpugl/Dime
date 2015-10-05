'use strict';

/**
 * Dime - collection/activities.js
 *
 * Register Activities collection to namespace App
 */
(function ($, App) {

    // Create Activities collection and add it to App.Collection
    App.provide('Collection.Activities', App.Collection.Base.extend({
        model:App.Model.Activity,
        url:App.Route.Activities,
        comparator:function (first, second) {
            if (first && second) {
                var opt1 = first.get('updatedAt'), opt2 = second.get('updatedAt');
                if (opt1 != undefined && opt2 != undefined) {
                    if (opt1 > opt2) {
                        return -1;
                    } else if (opt1 < opt2) {
                        return 1;
                    } else {
                        opt1 = first.get('id');
                        opt2 = second.get('id');
                        if (opt1 > opt2) {
                            return -1;
                        } else if (opt1 < opt2) {
                            return 1;
                        }
                    }
                }
            }
            return 0;
        }
    }));

})(window.jQuery, window.Dime);

