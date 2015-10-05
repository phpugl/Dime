'use strict';

/**
 * Dime - app/activity/routes.js
 */
(function ($, App) {

    App.menu.add({
        id:"activity",
        title:"Activities",
        route:"activity",
        weight:-20,
        callback:function () {
            App.menu.activateItem('activity');
            App.router.switchView(new App.Views.Activity.Index());
        }
    });
    App.session.set('default-backlink', 'activity');

    // Define Routes
    App.router.route("activity/add", "activity:add", function () {
        var model = new App.Model.Activity();

        App.menu.activateItem('activity');
        App.router.switchView(new App.Views.Activity.Form({
            model:model,
            title:'Add Activity'
        }));
    });

    App.router.route("activity/:id/edit", "activity:edit", function (id) {
        var model = new App.Model.Activity({id:id});
        model.fetch({async:false});

        App.menu.activateItem('activity');
        App.router.switchView(new App.Views.Activity.Form({
            model:model,
            title:'Edit Activity'
        }));
    });

})(jQuery, Dime);
