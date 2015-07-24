http://kodeinfo.com/post/secure-login-system-phplaravel-1
http://blog.fagai.net/2014/05/27/laravel4-sentry-auth-tutorial/

Japan Validation lang/ja/validation.php
https://github.com/laravel-ja/laravel/blob/master/app/lang/ja/validation.php

Javascript Notification
http://sciactive.github.io/pnotify/

image validator rules laravel
http://packalyst.com/packages/package/cviebrock/image-validator

Laravel logo Laravel
5.1
Documentation Laracasts Lumen Services  Conference  Community

Home
Laracasts
Lumen
Services
Conference
Community
Documentation

Prologue
Release Notes
Upgrade Guide
Contribution Guide
API Documentation
Setup
Installation
Homestead
The Basics
Routing
Middleware
Controllers
Requests
Responses
Views
Blade Templates
Architecture Foundations
Request Lifecycle
Application Structure
Service Providers
Service Container
Contracts
Facades
Services
Authentication
Artisan Console
Billing
Cache
Collections
Elixir
Encryption
Errors & Logging
Events
Filesystem / Cloud Storage
Hashing
Helpers
Localization
Mail
Package Development
Pagination
Queues
Redis
Session
SSH Tasks
Task Scheduling
Testing
Validation
Database
Getting Started
Query Builder
Migrations
Seeding
Eloquent ORM
Getting Started
Relationships
Collections
Mutators
Serialization
Prologue
Release Notes
Upgrade Guide
Contribution Guide
API Documentation
Setup
Installation
Homestead
The Basics
Routing
Middleware
Controllers
Requests
Responses
Views
Blade Templates
Architecture Foundations
Request Lifecycle
Application Structure
Service Providers
Service Container
Contracts
Facades
Services
Authentication
Artisan Console
Billing
Cache
Collections
Elixir
Encryption
Errors & Logging
Events
Filesystem / Cloud Storage
Hashing
Helpers
Localization
Mail
Package Development
Pagination
Queues
Redis
Session
SSH Tasks
Task Scheduling
Testing
Validation
Database
Getting Started
Query Builder
Migrations
Seeding
Eloquent ORM
Getting Started
Relationships
Collections
Mutators
Serialization

Validation

Introduction
Validation Quickstart
Defining The Routes
Creating The Controller
Writing The Validation Logic
Displaying The Validation Errors
AJAX Requests & Validation
Other Validation Approaches
Manually Creating Validators
Form Request Validation
Working With Error Messages
Custom Error Messages
Available Validation Rules
Conditionally Adding Rules
Custom Validation Rules

Introduction

Laravel provides several different approaches to validate your application's incoming data. By default, Laravel's base controller class uses a ValidatesRequests trait which provides a convenient method to validate incoming HTTP request with a variety of powerful validation rules.


Validation Quickstart

To learn about Laravel's powerful validation features, let's look at a complete example of validating a form and displaying the error messages back to the user.


Defining The Routes

First, let's assume we have the following routes defined in our app/Http/routes.php file:

// Display a form to create a blog post...
Route::get('post/create', 'PostController@create');

// Store a new blog post...
Route::post('post', 'PostController@store');
Of course, the GET route will display a form for the user to create a new blog post, while the POST route will store the new blog post in the database.


Creating The Controller

Next, let's take a look at a simple controller that handles these routes. We'll leave the store method empty for now:

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Show the form the create a new blog post.
     *
     * @return Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate and store the blog post...
    }
}

Writing The Validation Logic

Now we are ready to fill in our store method with the logic to validate the new blog post. If you examine your application's base controller (App\Http\Controllers\Controller) class, you will see that the class uses a ValidatesRequests trait. This trait provides a convenient validate method in all of your controllers.

The validate method accepts an incoming HTTP request and a set of validation rules. If the validation rules pass, your code will keep executing normally; however, if validation fails, an exception will be thrown and the proper error response will automatically be sent back to the user. In the case of a traditional HTTP request, a redirect response will be generated, while a JSON response will be sent for AJAX requests.

To get a better understanding of the validate method, let's jump back into the store method:

