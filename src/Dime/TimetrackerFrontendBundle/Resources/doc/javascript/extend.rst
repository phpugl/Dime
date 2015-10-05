Extend the Frontend
===================

The best way to extend the frontend is to create new bundle (e.g. DimeReportBundle). Add the bundle to you Dime
installation. Basically you need the following structure.

Example stucture
----------------

.. code-block:: js

    Dime/
        ExampleBundle/
            DependencyInjection/
            Resources/
                config/
                    config.yml          ... add your js files to the asset configuration
                public/
                    js/                 ... put here your js code
                views/
             DimeExampleBundle.php
             composer.json              ... requirement for this extensions
             README.mkd

Example composer.json
---------------------

.. code-block:: js

    {
        "name": "phpugl/dime-report-bundle",
        "type": "symfony-bundle",
        "description": "Report bundle for dime timetracker",
        "homepage": "https://github.com/phpugl/DimeTimetrackerFrontendBundle",
        "license": "MIT",
        "authors": [
            {
                "name": "PHPUGL",
                "email": "info@phpugl.de"
            }
        ],
        "autoload": {
            "psr-0": { "Dime\\ReportBundle": "" }
        },
        "require": {
            "php": ">=5.3.3",
            "phpugl/dime-timetracker-bundle": "*",
            "phpugl/dime-timetracker-frontend-bundle": "*"
        },
        "minimum-stability": "dev",
        "target-dir": "Dime/ReportBundle"
    }

Symfony app configuration
-------------------------

app/AppKernel

.. code-block:: php

    $bundles = array(
        ...
        new Dime\TimetrackerFrontendBundle\DimeTimetrackerFrontendBundle(),
        new Dime\ExampleBundle\DimeReportBundle(),
        ...
    };

app/config/config.yml

.. code-block:: yaml

    imports:
        - ...
        - { resource: "@DimeTimetrackerFrontendBundle/Resources/config/config.yml" }
        - { resource: "@DimeExampleBundle/Resources/config/config.yml" }


Add extension start point
-------------------------

Create a new javascript file within the public/js folder and add the basic file structure. Now you can start to create a
menu and your first route.

.. code-block:: js

    'use strict';

    /**
    * Dime - app/report/index.js
    */
    (function ($, Backbone, _, App) {

        // add menu item
         App.menu.add({
             id:"example",            // unique name
             title:"Example",           // menu title
             route:"example",           // route
             weight: 0,                // weight to order the menu
             callback:function () {    // callback to switch the view
                 // activate menu item
                 App.menu.activateItem('example');

                 // switch to you defined index view
                 App.router.switchView(new App.Views.Example.Index());
             }
         });

         // create index view and render the remote template
         App.provide('Views.Example.Index', App.Views.Core.Content.extend({
             template:'DimeExampleBundle:Example:index',
             initialize:function () {
                 // Bind all to this, because you want to use
                 // "this" view in callback functions
                 _.bindAll(this);
             }
         }));

    })(jQuery, Backbone, _, Dime);

