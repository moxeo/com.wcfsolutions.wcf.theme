<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a theme.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme
 * @category	Community Framework
 */
class Theme extends DatabaseObject {
	/**
	 * list of all themes
	 *
	 * @var	array<Theme>
	 */
	protected static $themes = null;

	/**
	 * Creates a new Theme object.
	 *
	 * @param	integer		$themeID
	 * @param	array		$row
	 * @param 	Theme		$cacheObject
	 */
	public function __construct($themeID, $row = null, $cacheObject = null) {
		if ($themeID !== null) $cacheObject = self::getTheme($themeID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}

	/**
	 * Returns the title of this theme.
	 *
	 * @return	string
	 */
	public function __toString() {
		return $this->themeName;
	}

	/**
	 * Returns a list of all themes.
	 *
	 * @return 	array<Theme>
	 */
	public static function getThemes() {
		if (self::$themes == null) {
			self::$themes = WCF::getCache()->get('theme-'.PACKAGE_ID, 'themes');
		}

		return self::$themes;
	}

	/**
	 * Returns the theme with the given theme id from cache.
	 *
	 * @param 	integer		$themeID
	 * @return	Theme
	 */
	public static function getTheme($themeID) {
		$themes = self::getThemes();

		if (!isset($themes[$themeID])) {
			throw new IllegalLinkException();
		}

		return $themes[$themeID];
	}
}
?>