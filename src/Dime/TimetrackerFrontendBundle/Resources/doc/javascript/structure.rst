Structure and Convention about Javascript modules
=================================================

Folder structure
----------------

Location: ``Resources/public/js``

.. code-block:: js
    :linenos:

    app/
        module/       ... Module sub folder
        ...
        main.js       ... Main stuff which not belong to a module
    core/           ... Core file which are needed in ever module
        collection/   .., Location for core backbone collections
        helper/       .., Location for core helper
        model/        .., Location for core backbone model
        views/        .., Location for core views
    plugins/        ... Location for plugins (e.g. jQuery)
    vendors/        ... Location for vendor libraries
    application.js  ... Initialize Dime namespace

Initialization order
--------------------

The order of initalization will be define in the ``Resources/config/config.yml``. There you find all the assets.

#. vendors (json2, jquery, underscore, backbone, ...)
#. plugins
#. app

Basic javascript file
---------------------

You should use every time you create a new javascript file this following template.

.. code-block:: js
    :linenos:

    'use strict';

    /**
    * Dime - path/to/file.js
    */
    (function ($, Backbone, _, App) {

    // PUT HERE YOUR CODE

    })(jQuery, Backbone, _, Dime);

The template will set 'use strict' and wrap you code in a closure. So the you can not override something by accident.

Naming convention
-----------------

- Classes starts with a upper case letter.

    - App.Model.Activitiy
    - App.Views.Content

- Instances start with a lower case letter.

    - var activity = new App.Model.Activitiy();
    - var view = new App.View.Content();


- Collection plural ('activities', 'customers', ...)
- Model singular ('activity', 'customer', ...)
- Views singular ('activity.list', 'activity.item', ...)
