the main issue with the source code was the inefficient memory consumption.

In case of thousands of records in the DB, the script would consume a lot of memory and would be and die

I have refactored using the Lazy collection to avoid loading all the records in memory at once, which allows us to work with large datasets without memory issues.

However for the best performance, I would recommend using the database pagination and limit the number of records fetched at once.

I have also leveraged lazy loading of the relationships and used nice technique to build a relationship on the fly, in this case the `lastAddedItem` relationship. it can be useful when not only a single field like in our case is needed to be fetched from the related model, that is originally is a hasMany relationship.

Also the DB indexes should be reviewed, for example an index on the `completed_at` field would be beneficial
