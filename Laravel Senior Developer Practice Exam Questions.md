
### Laravel Senior Developer Practice Exam Questions

#### Routing and Controllers
1. **What is the correct way to define a resourceful route for a `Post` model with a custom prefix in Laravel?**
   - A. `Route::resource('posts', PostController::class)->prefix('admin');`
   - B. `Route::prefix('admin')->resource('posts', PostController::class);`
   - C. `Route::resource('admin.posts', PostController::class);`
   - D. `Route::group(['prefix' => 'admin'], 'posts', PostController::class);`

2. **How can you restrict a route to only accept POST requests for a specific endpoint?**
   - A. `Route::post('submit', 'FormController@submit');`
   - B. `Route::match(['post'], 'submit', 'FormController@submit');`
   - C. `Route::method('post', 'submit', 'FormController@submit');`
   - D. Both A and B are correct.

3. **What happens if you call `Route::fallback()` in your routes file?**
   - A. It defines a route for the homepage.
   - B. It handles any undefined routes with a custom response.
   - C. It redirects all requests to the login page.
   - D. It caches the route for better performance.

4. **How would you implement a route that accepts a slug parameter with a custom regex constraint?**
   - A. `Route::get('post/{slug}', 'PostController@show')->where('slug', '[a-z0-9-]+');`
   - B. `Route::get('post/{slug}', 'PostController@show')->regex('slug', '[a-z0-9-]+');`
   - C. `Route::get('post/{slug}', 'PostController@show')->constraint('slug', '[a-z0-9-]+');`
   - D. `Route::get('post/{slug}', 'PostController@show')->whereRegex('slug', '[a-z0-9-]+');`

5. **What is the purpose of the `Route::permanentRedirect()` method?**
   - A. It redirects a route with a 302 status code.
   - B. It redirects a route with a 301 status code.
   - C. It caches the redirect for performance.
   - D. It creates a temporary alias for a route.

#### Middleware
6. **How do you apply a middleware group to a specific route?**
   - A. `Route::get('dashboard', 'DashboardController@index')->middleware('auth');`
   - B. `Route::get('dashboard', 'DashboardController@index')->apply('auth');`
   - C. `Route::get('dashboard', 'DashboardController@index')->use('auth');`
   - D. `Route::get('dashboard', 'DashboardController@index')->group('auth');`

7. **What is the correct way to create a custom middleware that logs request headers?**
   - A. `php artisan make:middleware LogHeaders`
   - B. `php artisan middleware:generate LogHeaders`
   - C. `php artisan create:middleware LogHeaders`
   - D. `php artisan make:log LogHeaders`

8. **How can you terminate a middleware after processing the request?**
   - A. Implement the `handle` method only.
   - B. Implement the `terminate` method in the middleware.
   - C. Use the `after` method in the middleware.
   - D. Add a `postHandle` method.

9. **What is the purpose of the `throttle` middleware in Laravel?**
   - A. It limits the number of requests to a route within a time frame.
   - B. It caches responses for faster loading.
   - C. It authenticates users before accessing a route.
   - D. It compresses the response output.

10. **How do you exclude a specific middleware from a route group?**
    - A. `Route::group(['middleware' => 'auth', 'except' => 'guest'], ...);`
    - B. `Route::group(['middleware' => ['auth', '!guest']], ...);`
    - C. `Route::group(['middleware' => 'auth', 'withoutMiddleware' => 'guest'], ...);`
    - D. `Route::group(['middleware' => 'auth'])->except('guest', ...);`

#### Eloquent ORM
11. **How do you define a many-to-many relationship between `User` and `Role` models?**
    - A. `public function roles() { return $this->hasMany(Role::class); }`
    - B. `public function roles() { return $this->belongsToMany(Role::class); }`
    - C. `public function roles() { return $this->hasManyThrough(Role::class); }`
    - D. `public function roles() { return $this->belongsTo(Role::class); }`

12. **What is the purpose of the `with` method in an Eloquent query?**
    - A. It filters records based on a condition.
    - B. It eager loads relationships to avoid N+1 query issues.
    - C. It joins tables in the database.
    - D. It caches the query results.

13. **How do you retrieve only soft-deleted models from a table?**
    - A. `Model::withTrashed()->get();`
    - B. `Model::onlyTrashed()->get();`
    - C. `Model::trashed()->get();`
    - D. `Model::softDeleted()->get();`

14. **What is the correct way to define a custom pivot table for a many-to-many relationship?**
    - A. `return $this->belongsToMany(Role::class, 'user_role');`
    - B. `return $this->belongsToMany(Role::class)->pivot('user_role');`
    - C. `return $this->belongsToMany(Role::class)->table('user_role');`
    - D. `return $this->belongsToMany(Role::class, 'pivot' => 'user_role');`

15. **How can you optimize a query to avoid the N+1 problem when fetching posts with their comments?**
    - A. `Post::all()->load('comments');`
    - B. `Post::with('comments')->get();`
    - C. `Post::join('comments')->get();`
    - D. `Post::has('comments')->get();`

#### Blade Templating
16. **How do you include a partial Blade template with data?**
    - A. `@include('partial', ['data' => $data])`
    - B. `@partial('partial', ['data' => $data])`
    - C. `@use('partial', ['data' => $data])`
    - D. `@render('partial', ['data' => $data])`

