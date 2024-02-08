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

## Lacks support

- Only supports String based ID's on records instead of:
    - number
    - records
    - arrays