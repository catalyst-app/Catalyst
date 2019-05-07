<?php

namespace Catalyst\Page;

use \Catalyst\Controller;
use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\{MultipleOrderByClause, OrderByClause, WhereClause};
use \DateTime;
use \LogicException;

/**
 * Used to hold data about what scripts and other resources are used
 * @method string getEnvironment()
 * @method void setEnvironment(string $environment)
 * @method string getType()
 * @method void setType(string $type)
 * @method int getPriority()
 * @method void setPriority(int $priority)
 * @method bool isLocal()
 * @method void setLocal(bool $local)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getMinifiedName()
 * @method void setMinifiedName(string $minifiedName)
 * @method string getStagedName()
 * @method void setStagedName(string $stagedName)
 * @method string getStagedMinifiedName()
 * @method void setStagedMinifiedName(string $stagedMinifiedName)
 * @method bool isVersioned()
 * @method void setVersioned(bool $versioned)
 * @method DateTime getDateOfLastRelease()
 * @method void setDateOfLastRelease(DateTime $dateOfLastRelease)
 * @method string getGithubRepoName()
 * @method void setGithubRepoName(string $githubRepoName)
 * @method string getChangelogUrl()
 * @method void setChangelogUrl(string $changelogUrl)
 * @method string getCurrentVersion()
 * @method void setCurrentVersion(string $currentVersion)
 * @method string getLatestVersion()
 * @method void setLatestVersion(string $latestVersion)
 * @method array getAttributes()
 * @method void setAttributes(array $attributes)
 */
class Resource extends AbstractDatabaseModel {
	/**
	 * Get the table the object's data is stored in
	 * 
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::RESOURCES;
	}

	/**
	 * Create an object of this type
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		throw new LogicException("Resources should be created directly without this abstraction.");
	}

	/**
	 * Get deleted values for when is delet
	 * @return array
	 */
	public function getDeletedValues() : array {
		return [];
	}

	/**
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"ENVIRONMENT",
			"TYPE",
			"PRIORITY",
			"LOCAL",
			"NAME",
			"MINIFIED_NAME",
			"ATTRIBUTES",
		];
	}

	/**
	 * Get modifiable properties for the model
	 *
	 * 	"Name" => ["COLUMN_NAME", function($value) {return $out;}, function($newValue) {return $out;}]
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"Environment" => ["ENVIRONMENT", null, null],
			"Type" => ["TYPE", null, null],
			"Priority" => ["PRIORITY", null, null],
			"Local" => ["LOCAL", null, null],
			"Name" => ["NAME", null, null],
			"MinifiedName" => ["MINIFIED_NAME", null, null],
			"StagedName" => ["STAGED_NAME", null, null],
			"StagedMinifiedName" => ["STAGED_MINIFIED_NAME", null, null],
			"Versioned" => ["VERSIONED", null, null],
			"DateOfLastRelease" => ["DATE_OF_LATEST_RELEASE", "date_create", function(DateTime $in) : string { return $in->format("Y-m-d"); }],
			"GithubRepoName" => ["GITHUB_REPO_NAME", null, null],
			"ChangelogUrl" => ["CHANGELOG_URL", null, null],
			"CurrentVersion" => ["CURRENT_VERSION", null, null],
			"LatestVersion" => ["LATEST_VERSION", null, null],
			"Attributes" => ["ATTRIBUTES", "json_decode", "json_encode"],
		];
	}

	/**
	 * Get an array of all
	 * 
	 * @return self[]
	 */
	public static function getAll() : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		foreach (self::getPrefetchColumns() as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
		}

		$stmt->addAdditionalCapability(new OrderByClause(new Column("PRIORITY", self::getTable()), "ASC"));

		$stmt->execute();

		$resources = [];

		foreach ($stmt->getResult() as $resource) {
			$resources[] = new self($resource["ID"], $resource, false);
		}

