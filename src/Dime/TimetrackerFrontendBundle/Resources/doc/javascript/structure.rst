Structure and Convention about Javascript modules
=================================================

File Structure
--------------

Location: src/Dime/TimetrackerFrontendBundle/Resources/public/js

::

    model/          ... Location for backbone models
    collection/     ... Location for backbone collections
    plugins/        ... Location for jQuery plugins
    views/          ... Location for backbone views
    vendor/         ... Location for vendor libraries
    application.js  ... Initialize Dime namespace

Initialization order
--------------------

#. vendors (json2, jquery, underscore, backbone, ...)
#. plugins
#. backbone models
#. backbone collections
#. backbone views

Namespace "dime"
----------------

Location: src/Dime/TimetrackerFrontendBundle/Resources/public/js/application.js

::

    window.dime = {
      model: {},
      collection: {},
      views: {}
    }

Naming convention
~~~~~~~~~~~~~~~~~

- Collection plural ('activities', 'customers', ...)
- Model singular ('activity', 'customer', ...)
- Views singular ('activity.list', 'activity.item', ...)

Extending and override the namespace
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you want to extend the dime namespace just use the javascript object. You
are free to extend or override objects.

::

    window.dime.model['activity'] = Backbone.Model.extend({});

