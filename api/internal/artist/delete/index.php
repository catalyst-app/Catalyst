<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, SelectQuery, UpdateQuery};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Tokens;

Endpoint::init(true, 1);

FormRepository::getDeleteArtistPageForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91301, ErrorCodes::ERR_91301);
}

$artistId = $_SESSION["user"]->getArtistPageId();

$stmt = new UpdateQuery();
$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("URL", Tables::ARTIST_PAGES));
$stmt->addValue(Tokens::generateDeletedArtistUrl($_SESSION["user"]->getArtistPage()->getUrl()));

$stmt->addColumn(new Column("DELETED", Tables::ARTIST_PAGES));
$stmt->addValue(1);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::ARTIST_PAGES), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$stmt = new UpdateQuery();
$stmt->setTable(Tables::USERS);
$stmt->addColumn(new Column("ARTIST_PAGE_ID", Tables::USERS));
$stmt->addValue(null);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);
$stmt->execute();

$removeArtistSocialMediaQuery = new DeleteQuery();
$removeArtistSocialMediaQuery->setTable(Tables::ARTIST_SOCIAL_MEDIA);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_ID", Tables::ARTIST_SOCIAL_MEDIA), "=", $artistId]);
$removeArtistSocialMediaQuery->addAdditionalCapability($whereClause);
$removeArtistSocialMediaQuery->execute();

$removeArtistStreamingIntegrationsQuery = new DeleteQuery();
$removeArtistStreamingIntegrationsQuery->setTable(Tables::ARTIST_STREAMING_INTEGRATIONS);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_ID", Tables::ARTIST_STREAMING_INTEGRATIONS), "=", $artistId]);
$removeArtistStreamingIntegrationsQuery->addAdditionalCapability($whereClause);
$removeArtistStreamingIntegrationsQuery->execute();

$deleteCommissionTypeImages = new DeleteQuery();
$deleteCommissionTypeImages->setTable(Tables::COMMISSION_TYPE_IMAGES);
$joinClause = new JoinClause();
$joinClause->setType(JoinClause::INNER);
$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
$joinClause->setLeftColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_IMAGES));
$joinClause->setRightColumn(new Column("ID", Tables::COMMISSION_TYPES));
$deleteCommissionTypeImages->addAdditionalCapability($joinClause);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $artistId]);
$deleteCommissionTypeImages->addAdditionalCapability($whereClause);
$deleteCommissionTypeImages->execute();

$deleteCommissionTypeModifiers = new UpdateQuery();
$deleteCommissionTypeModifiers->setTable(Tables::COMMISSION_TYPE_MODIFIERS);
$deleteCommissionTypeModifiers->addColumn(new Column("DELETED", Tables::COMMISSION_TYPE_MODIFIERS));
$deleteCommissionTypeModifiers->addValue(true);
$joinClause = new JoinClause();
$joinClause->setType(JoinClause::INNER);
$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
$joinClause->setLeftColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_MODIFIERS));
$joinClause->setRightColumn(new Column("ID", Tables::COMMISSION_TYPES));
$deleteCommissionTypeModifiers->addAdditionalCapability($joinClause);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $artistId]);
$deleteCommissionTypeModifiers->addAdditionalCapability($whereClause);
$deleteCommissionTypeModifiers->execute();

$deleteCommissionTypePaymentOptions = new UpdateQuery();
$deleteCommissionTypePaymentOptions->setTable(Tables::COMMISSION_TYPE_PAYMENT_OPTIONS);
$deleteCommissionTypePaymentOptions->addColumn(new Column("DELETED", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
$deleteCommissionTypePaymentOptions->addValue(true);
$joinClause = new JoinClause();
$joinClause->setType(JoinClause::INNER);
$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
$joinClause->setLeftColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
$joinClause->setRightColumn(new Column("ID", Tables::COMMISSION_TYPES));
$deleteCommissionTypePaymentOptions->addAdditionalCapability($joinClause);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $artistId]);
$deleteCommissionTypePaymentOptions->addAdditionalCapability($whereClause);
$deleteCommissionTypePaymentOptions->execute();

$deleteCommissionTypeStages = new UpdateQuery();
$deleteCommissionTypeStages->setTable(Tables::COMMISSION_TYPE_STAGES);
$deleteCommissionTypeStages->addColumn(new Column("DELETED", Tables::COMMISSION_TYPE_STAGES));
$deleteCommissionTypeStages->addValue(true);
$joinClause = new JoinClause();
$joinClause->setType(JoinClause::INNER);
$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
$joinClause->setLeftColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_STAGES));
$joinClause->setRightColumn(new Column("ID", Tables::COMMISSION_TYPES));
$deleteCommissionTypeStages->addAdditionalCapability($joinClause);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $artistId]);
$deleteCommissionTypeStages->addAdditionalCapability($whereClause);
$deleteCommissionTypeStages->execute();

$deleteCommissionTypes = new UpdateQuery();
$deleteCommissionTypes->setTable(Tables::COMMISSION_TYPES);
$deleteCommissionTypes->addColumn(new Column("DELETED", Tables::COMMISSION_TYPES));
$deleteCommissionTypes->addValue(true);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $artistId]);
$deleteCommissionTypes->addAdditionalCapability($whereClause);
$deleteCommissionTypes->execute();

Response::sendSuccessResponse("Success");
