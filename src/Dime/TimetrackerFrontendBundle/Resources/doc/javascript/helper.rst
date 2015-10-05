Helpers
=======

Helper are functions and object which should be reusable to the whole app.

How to define a helper
----------------------

Use the provide function of the namespace to generate a new entry and defined your function or object.

.. code-block:: js
    :linenos:

    Dime.provide('Helper.Format.Duration', function (data, unit) {
        return 0;
    });


How to call it
--------------

.. code-block:: js
    :linenos:

    Dime.Helper.Format.Duration(date, 's');
