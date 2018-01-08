<?php

namespace Redacted\Database;

define("DB_DRIVER", "mysql");
define("DB_SERVER", "localhost");
define("DB_PORT", 3306);
define("DB_NAME", "REDACTED");
define("DB_USER", "REDACTED");
// define("DB_PASSWORD", .); DEFINED IN SECRETS.PHP, WAS REGENERATED

define("DB_TABLES", [
	"artist_pages" => "artist_pages",
	"artist_social_media" => "artist_social_media",
	"artist_social_media" => "artist_social_media",
	"artist_streaming_integrations" => "artist_streaming_integrations",
	"character_images" => "character_images",
	"characters" => "characters",
	"commission_type_attribute_groups" => "commission_type_attribute_groups",
	"commission_type_attributes" => "commission_type_attributes",
	"commission_type_images" => "commission_type_images",
	"commission_type_modifiers" => "commission_type_modifiers",
	"commission_type_payment_options" => "commission_type_payment_options",
	"commission_type_stages" => "commission_type_stages",
	"commission_types" => "commission_types",
	"integrations_meta" => "social_media_meta",
	"user_social_media" => "user_social_media",
	"user_wishlists" => "user_wishlists",
	"users" => "users",
]);

$GLOBALS["dbh"] = new \PDO(DB_DRIVER.":host=".DB_SERVER.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASSWORD);
$GLOBALS["dbh"]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
