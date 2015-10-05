Menu system
===========

Sourcecode: core/helper/menu.js

Dime.menu is an instance of *Dime.Collection.Menu* collection and contain the app main menu. Each item is a
*Dime.Model.Menu* model and will contain a submenu collection.

Add a new menu item to main menu
--------------------------------

To add a new menu item to the main menu just call *Dime.menu.add* and push either a object or a Dime.Model.Menu object.

.. code-block:: js
    :linenos:

    Dime.menu.add({
        id: 'Unique_name',
        title: 'Title to show',
        weight: 0,
        route: '#Route',
        callback: function() {
            // Do here your stuff
        }
    });

The table show the object attributes for a menu item.

+------------+-----------------------------------------------------------------------+
| Parameters | Description                                                           |
+============+=======================================================================+
| id         | Unique identifier to the menu collection. (**required**)              |
+------------+-----------------------------------------------------------------------+
| title      | Title will shown in on the screen. (**required**)                     |
+------------+-----------------------------------------------------------------------+
| weight     | Define the sort weight of the menu item. (Default: 0)                 |
+------------+-----------------------------------------------------------------------+
| route      | Route will be set automatically in Dime.router.                       |
+------------+-----------------------------------------------------------------------+
| callback   | Callback function used for the Dime.router.                           |
+------------+-----------------------------------------------------------------------+

Add a menu item to a submenu
----------------------------

To add a menu item to a submenu use the *Dime.menu.get* call to retrieve the item. Each menu item has a submenu and it
is an instance of *Dime.Collection.Menu* collection. So you can use the add function. Be aware that the menu item with
submenu items will not be able to use its callback.

.. code-block:: js
    :linenos:

    var adminItem = Dime.menu.get('admin');

    adminItem.submenu.add({
        id: 'Unique_name',
        title: 'Title to show',
        weight: 0,
        route: '#Route',
        callback: function() {
            // Do here your stuff
        }
    });

Activate / deactivate menu item
-------------------------------

To activate a menu item use the *Dime.Collection.Menu* collection build in function "activateItem". To find the item it
uses the menu item id. This is the reason why the id of each menu item in a collection must be unique.

.. code-block:: js
    :linenos:

    Dime.menu.activateItem('activity');

If there is a submenu item you want to activate you can either get each single menu item via *get()* and further
with its submenu collection or use the "dot"-notation like shown below.

.. code-block:: js
    :linenos:

    Dime.menu.activateItem('admin.customer');

To deactivate the active item use the *deactivateItem()* function. There is no parameter, because the collection store
the active item.

.. code-block:: js
    :linenos:

    Dime.menu.deactivateItem();

Create a new menu
-----------------

Beside of *Dime.menu* you can create you own menu. Just create a new *Dime.Collection.Menu* collection and add you items.
After than create a new instance of *Dime.Views.Core.Menu* view. This view create an ul-element and each menu item will
be a *Dime.Views.Core.MenuItem*.


.. code-block:: js
    :linenos:

    // Create new menu collection
    var sidemenu = new Dime.Collection.Menu();

    // Create you menu items
    sidemenu.add({
        id: 'Unique_to_this_collection',
        title: 'Show me',
        route: '#where-every-you-want',
        callback: function() {
            // PUT YOU STUFF HERE
        }
    });

    // Create menu view with class 'nav nav-list'
    var sidemenuView = new Dime.Views.Core.Menu({
        collection: sidemenu,
        attributes: {
            'class': 'nav nav-list'
        },
        itemView:App.Views.Core.MenuItem    // itemView is not required, this is the default
    });

    // replace the #element html with the rendered view
    $('#element').html(view.render().el);