17. **What is the purpose of the `@yield` directive in Blade?**
    - A. It defines a section that can be extended by child views.
    - B. It includes an external template.
    - C. It loops through a collection.
    - D. It defines a route.

18. **How do you create a custom Blade directive?**
    - A. Define it in the `boot` method of a service provider using `Blade::directive`.
    - B. Define it in the `routes/web.php` file.
    - C. Create a new class in the `App\Directives` namespace.
    - D. Add it to the `config/blade.php` file.

19. **What is the output of `@if($user->isAdmin()) Admin @else User @endif` if `$user->isAdmin()` returns `true`?**
    - A. Admin
    - B. User
    - C. Admin User
    - D. Nothing

20. **How do you loop through a collection in Blade?**
    - A. `@foreach($items as $item) ... @endforeach`
    - B. `@loop($items as $item) ... @endloop`
    - C. `@for($items as $item) ... @endfor`
    - D. `@each($items as $item) ... @endeach`

#### Authentication and Authorization
21. **How do you implement Laravel’s built-in authentication scaffolding?**
    - A. `php artisan make:auth`
    - B. `php artisan ui:auth`
    - C. `php artisan auth:install`
    - D. `php artisan breeze:install`

22. **What is the purpose of the `Gate` facade in Laravel?**
    - A. It handles user authentication.
    - B. It defines authorization policies.
    - C. It manages database migrations.
    - D. It caches application routes.

23. **How do you protect a route using a Gate?**
    - A. `Route::get('admin', 'AdminController@index')->middleware('can:access-admin');`
    - B. `Route::get('admin', 'AdminController@index')->gate('access-admin');`
    - C. `Route::get('admin', 'AdminController@index')->policy('access-admin');`
    - D. `Route::get('admin', 'AdminController@index')->authorize('access-admin');`

24. **What package is commonly used for API authentication in Laravel?**
    - A. Passport
    - B. Sanctum
    - C. JWT
    - D. Both A and B

25. **How do you check if a user has a specific permission in Laravel Spatie’s Permission package?**
    - A. `$user->hasPermission('edit-posts');`
    - B. `$user->hasPermissionTo('edit-posts');`
    - C. `$user->can('edit-posts');`
    - D. Both B and C

#### Database and Migrations
26. **How do you create a new migration for a `users` table?**
    - A. `php artisan make:migration create_users_table`
    - B. `php artisan migrate:make create_users_table`
    - C. `php artisan migration:create users`
    - D. `php artisan make:table users`

27. **What is the purpose of the `Schema::create` method in a migration?**
    - A. It updates an existing table.
    - B. It deletes a table.
    - C. It creates a new table.
    - D. It renames a table.

28. **How do you add a foreign key constraint in a migration?**
    - A. `$table->foreign('user_id')->references('id')->on('users');`
    - B. `$table->foreignKey('user_id')->on('users');`
    - C. `$table->references('user_id')->foreign('users');`
    - D. `$table->foreign('user_id')->table('users');`

29. **How do you rollback the last migration batch?**
    - A. `php artisan migrate:rollback`
    - B. `php artisan migrate:reset`
    - C. `php artisan migrate:undo`
    - D. `php artisan migrate:back`

30. **What is the purpose of the `DB::raw` method?**
    - A. It executes raw SQL queries.
    - B. It caches database results.
    - C. It encrypts database data.
    - D. It creates a new database connection.

#### Caching and Performance
31. **How do you cache a query result in Laravel for 10 minutes?**
    - A. `Cache::put('key', $data, 10);`
    - B. `Cache::remember('key', 600, function() { return DB::table('data')->get(); });`
    - C. `Cache::store('key', $data, 10);`
    - D. `Cache::save('key', $data, 600);`

32. **What is the purpose of the `php artisan optimize` command?**
    - A. It clears the application cache.
    - B. It compiles configuration and routes for better performance.
    - C. It runs database migrations.
    - D. It generates API documentation.

33. **How do you clear the cache in Laravel?**
    - A. `php artisan cache:clear`
    - B. `php artisan clear:cache`
    - C. `php artisan cache:delete`
    - D. `php artisan cache:flush`

34. **What is the benefit of using Laravel’s query builder over raw SQL?**
    - A. It prevents SQL injection by default.
    - B. It is faster than raw SQL.
    - C. It automatically caches results.
    - D. It supports multiple databases without configuration.

35. **How do you enable query logging in Laravel?**
    - A. `DB::enableQueryLog();`
    - B. `DB::logQueries(true);`
    - C. `DB::queryLog(true);`
    - D. `DB::setLogging(true);`

#### Testing
36. **How do you create a test case in Laravel?**
    - A. `php artisan make:test MyTest`
    - B. `php artisan test:make MyTest`
    - C. `php artisan create:test MyTest`
    - D. `php artisan make:unit MyTest`

37. **What is the purpose of the `assertStatus` method in a test?**
    - A. It checks the HTTP status code of a response.
    - B. It verifies the database state.
    - C. It checks if a user is authenticated.
    - D. It validates JSON structure.

38. **How do you mock a service in a Laravel test?**
    - A. `$this->mock(Service::class, function ($mock) { $mock->shouldReceive('method')->andReturn('value'); });`
    - B. `$this Watermark: https://certificationforlaravel.com/
