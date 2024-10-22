What's the difference between $query->update(), $model->update(), and $model->updateQuietly() in Laravel, and when would you use each?

$model->update() method eventually delegates to the query builder update method, but it does numerous things before that:

- it protects against mass assignment vulnerabilities and throws exception or simply ignores not fillable properties
- it runs attributes casts
- it fires various eloquent events like `updating`, `updated`
- if everything is good, it instantiates the query builder and delegates to its update method

Query builder which is part of the Eloquent ORM perfomrs the update - it's job is to take the query that the user built using fluent api, get the PDO instance, transform the query into raw sql query, retrieve the bindings and run the sql query eventually.

updateQuietly() method on eloquent model does exactly the same as the update() method (on the MOdel), but it doesn't fire any events. useful sometimes in tests.
