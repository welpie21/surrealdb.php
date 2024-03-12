<h1 align="center">
	SurrealDB.php
</h1>
<h3 align="center">
	A SurrealDB driver for PHP
</h3>
<p align="center">
	<a href="https://github.com/welpie21/surrealdb.php/blob/main/LICENSE">
		<img src="https://img.shields.io/github/license/welpie21/surrealdb.php"> 
	</a>
</p>

___

## 🚧 Disclaimer 🚧

This surreal driver for php is done but might be unstable and might have some bugs.
Please use it with caution and report any bugs or issues you might find.

## 📦 About

SurrealDB.php is a driver for PHP to connect to a SurrealDB instance.
It allows you to connect to a SurrealDB instance and perform operations on the database.

## 📚 Documentation

There is no current documentation for this driver. Please refer to
the [SurrealDB documentation](https://surrealdb.com/docs) for more information.
Since the driver has the specs that of the javascript library, it works the same way as the javascript library.

## 📋 Requirements

- PHP 8.3+
- SurrealDB v1.2.0+

## 📥 Installation

```bash
composer require welpie21/surrealdb.php
```

## 🚀 Usage

**Create a new connection to a SurrealDB instance**

```php
use Surreal\SurrealHTTP;
use Surreal\SurrealWebsocket;

// create a surrealdb connection
$db = new \Surreal\SurrealHTTP(
    host: "http://127.0.0.1:8000",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);
```

**🔗 Websocket**

You can also use the websocket protocol to connect to a SurrealDB instance.

```php
// create a surrealdb connection
$db = new \Surreal\SurrealWebsocket(
    host: "ws://127.0.0.1:8000/rpc",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

// the websocket connection is not persistent. You have to close the connection manually.
$db->close();
```

**🔐 Authentication**

You can sign in to a SurrealDB instance using the `signin` method.
The authentication system in the driver is similar to the javascript library. But
it adds one more functionality to the driver.

Since the authentication has its own state you can kind of override the "namespace" and "database"
you defined earlier in the connection. This is useful if you want to sign in to a different namespace or database
with still persisting the connection to the previous namespace and database.

Here we create a connection, and we set the scope for the `signup` and `signin` methods that is using under the hood.

```php
// create a surrealdb connection
$db = new \Surreal\SurrealHTTP(
    host: "http://127.0.0.1:8000",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

// the argument is a keyed array.
// for scope authentication you have to set the correct keys and values for the scope.
$token = $db->signin([
    "user" => "john.doe@gmail.com",
    "pass" => "some-password",
    "ns" => "<namespace>", // <-- this is optional
    "db" => "<database>", // <-- this is optional
    "sc" => "<scope>" // <-- this is optional
]);

// the signin method returns a token that you can use to set the authentication token for the connection.
$db->setToken($token); // <-- the token can be either a string or null.

// we can also set the token to the session
session_start();
$_SESSION["token"] = $token;

// invalidate the token
$db->invalidate(); // <-- basically sets the token to null
```

**🔐 Authentication with Websockets**

The authentication with websockets is similar to the HTTP protocol. The only difference is that you have to `signin` or `signup`
and after you call those methods you have to call the `authenticate` method to authenticate the connection.

```php
// create a surrealdb connection
$db = new \Surreal\SurrealWebsocket(
    host: "ws://127.0.0.1:8000/rpc",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

// the argument is a keyed array.
// for scope authentication you have to set the correct keys and values for the scope.
$token = $db->signin([
    "user" => "some-username",
    "pass" => "some-password",
    "ns" => "<namespace>", // <-- this is optional
    "db" => "<database>", // <-- this is optional
    "sc" => "<scope>" // <-- this is optional
]);

// the signin method returns a token that you can use to set the authentication token for the connection.
$db->authenticate($token);

// we can also set the token to the session
session_start();
$_SESSION["token"] = $token;

// invalidate the token
$db->invalidate(); // <-- basically sets the token to null
```

*Keep in mind this can work buggy and might not work as expected. Please use it with caution.*

**🔍 Querying** <br>

The driver has a similar querying system as the javascript library. You can use the `sql` method to perform operations
on the database.

```php
// create a surrealdb connection
$db = new \Surreal\SurrealHTTP(
    host: "http://127.0.0.1:8000",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

// create a table
$db->sql("CREATE product:apple CONTENT { name: 'Apple', price: 1.99 }");

// get the table
$product = $db->sql("SELECT * FROM ONLY product:apple");
$apple = $product["apple"] ?? null; // <-- this can be null or return the apple object

// create, update and delete methods
$db->create("product", ["name" => "Banana", "price" => 2.99]);
$db->update("product:banana", ["price" => 3.99]);
$db->merge("product:banana", ["price" => 4.99]);
$db->delete("product:banana");
```

**🔗 Websockets**

The driver supports websockets. You can use the `SurrealWebsocket` class to connect to a SurrealDB instance using the websocket protocol.

```php

// create a surrealdb connection
$db = new \Surreal\SurrealWebsocket(
    host: "ws://127.0.0.1:8000/rpc",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

$db->create("product", ["name" => "Banana", "price" => 2.99]);
$db->update("product:banana", ["price" => 3.99]);
$db->merge("product:banana", ["price" => 4.99]);
$db->delete("product:banana");

$db->patch("product:banana", [
    \Surreal\classes\SurrealPatch::create("replace", "/price", 5.99),
    // or
    [
        "op" => "replace",
        "path" => "/name",
        "value" => "Banana"
    ]
]);

$db->let("name", "banana");
$db->let("price", 5.99);

$db->sql('CREATE product CONTENT { name: $name, price: $price }');

$db->unset("name");
$db->unset("price");

// the websocket connection is not persistent. You have to close the connection manually.
$db->close();
```

**📦 Import & Export ( HTTP ONLY )**

You can import and export data from the database using the `import` and `export` methods.

```php
$db = new \Surreal\SurrealHTTP(
    host: "http://127.0.0.1:8000",
    target: [
        "namespace" => "<namespace>",
        "database" => "<database>"
    ]
);

// import data
$file = file_get_contents("some_path_to_surql_file.surql");
$result = $db->import($file, "username", "password");

// export data
$file = $db->export("username", "password"); // <-- returns the whole file as a string
file_put_contents("some_path_to_surql_file.surql", $file); // <-- save the file
```

## Supported features

- [x] Authentication
- [x] Querying
- [x] Import & Export
- [x] Create, Update, Merge, Patch and Delete
- [x] HTTP and Websocket protocol
- [x] Error handling

## Unsupported

- [ ] Live queries
- [ ] CBOR

## Roadmap

**v1.1.0**
- [ ] Add support for CBOR protocol
- [ ] Add custom Data Type Classes ( RecordID, UUID, Decimal and Duration ) for CBOR

**v1.2.0**
- [ ] Add support for live queries

**v1.3.0**
- [ ] Psalm and PHPStan support for better type checking and code quality

## Coverage
All the methods in the driver are covered with tests. The coverage is 98.14% as of version v1.0.0.

![coverage.png](assets%2Fcoverage.png)