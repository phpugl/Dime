Namespace
=========

.. code-block:: js
    :linenos:

    window.Dime = {}

The namespace "window.Dime" is the container that contain all the objects.

Extend namespace with provide()
-------------------------------

.. code-block:: js
    :linenos:

    Dime.provide(name, obj, force);


+------------+----------------------------------------------------------------+
| Parameters | Description                                                    |
+============+================================================================+
| name       | Name of object in namespace (dot-sparated) you want to extend. |
|            | *required*                                                     |
+------------+----------------------------------------------------------------+
| obj        | Object or function which contain the new namespace             |
+------------+----------------------------------------------------------------+
| force      | override the current object in this namespace (default: false) |
+------------+----------------------------------------------------------------+

Add new structure to namespace
------------------------------

Create a "session" entry in Dime namespace.

.. code-block:: js
    :linenos:

    Dime.provide('session', function(param) {
        // do somthing
    });

Create a "Views.Core.Content" entry in Dime namespace. All "dots" will be a new object and created automatically.

.. code-block:: js
    :linenos:

    Dime.provide('Views.Core.Content', Backbone.View.extend({
      // add properties to extend the view
    }));

Call a provided namespace object

.. code-block:: js
    :linenos:

    Dime.session(param);

.. code-block:: js
    :linenos:

    var view = new Dime.Views.Core.Content();

Namespace predefined objects
----------------------------

The predefined objects are container for backbone related objects,

.. code-block:: js

    Dime.Collection   ... Container object for all backbone collections
    Dime.Helper       ... Container object for all helper
    Dime.Model        ... Container object for all backbone models
    Dime.Views        ... Container object for all backbone views
    Dime.Route        ... Container object for route to api

    Dime.hook         ... Collection instance for application hook system
    Dime.menu         ... Collection instance for application main menu
    Dime.router       ,,, Collection instance for application router

Namespace predefined functions
------------------------------

.. code-block:: js

    Dime.log(msg, level)                ... Basic console.log wrapper
    Dime.provide(name, object, force)   ... Create namespace onject
    Dime.run()                          ... Initialize the whole app
    Dime.render(name, data)             ... Fetch remote templates and store them