		return $resources;
	}

	/**
	 * Get an array of all resources for the current environment
	 * 
	 * @param bool $forceDevel to force devel
	 * @return self[]
	 */
	public static function getAllForEnvironment(bool $forceDevel=false) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		foreach (self::getPrefetchColumns() as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
		}

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ENVIRONMENT", self::getTable()), "!=", (Controller::isDevelMode() | $forceDevel) ? "PRODUCTION" : "DEVEL"]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->addAdditionalCapability(new OrderByClause(new Column("PRIORITY", self::getTable()), "ASC"));

		$stmt->execute();

		$resources = [];

		foreach ($stmt->getResult() as $resource) {
			$resources[] = new self($resource["ID"], $resource, false);
		}

		return $resources;
	}

	/**
	 * Get the scripts and attributes
	 * @return self[]
	 */
	public static function getScripts() : array {
		return array_filter(self::getAllForEnvironment(), function(self $in) : bool {
			return $in->isScript();
		});
	}

	/**
	 * Get the styles
	 * @return self[]
	 */
	public static function getStyles() : array {
		return array_filter(self::getAllForEnvironment(), function(self $in) : bool {
			return $in->isStyle();
		});
	}

	/**
	 * Return if the resource is a script
	 * @return bool
	 */
	public function isScript() : bool {
		return $this->getType() == "SCRIPT";
	}

	/**
	 * Return if the resource is a style
	 * @return bool
	 */
	public function isStyle() : bool {
		return $this->getType() == "STYLE";
	}

	/**
	 * Get the minified or non-minified name, based on the environment.
	 * @return string
	 */
	protected function getRelevantName() : string {
		return Controller::isDevelMode() ? $this->getName() : $this->getMinifiedName();
	}

	/**
	 * Returns true if the resource is on another domain
	 * @return bool
	 */
	public function isExternalOriginResource() : bool {
		return strpos($this->getRelevantName(), "http") === 0;
	}

	/**
	 * Get the full path to the resource from the browser's standpoint
	 * @return string
	 */
	protected function getResolvedPath() : string {
		$path = $this->getRelevantName();

		if (!$this->isExternalOriginResource()) {
			if ($this->isScript()) {
				if ($this->isLocal()) {
					$path = ROOTDIR."js/".$path;
				} else {
					$path = ROOTDIR."js/external/".$path;
				}
			} else {
				if ($this->isLocal()) {
					$path = ROOTDIR."css/".$path;
					$path = str_replace('{color}', PAGE_COLOR, $path);
				} else {
					$path = ROOTDIR."css/external/".$path;
				}
			}
		}

		return $path;
	}

	/**
	 * Get the HTML tag for the inclusion of this resource
	 * @return string
	 */
	public function getTag() : string {
		$path = $this->getResolvedPath();

		$path .= "?commit=".Controller::getCommit();
		if (Controller::isDevelMode()) {
			$path .= microtime(true);
		}

		$attributes = $this->getAttributes();
		if (Controller::isDevelMode()) {
			$attributes["data-environment"] = $this->getEnvironment();
			$attributes["data-type"] = $this->getType();
			$attributes["data-priority"] = (string)$this->getPriority();
			$attributes["data-local"] = $this->isLocal() ? "true" : "false";
		}

		if ($this->isScript()) {
			return self::buildScriptTag($path, $attributes);
		} else {
			return self::buildStyleTag($path, $attributes);
		}
	}

	/**
	 * Build a script tag to embed JavaScript
	 * @param string $src The path to point to
	 * @param string[] $attributes Attributes to attach to the element
	 * @return string
	 */
	protected static function buildScriptTag(string $src, array $attributes) {
		$tag = '<script';

		$attributes["src"] = $src;
		
		foreach ($attributes as $key => $value) {
			// allows defer="defer" to work with ["defer"]
			if (is_numeric($key)) {
				$tag .= ' '.htmlspecialchars($value);
			} else {
				$tag .= ' '.htmlspecialchars($key);
			}
			$tag .= '="'.htmlspecialchars($value).'"';
		}

		return $tag."></script>";
	}

	/**
	 * Build a script tag to embed CSS
	 * @param string $href The stylesheet to point to
	 * @param string[] $attributes Attributes to attach to the element
	 * @return string
	 */
	protected static function buildStyleTag(string $href, array $attributes) {
		$tag = '<link';

		$attributes["href"] = $href;
		$attributes["rel"] = "stylesheet";
		
		foreach ($attributes as $key => $value) {
			// allows defer="defer" to work with ["defer"]
			if (is_numeric($key)) {
				$tag .= ' '.htmlspecialchars($value);
			} else {
				$tag .= ' '.htmlspecialchars($key);
			}
			$tag .= '="'.htmlspecialchars($value).'"';
		}

		return $tag." />";
	}

	/**
	 * Send Link headers with page resources that may be helpful, especially for HTTP/2
	 */
	public static function pushPageResources() : void {
		return;
		if (!headers_sent() &&
			!(array_key_exists("SCRIPT_FILENAME", $_SERVER) && strpos(strrev($_SERVER["SCRIPT_FILENAME"]), strrev(".js")) === 0) &&
			!(array_key_exists("SCRIPT_FILENAME", $_SERVER) && strpos(strrev($_SERVER["SCRIPT_FILENAME"]), strrev(".css")) === 0)) {
			$preconnects = []; // domains to preconnect to

			$preconnects[] = "https://fonts.googleapis.com";
			$preconnects[] = "https://fonts.gstatic.com";
			$preconnects[] = "https://www.gstatic.com";
			$preconnects[] = "https://google.com";

			// the script itself is ignored if DNT is set but we can avoid preconnecting too
			if (!isset($_SERVER['HTTP_DNT']) || $_SERVER['HTTP_DNT'] != 1) {
				$preconnects[] = "https://googletagmanager.com";
				$preconnects[] = "https://www.google-analytics.com";
			}

			foreach ($preconnects as $domain) {
				header("Link: <".$domain.">; rel=preconnect", false);
			}

			foreach (self::getStyles() as $style) {
				header("Link: <".$style->getResolvedPath().">; rel=preload; as=style; type=text/css", false);
			}

			foreach (self::getScripts() as $script) {
				header("Link: <".$script->getResolvedPath().">; rel=preload; as=script; type=application/javascript", false);
			}
		}
	}
}