/**
 * Store a new blog post.
 *
 * @param  Request  $request
 * @return Response
 */
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // The blog post is valid, store in database...
}
As you can see, we simply pass the incoming HTTP request and desired validation rules into the validate method. Again, if the validation fails, the proper response will automatically be generated. If the validation passes, our controller will continue executing normally.

A Note On Nested Attributes

If your HTTP request contains "nested" parameters, you may specify them in your validation rules using "dot" syntax:

$this->validate($request, [
    'title' => 'required|unique:posts|max:255',
    'author.name' => 'required',
    'author.description' => 'required',
]);

Displaying The Validation Errors

So, what if the incoming request parameters do not pass the given validation rules? As mentioned previously, Laravel will automatically redirect the user back to their previous location. In addition, all of the validation errors will automatically be flashed to the session.

Again, notice that we did not have to explicitly bind the error messages to the view in our GET route. This is because Laravel will always check for errors in the session data, and automatically bind them to the view if they are available. So, it is important to note that an $errors variable will always be available in all of your views on every request, allowing you to conveniently assume the $errors variable is always defined and can be safely used. The $errors variable will be an instance of Illuminate\Support\MessageBag. For more information on working with this object, check out its documentation.

So, in our example, the user will be redirected to our controller's create method when validation fails, allowing us to display the error messages in the view:

<!-- /resources/views/post/create.blade.php -->

<h1>Create Post</h1>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create Post Form -->

Customizing The Flashed Error Format

If you wish to customize the format of the validation errors that are flashed to the session when validation fails, override the formatValidationErrors on your base controller. Don't forget to import the Illuminate\Contracts\Validation\Validator class at the top of the file:

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * {@inheritdoc}
     */
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}

AJAX Requests & Validation

In this example, we used a traditional form to send data to the application. However, many applications use AJAX requests. When using the validate method during an AJAX request, Laravel will not generate a redirect response. Instead, Laravel generates a JSON response containing all of the validation errors. This JSON response will be sent with a 422 HTTP status code.


Other Validation Approaches


Manually Creating Validators

If you do not want to use the ValidatesRequests trait's validate method, you may create a validator instance manually using the Validator facade. The make method on the facade generates a new validator instance:

<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('post/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Store the blog post...
    }
}
The first argument passed to the make method is the data under validation. The second argument is the validation rules that should be applied to the data.

After checking if the request failed to pass validation, you may use the withErrors method to flash the error messages to the session. When using this method, the $errors variable will automatically be shared with your views after redirection, allowing you to easily display them back to the user. The withErrors method accepts a validator, a MessageBag, or a PHP array.

Named Error Bags

If you have multiple forms on a single page, you may wish to name the MessageBag of errors, allowing you to retrieve the error messages for a specific form. Simply pass a name as the second argument to withErrors:

return redirect('register')
            ->withErrors($validator, 'login');
You may then access the named MessageBag instance from the $errors variable:

{{ $errors->login->first('email') }}
After Validation Hook

The validator also allows you to attach callbacks to be run after validation is completed. This allows you to easily perform further validation and even add more error messages to the message collection. To get started, use the after method on a validator instance:

$validator = Validator::make(...);

$validator->after(function($validator) {
    if ($this->somethingElseIsInvalid()) {
        $validator->errors()->add('field', 'Something is wrong with this field!');
    }
});

if ($validator->fails()) {
    //
}

Form Request Validation

For more complex validation scenarios, you may wish to create a "form request". Form requests are custom request classes that contain validation logic. To create a form request class, use the make:request Artisan CLI command:

php artisan make:request StoreBlogPostRequest
The generated class will be placed in the app/Http/Requests directory. Let's add a few validation rules to the rules method:

/**
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
public function rules()
{
    return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ];
}
So, how are the validation rules evaluated? All you need to do is type-hint the request on your controller method. The incoming form request is validated before the controller method is called, meaning you do not need to clutter your controller with any validation logic:

/**
 * Store the incoming blog post.
 *
 * @param  StoreBlogPostRequest  $request
 * @return Response
 */
