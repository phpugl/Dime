Tag
========

GET /api/tags
-----------------

Returns a list with tags.

+------------+-----------+------------------------------------------+
| Parameters | Example   | Description                              |
+============+===========+==========================================+
| count      | count=30  | Specify the limit of the tag list.       |
+------------+-----------+------------------------------------------+
| page       | page=1    | Specify the offset of the tag list.      |
+------------+-----------+------------------------------------------+
| filter     | filter[]= | Filter the tag list                      |
+------------+------------------------------------------------------+

Filter
^^^^^^

Filter the list of tags by following parameters.

+------------+------------------------------+-----------------------------------------+
| Parameters | Definition                   | Description                             |
+============+==============================+=========================================+
| user       | filter[user]=1               | Filter by user id.                      |
+------------+------------------------------+-----------------------------------------+
| search     | filter[search]=TEXT          | Search for tags                         |
+------------+------------------------------+-----------------------------------------+

Response
^^^^^^^^

An array with tags.

.. code-block:: js
    :linenos:

    [
        {
            "id": 1,
            "name": "Tagname",
            "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
            "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
        }
    ]


GET /api/tags/:id
---------------------

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of tag                        |
+------------+------------------------------------------+

Response
^^^^^^^^

A single tag.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "name": "Tagname",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

POST /api/tags
------------------

.. code-block:: js
    :linenos:

    {
        "name": "Tagname"
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| name       | Name of tag (Unique for user)            |
+------------+------------------------------------------+

Response
^^^^^^^^

The new created tag.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "name": "Tagname",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PUT /api/tags/:id
---------------------

.. code-block:: js
    :linenos:

    {
        "name": "Tagname"
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of tag                        |
+------------+------------------------------------------+

Response
^^^^^^^^

The modified tag.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "name": "Tagname",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

DELETE /api/tags/:id
------------------------

Delete a tag by the given ID.

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of tag                        |
+------------+------------------------------------------+
