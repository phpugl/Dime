Customers
=========

GET /api/customers
------------------

Returns a list with customers.

+------------+-----------+------------------------------------------+
| Parameters | Example   | Description                              |
+============+===========+==========================================+
| count      | count=30  | Specify the limit of the customer list.  |
+------------+-----------+------------------------------------------+
| page       | page=1    | Specify the offset of the customer list. |
+------------+-----------+------------------------------------------+
| filter     | filter[]= | Filter the customer list.                |
+------------+-----------+------------------------------------------+


Filter
^^^^^^

Filter the list of customers by following parameters.

+------------+------------------------------+-----------------------------------------+
| Parameters | Definition                   | Description                             |
+============+==============================+=========================================+
| user       | filter[user]=ID              | Filter by user id.                      |
+------------+------------------------------+-----------------------------------------+
| search     | filter[search]=TEXT          | Search for name and alias               |
+------------+------------------------------+-----------------------------------------+

Response
^^^^^^^^

An array with customers.

.. code-block:: js
    :linenos:

    [
        {
            "id": 1,
            "user": {},
            "name": "Customers name",
            "alias": "alias-for-customer",
            "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
            "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
        }
    ]


GET /api/customers/:id
----------------------

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of customer                   |
+------------+------------------------------------------+

Response
^^^^^^^^

A single customer.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "Customers name",
        "alias": "alias-for-customer",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

POST /api/customers
-------------------

.. code-block:: js
    :linenos:

    {
        "name": "Customers name",
        "alias": "alias-for-customer",
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| name       | Name of customer                         |
+------------+------------------------------------------+
| alias      | Alias for customer                       |
|            | * Unique for user                        |
|            | * Slug format (low-case, A-Z. a-z, 0-9)  |
|            | * Identifier for parser                  |
+------------+------------------------------------------+

Response
^^^^^^^^

The new created customer.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "Customers name",
        "alias": "alias-for-customer",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PUT /api/customers/:id
----------------------

.. code-block:: js
    :linenos:

    {
        "user": ID,
        "name": "Customers name",
        "alias": "alias-for-customer",
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of customer                   |
+------------+------------------------------------------+

Response
^^^^^^^^

The modified customer.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "Customers name",
        "alias": "alias-for-customer",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

DELETE /api/customers/:id
-------------------------

Delete a customer by the given ID.

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of customer                   |
+------------+------------------------------------------+
