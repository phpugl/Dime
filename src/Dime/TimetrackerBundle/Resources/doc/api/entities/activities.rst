.. index::
   single: Activities

Activities
==========

GET /api/activities
-------------------

Returns a list with activities.

+------------+-----------+------------------------------------------+
| Parameters | Example   | Description                              |
+============+===========+==========================================+
| count      | count=30  | Specify the limit of the activity list.  |
+------------+-----------+------------------------------------------+
| page       | page=1    | Specify the offset of the activity list. |
+------------+-----------+------------------------------------------+
| filter     | filter[]= | Filter the activity list.                |
+------------+-----------+------------------------------------------+


Filter
^^^^^^

Filter the list of activities by following parameters.

+------------+------------------------------+-----------------------------------------+
| Parameters | Definition                   | Description                             |
+============+==============================+=========================================+
| user       | filter[user]=ID              | Filter by user id.                      |
+------------+------------------------------+-----------------------------------------+
| customer   | filter[customer]=ID          | Filter by customer id                   |
+------------+------------------------------+-----------------------------------------+
| project    | filter[project]=ID           | Filter by project id                    |
+------------+------------------------------+-----------------------------------------+
| service    | filter[service]=ID           | Filter by service id                    |
+------------+------------------------------+-----------------------------------------+
| active     | filter[active]=true|false    | Filter by active / running timeslice    |
+------------+------------------------------+-----------------------------------------+
| date       | filter[date]=YYYY-MM-DD      | Filter by date                          |
|            |                              |   * single date with YYYY-MM-DD format  |
|            | filter[date][]=YYYY-MM-DD    |   * array with to entry for range       |
|            |                              |                                         |
+------------+------------------------------+-----------------------------------------+

Response
^^^^^^^^

An array with activities.


.. code-block:: js
    :linenos:

    [
        {
            "id": 1,
            "user": {},
            "customer": {},
            "project": {},
            "service": {},
            "timeslices": [],
            "description": "Activity description",
            "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
            "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
        }
    ]


GET /api/activities/:id
-----------------------

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of activity                   |
+------------+------------------------------------------+

Response
^^^^^^^^

A single activity.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "customer": {},
        "project": {},
        "service": {},
        "timeslices": [],
        "description": "Activity description",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PULL /api/activities
--------------------

.. code-block:: js
    :linenos:

    {
        "user": ID,
        "customer": ID,
        "project": ID,
        "service": ID,
        "timeslices": [??],
        "description": "Activity description",
    }

or

.. code-block:: js
    :linenos:

    {
        "parse": "Text with a certain structure which should be parsed."
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| parse      | Text will be processed by controller.    |
+------------+------------------------------------------+

Response
^^^^^^^^

The new created activity.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "customer": {},
        "project": {},
        "service": {},
        "timeslices": [],
        "description": "Activity description",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

PUT /api/activities/:id
-----------------------

.. code-block:: js
    :linenos:

    {
        "user": ID,
        "customer": ID,
        "project": ID,
        "service": ID,
        "timeslices": [??],
        "description": "Activity description",
    }

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of activity                   |
+------------+------------------------------------------+

Response
^^^^^^^^

The modified activity.

.. code-block:: js
    :linenos:

    {
        "id": 1,
        "user": {},
        "customer": {},
        "project": {},
        "service": {},
        "timeslices": [],
        "description": "Activity description",
        "createdAt": "DATETIME (YYYY-MM-DD HH:mm:ss)",
        "updatedAt": "DATETIME (YYYY-MM-DD HH:mm:ss)"
    }

DELETE /api/activities/:id
--------------------------

Delete a activity by the given ID.

+------------+------------------------------------------+
| Parameters | Description                              |
+============+==========================================+
| id         | Identifier of activity                   |
+------------+------------------------------------------+
