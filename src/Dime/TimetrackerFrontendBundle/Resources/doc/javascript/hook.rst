Hook system
===========

The dime application has a hook system. It is a *Dime.Collection.Hooks* collection provided via *Dime.hook*. These hooks
are used at the moment to initalize the application.

Add a hook
----------

To add a hook use the *Dime.hook.add* function. If you push an object to the function it will create a *Dime.Model.Hook*
for you. The example below show a new router hook with scope initialize. This hook will be called when Dime.run()
is executed.

.. code-block:: js
    :linenos:

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

The table show the object attributes for a menu item.

+------------+-----------------------------------------------------------------------+
| Parameters | Description                                                           |
+============+=======================================================================+
| id         | Unique identifier to the hook collection. (**required**)              |
+------------+-----------------------------------------------------------------------+
| scope      | Define the hook scope (Default: 'default')                            |
+------------+-----------------------------------------------------------------------+
| weight     | Define the sort weight of the hook item. (Default: 0)                 |
+------------+-----------------------------------------------------------------------+
| callback   | Callback function.                                                    |
+------------+-----------------------------------------------------------------------+
