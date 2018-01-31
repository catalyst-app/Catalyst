<?php

namespace Catalyst\Database;

/**
 * Maps tables to the table names in the database
 * 
 * Compartmentalized due to MySQL handling and potential growth/modifications.
 */
class Tables {
	public const API_AUTHORIZATIONS = "api_authorizations";
	public const API_KEYS = "api_keys";
	public const ARTIST_PAGES = "artist_pages";
	public const ARTIST_SOCIAL_MEDIA = "artist_social_media";
	public const ARTIST_STREAMING_INTEGRATIONS = "artist_streaming_integrations";
	public const CHARACTER_IMAGES = "character_images";
	public const CHARACTERS = "characters";
	public const COMMISSION_TYPE_ATTRIBUTE_GROUPS = "commission_type_attribute_groups";
	public const COMMISSION_TYPE_ATTRIBUTES = "commission_type_attributes";
	public const COMMISSION_TYPE_IMAGES = "commission_type_images";
	public const COMMISSION_TYPE_MODIFIERS = "commission_type_modifiers";
	public const COMMISSION_TYPE_PAYMENT_OPTIONS = "commission_type_payment_options";
	public const COMMISSION_TYPE_STAGES = "commission_type_stages";
	public const COMMISSION_TYPES = "commission_types";
	public const EMAIL_LIST = "email_list";
	public const FEATURE_BOARD_COMMENTS = "feature_board_comments";
	public const FEATURE_BOARD_GROUPS = "feature_board_groups";
	public const FEATURE_BOARD_ITEMS = "feature_board_items";
	public const FEATURE_BOARD_STATUSES = "feature_board_statuses";
	public const FEATURE_BOARD_VOTES = "feature_board_votes";
	public const INTEGRATIONS_META = "social_media_meta";
	public const USER_SOCIAL_MEDIA = "user_social_media";
	public const USER_WISHLISTS = "user_wishlists";
	public const USERS = "users";
}
