Settings
========

GET /api/settings
-----------------

Returns a list with settings.

+------------+-----------+------------------------------------------+
| Parameters | Example   | Description                              |
+============+===========+==========================================+
| count      | count=30  | Specify the limit of the setting list.   |
+------------+-----------+------------------------------------------+
| page       | page=1    |Specify the offset of the setting list.   |
+------------+-----------+------------------------------------------+
| filter     | filter[]= | Filter the setting list                  |
+------------+------------------------------------------------------+

Filter
^^^^^^

Filter the list of settings by following parameters.

+------------+------------------------------+-----------------------------------------+
| Parameters | Definition                   | Description                             |
+============+==============================+=========================================+
| user       | filter[user]=1               | Filter by user id.                      |
+------------+------------------------------+-----------------------------------------+
| namespace  | filter[namespace]=NAMESPACE  | Filter by namespace                     |
+------------+------------------------------+-----------------------------------------+

Response
^^^^^^^^

An array with settings.

.. code-block:: js
    :linenos:

    [
        {
            "id": 1,
            "key": "Setting key",
            "namespace": "Settings namespace",
            "value": "Setting value",
            "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
            "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
        }
    ]


GET /api/settings/:id
---------------------

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of setting                    |
+------------+------------------------------------------+

Response
^^^^^^^^

A single setting.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "key": "Setting key",
        "namespace": "Settings namespace",
        "value": "Setting value",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

POST /api/settings
------------------

.. code-block:: js
    :linenos:

    {
        "key": "Setting key",
        "namespace": "Setting namespace",
        "value": "Setting value"
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| key        | Key of setting                           |
|            | * Unique for user and namespace          |
+------------+------------------------------------------+
| namespace  | Namespace for setting                    |
|            | * Unique for user                        |
+------------+------------------------------------------+
| value      | Value of setting                         |
|            | * Text                                   |
+------------+------------------------------------------+

Response
^^^^^^^^

The new created setting.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "key": "Setting key",
        "namespace": "Setting namespace",
        "value": "Setting value",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PUT /api/settings/:id
---------------------

.. code-block:: js
    :linenos:

    {
        "key": "Setting key",
        "namespace": "Setting namespace",
        "value": "Setting value"
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of setting                    |
+------------+------------------------------------------+

Response
^^^^^^^^

The modified setting.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "key": "Setting key",
        "namespace": "Setting namespace",
        "value": "Setting value",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

DELETE /api/settings/:id
------------------------

Delete a setting by the given ID.

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of setting                    |
+------------+------------------------------------------+