public function store(StoreBlogPostRequest $request)
{
    // The incoming request is valid...
}
If validation fails, a redirect response will be generated to send the user back to their previous location. The errors will also be flashed to the session so they are available for display. If the request was an AJAX request, a HTTP response with a 422 status code will be returned to the user including a JSON representation of the validation errors.

Authorizing Form Requests

The form request class also contains an authorize method. Within this method, you may check if the authenticated user actually has the authority to update a given resource. For example, if a user is attempting to update a blog post comment, do they actually own that comment? For example:

/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
public function authorize()
{
    $commentId = $this->route('comment');

    return Comment::where('id', $commentId)
                  ->where('user_id', Auth::id())->exists();
}
Note the call to the route method in the example above. This method grants you access to the URI parameters defined on the route being called, such as the {comment} parameter in the example below:

Route::post('comment/{comment}');
If the authorize method returns false, a HTTP response with a 403 status code will automatically be returned and your controller method will not execute.

If you plan to have authorization logic in another part of your application, simply return true from the authorize method:

/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
public function authorize()
{
    return true;
}
Customizing The Flashed Error Format

If you wish to customize the format of the validation errors that are flashed to the session when validation fails, override the formatErrors on your base request (App\Http\Requests\Request). Don't forget to import the Illuminate\Contracts\Validation\Validator class at the top of the file:

/**
 * {@inheritdoc}
 */
protected function formatErrors(Validator $validator)
{
    return $validator->errors()->all();
}

Working With Error Messages

After calling the errors method on a Validator instance, you will receive an Illuminate\Support\MessageBag instance, which has a variety of convenient methods for working with error messages.

Retrieving The First Error Message For A Field

To retrieve the first error message for a given field, use the first method:

$messages = $validator->errors();

echo $messages->first('email');
Retrieving All Error Messages For A Field

If you wish to simply retrieve an array of all of the messages for a given field, use the get method:

foreach ($messages->get('email') as $message) {
    //
}
Retrieving All Error Messages For All Fields

To retrieve an array of all messages for all fields, use the all method:

foreach ($messages->all() as $message) {
    //
}
Determining If Messages Exist For A Field

if ($messages->has('email')) {
    //
}
Retrieving An Error Message With A Format

echo $messages->first('email', '<p>:message</p>');
Retrieving All Error Messages With A Format

foreach ($messages->all('<li>:message</li>') as $message) {
    //
}

Custom Error Messages

If needed, you may use custom error messages for validation instead of the defaults. There are several ways to specify custom messages. First, you may pass the custom messages as the third argument to the Validator::make method:

$messages = [
    'required' => 'The :attribute field is required.',
];

$validator = Validator::make($input, $rules, $messages);
In this example, the :attribute place-holder will be replaced by the actual name of the field under validation. You may also utilize other place-holders in validation messages. For example:

$messages = [
    'same'    => 'The :attribute and :other must match.',
    'size'    => 'The :attribute must be exactly :size.',
    'between' => 'The :attribute must be between :min - :max.',
    'in'      => 'The :attribute must be one of the following types: :values',
];
Specifying A Custom Message For A Given Attribute

Sometimes you may wish to specify a custom error messages only for a specific field. You may do so using "dot" notation. Specify the attribute's name first, followed by the rule:

$messages = [
    'email.required' => 'We need to know your e-mail address!',
];

Specifying Custom Messages In Language Files

In many cases, you may wish to specify your attribute specific custom messages in a language file instead of passing them directly to the Validator. To do so, add your messages to custom array in the resources/lang/xx/validation.php language file.

'custom' => [
    'email' => [
        'required' => 'We need to know your e-mail address!',
    ],
],

Available Validation Rules

Below is a list of all available validation rules and their function:

Accepted
Active URL
After (Date)
Alpha
Alpha Dash
Alpha Numeric
Array
Before (Date)
Between
Boolean
Confirmed
Date
Date Format
Different
Digits
Digits Between
E-Mail
Exists (Database)
Image (File)
In
Integer
IP Address
Max
MIME Types (File)
Min
Not In
Numeric
Regular Expression
Required
Required If
Required With
Required With All
Required Without
Required Without All
Same
Size
String
Timezone
Unique (Database)
URL

