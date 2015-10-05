Services
========

GET /api/services
-----------------

Returns a list with services.

+------------+-----------+------------------------------------------+
| Parameters | Example   | Description                              |
+============+===========+==========================================+
| count      | count=30  | Specify the limit of the service list.   |
+------------+-----------+------------------------------------------+
| page       | page=1    | Specify the offset of the service list.  |
+------------+-----------+------------------------------------------+
| filter     | filter[]= | Filter the service list.                 |
+------------+-----------+------------------------------------------+


Filter
^^^^^^

Filter the list of services by following parameters.

+------------+------------------------------+-----------------------------------------+
| Parameters | Definition                   | Description                             |
+============+==============================+=========================================+
| user       | filter[user]=ID              | Filter by user id.                      |
+------------+------------------------------+-----------------------------------------+
| search     | filter[search]=TEXT          | Search for name and alias               |
+------------+------------------------------+-----------------------------------------+


Response
^^^^^^^^

An array with services.

.. code-block:: js
    :linenos:

    [
        {
            "id": 1,
            "user": {},
            "name": "service name",
            "alias": "alias-for-service",
            "description": "Service description",
            "rate": decimal,
            "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
            "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
        }
    ]


GET /api/services/:id
---------------------

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of service                    |
+------------+------------------------------------------+

Response
^^^^^^^^

A single service.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "service name",
        "alias": "alias-for-service",
        "description": "Service description",
        "rate": decimal,
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

POST /api/services
------------------

.. code-block:: js
    :linenos:

    {
        "name": "services name",
        "alias": "alias-for-service",
        "description": "Service description",
        "rate": decimal
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| name       | Name of service                          |
+------------+------------------------------------------+
| alias      | Alias for service                        |
|            | * Unique for user                        |
|            | * Slug format (low-case, A-Z. a-z, 0-9)  |
|            | * Identifier for parser                  |
+------------+------------------------------------------+

Response
^^^^^^^^

The new created service.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "services name",
        "alias": "alias-for-service",
        "description": "Service description",
        "rate": decimal
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PUT /api/services/:id
---------------------

.. code-block:: js
    :linenos:

    {
        "name": "services name",
        "alias": "alias-for-service",
        "description": "Service description",
        "rate": decimal
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of service                    |
+------------+------------------------------------------+

Response
^^^^^^^^

The modified service.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "name": "services name",
        "alias": "alias-for-service",
        "description": "Service description",
        "rate": decimal
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

DELETE /api/services/:id
------------------------

Delete a service by the given ID.

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of service                    |
+------------+------------------------------------------+
