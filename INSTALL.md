INSTALL
=======

1. Activate the extension following the standard eZ Publish procedures (remember to regenerate autoloads).

2. Execute the query in sql/mysql/timed_objects.sql in your database.

3. Add events to the "after publish" trigger.

4. Create cronjobs for "timed_objects" and "expire_objects" cronjob parts.

5. Done.