accepted

The field under validation must be yes, on, 1, or true. This is useful for validating "Terms of Service" acceptance.


active_url

The field under validation must be a valid URL according to the checkdnsrr PHP function.


after:date

The field under validation must be a value after a given date. The dates will be passed into the strtotime PHP function:

'start_date' => 'required|date|after:tomorrow'
Instead of passing a date string to be evaluated by strtotime, you may specify another field to compare against the date:

'finish_date' => 'required|date|after:start_date'

alpha

The field under validation must be entirely alphabetic characters.


alpha_dash

The field under validation may have alpha-numeric characters, as well as dashes and underscores.


alpha_num

The field under validation must be entirely alpha-numeric characters.


array

The field under validation must be a PHP array.


before:date

The field under validation must be a value preceding the given date. The dates will be passed into the PHP strtotime function.


between:min,max

The field under validation must have a size between the given min and max. Strings, numerics, and files are evaluated in the same fashion as the size rule.


boolean

The field under validation must be able to be cast as a boolean. Accepted input are true, false, 1, 0, "1", and "0".


confirmed

The field under validation must have a matching field of foo_confirmation. For example, if the field under validation is password, a matching password_confirmation field must be present in the input.


date

The field under validation must be a valid date according to the strtotime PHP function.


date_format:format

The field under validation must match the given format. The format will be evaluated using the PHP date_parse_from_format function. You should use either date or date_format when validating a field, not both.


different:field

The field under validation must have a different value than field.


digits:value

The field under validation must be numeric and must have an exact length of value.


digits_between:min,max

The field under validation must have a length between the given min and max.


email

The field under validation must be formatted as an e-mail address.


exists:table,column

The field under validation must exist on a given database table.

Basic Usage Of Exists Rule

'state' => 'exists:states'
Specifying A Custom Column Name

'state' => 'exists:states,abbreviation'
You may also specify more conditions that will be added as "where" clauses to the query:

'email' => 'exists:staff,email,account_id,1'
Passing NULL as a "where" clause value will add a check for a NULL database value:

'email' => 'exists:staff,email,deleted_at,NULL'

image

The file under validation must be an image (jpeg, png, bmp, gif, or svg)


in:foo,bar,...

The field under validation must be included in the given list of values.


integer

The field under validation must be an integer.


ip

The field under validation must be an IP address.


max:value

The field under validation must be less than or equal to a maximum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.


mimes:foo,bar,...

The file under validation must have a MIME type corresponding to one of the listed extensions.

Basic Usage Of MIME Rule

'photo' => 'mimes:jpeg,bmp,png'

min:value

The field under validation must have a minimum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.


not_in:foo,bar,...

The field under validation must not be included in the given list of values.


numeric

The field under validation must be numeric.


regex:pattern

The field under validation must match the given regular expression.

Note: When using the regex pattern, it may be necessary to specify rules in an array instead of using pipe delimiters, especially if the regular expression contains a pipe character.


required

The field under validation must be present in the input data.


required_if:anotherfield,value,...

The field under validation must be present if the anotherfield field is equal to any value.


required_with:foo,bar,...

The field under validation must be present only if any of the other specified fields are present.


required_with_all:foo,bar,...

The field under validation must be present only if all of the other specified fields are present.


required_without:foo,bar,...

The field under validation must be present only when any of the other specified fields are not present.


required_without_all:foo,bar,...

The field under validation must be present only when all of the other specified fields are not present.


same:field

The given field must match the field under validation.


size:value

The field under validation must have a size matching the given value. For string data, value corresponds to the number of characters. For numeric data, value corresponds to a given integer value. For files, size corresponds to the file size in kilobytes.


string

The field under validation must be a string.


timezone

The field under validation must be a valid timezone identifier according to the timezone_identifiers_list PHP function.


unique:table,column,except,idColumn

The field under validation must be unique on a given database table. If the column option is not specified, the field name will be used.

Specifying A Custom Column Name:

'email' => 'unique:users,email_address'
Custom Database Connection

