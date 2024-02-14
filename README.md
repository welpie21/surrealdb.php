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

<br>

## 🚧 Disclaimer 🚧
This project is currently in heavy development and is still lacking many features. Do not use in production!

## Requirements

- PHP 8.1+
- SurrealDB v1.2.0+

## Roadmap

- Fully functional SurrealDB driver.
- CBOR encoder en decoder under the hood
- ORM / Query builder support
- Laravel or other framework support
- Examples

## Support list

- Authentication
- Database connection via HTTP
- all supported data type
- call methods to interact with the database such as:
  - `status`
  - `version`
  - `import`
  - `export`
  - `signin`
  - `signup`
  - `create`
  - `update`
  - `merge`
  - `delete`
  - `sql`
  - `close`
  - `invalidate`

## Support that is not yet implemented

This surrealdb driver has an CBOR implementation that needs some work. Which will be done in soon as possible.
But for now it only supports the following data types:
- String
- Number
- Boolean
- Null
- Array
- Object

The only that is not supported is the following:
- Date
- RecordID
- Duration
- Decimal
- UUID

Currently, it uses the CBOR encode / decode from "https://github.com/2tvenom/CBOREncode". It doesn't support tags yet.
I've made a fork which will have the support for tags. But it's not yet merged into the main branch.