'use strict';

/*
 * Dime - app/main.js
 */
(function ($, App) {

    // Define home route
    App.router.route("", "home", function () {
        App.menu.activateItem('activity');
        App.router.switchView(new App.Views.Activity.Index());
    });

    // Define management menu
    App.menu.add({
        id:'admin',
        title:'Administration',
        weight:10
    });

    // Define help menu
    App.provide('Views.Help', App.Views.Core.Content.extend({
        template:'DimeTimetrackerFrontendBundle:App:help'
    }));
    App.menu.add({
        id:"help",
        title:"Help",
        route:"help",
        weight:1000,
        callback:function () {
            App.menu.activateItem('help');
            App.router.switchView(new App.Views.Help());
        }
    });

    // Initialize main menu - bind on #nav-main
    App.hook.add({
        id:'navigation',
        scope: 'initialize',
        callback:function () {
            var view = new App.Views.Core.Menu({
                collection:App.menu,
                attributes:{
                    'class':'nav'
                }
            });
            $('#nav-main').prepend(view.render().el);
        }
    });

    // Initialize router
    App.hook.add({
        id: 'router',
        scope: 'initialize',
        weight: 9999,
        callback: function() {
            App.router.setElement('#area-content');
            Backbone.history.start();
        }
    });

    // Load settings
    App.hook.add({
        id: 'settings',
        scope: 'initialize',
        callback: function() {
            var settings = new App.Collection.Settings();
            settings.fetch({ async: false });
            App.session.set('settings', settings);
        }
    });

})(jQuery, Dime);

