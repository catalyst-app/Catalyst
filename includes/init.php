<?php

define("DEVEL",1);

require_once __DIR__."/Secrets.php";

require_once __DIR__."/Artist/Artist.php";
require_once __DIR__."/Character/Character.php";
require_once __DIR__."/Color.php";
require_once __DIR__."/CommissionType/CommissionType.php";
require_once __DIR__."/Database/Artist/EditArtist.php";
require_once __DIR__."/Database/Artist/NewArtist.php";
require_once __DIR__."/Database/Character/EditCharacter.php";
require_once __DIR__."/Database/Character/NewCharacter.php";
require_once __DIR__."/Database/CommissionType/Attributes.php";
require_once __DIR__."/Database/CommissionType/DeleteCommissionType.php";
require_once __DIR__."/Database/CommissionType/EditCommissionType.php";
require_once __DIR__."/Database/CommissionType/NewCommissionType.php";
require_once __DIR__."/Database/CommissionType/Wishlist.php";
require_once __DIR__."/Database/Connector.inc.php";
require_once __DIR__."/Database/Database.php";
require_once __DIR__."/Database/FeatureBoard/Comment.php";
require_once __DIR__."/Database/FeatureBoard/Groups.php";
require_once __DIR__."/Database/FeatureBoard/Item.php";
require_once __DIR__."/Database/FeatureBoard/NewFeature.php";
require_once __DIR__."/Database/Integrations/Meta.php";
require_once __DIR__."/Database/SocialMedia.php";
require_once __DIR__."/Database/User/Deactivate.php";
require_once __DIR__."/Database/User/EmailVerification.php";
require_once __DIR__."/Database/User/Login.php";
require_once __DIR__."/Database/User/Register.php";
require_once __DIR__."/Database/User/Settings.php";
require_once __DIR__."/Database/User/TOTPLogin.php";
require_once __DIR__."/Email.php";
require_once __DIR__."/Form/Captcha.php";
require_once __DIR__."/Form/FileUpload.php";
require_once __DIR__."/Form/Flags.php";
require_once __DIR__."/Form/FormHTML.php";
require_once __DIR__."/Form/FormJS.php";
require_once __DIR__."/Form/FormPHP.php";
require_once __DIR__."/Integrations/SocialMedia.php";
require_once __DIR__."/Message/Message.php";
require_once __DIR__."/Page/Header/Header.php";
require_once __DIR__."/Page/Navigation/Navbar.php";
require_once __DIR__."/Page/UniversalFunctions.php";
require_once __DIR__."/Page/Values.php";
require_once __DIR__."/phpqrcode.php";
require_once __DIR__."/Response.php";
require_once __DIR__."/Tokens.php";
require_once __DIR__."/User/User.php";

require_once __DIR__."/vendor/autoload.php";

if (!session_id()) {
	ini_set("session.cookie_lifetime", 24*60*60);
	ini_set("session.gc_maxlifetime", 24*60*60);
	ini_set("session.name", "catalyst");
	session_start();
}