Occasionally, you may need to set a custom connection for database queries made by the Validator. As seen above, setting unique:users as a validation rule will use the default database connection to query the database. To override this, specify the connection followed by the table name using "dot" syntax:

'email' => 'unique:connection.users,email_address'
Forcing A Unique Rule To Ignore A Given ID:

Sometimes, you may wish to ignore a given ID during the unique check. For example, consider an "update profile" screen that includes the user's name, e-mail address, and location. Of course, you will want to verify that the e-mail address is unique. However, if the user only changes the name field and not the e-mail field, you do not want a validation error to be thrown because the user is already the owner of the e-mail address. You only want to throw a validation error if the user provides an e-mail address that is already used by a different user. To tell the unique rule to ignore the user's ID, you may pass the ID as the third parameter:

'email' => 'unique:users,email_address,'.$user->id
Adding Additional Where Clauses:

You may also specify more conditions that will be added as "where" clauses to the query:

'email' => 'unique:users,email_address,NULL,id,account_id,1'
In the rule above, only rows with an account_id of 1 would be included in the unique check.


url

The field under validation must be a valid URL according to PHP's filter_var function.


Conditionally Adding Rules

In some situations, you may wish to run validation checks against a field only if that field is present in the input array. To quickly accomplish this, add the sometimes rule to your rule list:

$v = Validator::make($data, [
    'email' => 'sometimes|required|email',
]);
In the example above, the email field will only be validated if it is present in the $data array.

Complex Conditional Validation

Sometimes you may wish to add validation rules based on more complex conditional logic. For example, you may wish to require a given field only if another field has a greater value than 100. Or, you may need two fields to have a given value only when another field is present. Adding these validation rules doesn't have to be a pain. First, create a Validator instance with your static rules that never change:

$v = Validator::make($data, [
    'email' => 'required|email',
    'games' => 'required|numeric',
]);
Let's assume our web application is for game collectors. If a game collector registers with our application and they own more than 100 games, we want them to explain why they own so many games. For example, perhaps they run a game re-sell shop, or maybe they just enjoy collecting. To conditionally add this requirement, we can use the sometimes method on the Validator instance.

$v->sometimes('reason', 'required|max:500', function($input) {
    return $input->games >= 100;
});
The first argument passed to the sometimes method is the name of the field we are conditionally validating. The second argument is the rules we want to add. If the Closure passed as the third argument returns true, the rules will be added. This method makes it a breeze to build complex conditional validations. You may even add conditional validations for several fields at once:

$v->sometimes(['reason', 'cost'], 'required', function($input) {
    return $input->games >= 100;
});
Note: The $input parameter passed to your Closure will be an instance of Illuminate\Support\Fluent and may be used to access your input and files.

Custom Validation Rules

Laravel provides a variety of helpful validation rules; however, you may wish to specify some of your own. One method of registering custom validation rules is using the extend method on the Validator facade. Let's use this method within a service provider to register a custom validation rule:

<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('foo', function($attribute, $value, $parameters) {
            return $value == 'foo';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
The custom validator Closure receives three arguments: the name of the $attribute being validated, the $value of the attribute, and an array of $parameters passed to the rule.

You may also pass a class and method to the extend method instead of a Closure:

Validator::extend('foo', 'FooValidator@validate');
Defining The Error Message

You will also need to define an error message for your custom rule. You can do so either using an inline custom message array or by adding an entry in the validation language file. This message should be placed in the first level of the array, not within the custom array, which is only for attribute-specific error messages:

"foo" => "Your input was invalid!",

"accepted" => "The :attribute must be accepted.",

// The rest of the validation error messages...
When creating a custom validation rule, you may sometimes need to define custom place-holder replacements for error messages. You may do so by creating a custom Validator as described above then making a call to the replacer method on the Validator facade. You may do this within the boot method of a service provider:

/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    Validator::extend(...);

    Validator::replacer('foo', function($message, $attribute, $rule, $parameters) {
        return str_replace(...);
    });
}
Documentation Laracasts Lumen Services  Conference  Community
Laravel is a trademark of Taylor Otwell. Copyright © Taylor Otwell.

Design by Jack McDade