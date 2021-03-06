<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');
require_once(WCF_DIR.'lib/data/theme/module/ThemeModule.class.php');

/**
 * Represents a theme layout.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.layout
 * @category	Community Framework
 */
class ThemeLayout extends DatabaseObject {
	/**
	 * list of themes
	 *
	 * @var	array<Theme>
	 */
	protected static $themes = null;

	/**
	 * list of theme layouts
	 *
	 * @var	array<ThemeLayout>
	 */
	protected static $themeLayouts = null;

	/**
	 * list of theme modules
	 *
	 * @var	array<ThemeModule>
	 */
	protected static $themeModules = null;

	/**
	 * list of theme modules matched to layouts
	 *
	 * @var	array
	 */
	protected static $themeModuleToLayouts = null;

	/**
	 * list of theme module positions
	 *
	 * @var	array
	 */
	private static $themeModulePositions = array('header', 'left', 'main', 'right', 'footer');

	/**
	 * Creates a new ThemeLayout object.
	 *
	 * @param 	integer		$themeLayoutID
	 * @param 	array		$row
	 * @param 	ThemeLayout	$cacheObject
	 */
	public function __construct($themeLayoutID, $row = null, $cacheObject = null) {
		if ($themeLayoutID !== null) $cacheObject = self::getThemeLayout($themeLayoutID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}

	/**
	 * Returns the title of this theme layout.
	 *
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}

	/**
	 * Returns the theme object of this theme layout.
	 *
	 * @return	Theme
	 */
	public function getTheme() {
		return Theme::getTheme($this->themeID);
	}

	/**
	 * Caches all modules of this theme layout.
	 *
	 * @param	array		$additionalData
	 */
	public function cacheModules($additionalData = array()) {
		if (self::$themeModules === null) self::$themeModules = WCF::getCache()->get('themeModule-'.PACKAGE_ID);
		if (self::$themeModuleToLayouts === null) self::$themeModuleToLayouts = WCF::getCache()->get('themeLayout-'.PACKAGE_ID, 'modules');

		foreach (self::$themeModulePositions as $themeModulePosition) {
			if (isset(self::$themeModuleToLayouts[$this->themeLayoutID][$themeModulePosition])) {
				foreach (self::$themeModuleToLayouts[$this->themeLayoutID][$themeModulePosition] as $themeModuleData) {
					$themeModuleID = $themeModuleData['themeModuleID'];
					$themeModule = self::$themeModules[$themeModuleID];

					// cache module
					$themeModule->getThemeModuleType()->cache($themeModule, $themeModulePosition, $additionalData);
				}
			}
		}
	}

	/**
	 * Returns all modules of the given theme module position.
	 *
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 * @return	array<ThemeModule>
	 */
	public function getModules($themeModulePosition, $additionalData = array()) {
		if (self::$themeModules === null) self::$themeModules = WCF::getCache()->get('themeModule-' . PACKAGE_ID);
		if (self::$themeModuleToLayouts === null) self::$themeModuleToLayouts = WCF::getCache()->get('themeLayout-' . PACKAGE_ID, 'modules');

		$themeModules = array();
		if (isset(self::$themeModuleToLayouts[$this->themeLayoutID][$themeModulePosition])) {
			foreach (self::$themeModuleToLayouts[$this->themeLayoutID][$themeModulePosition] as $themeModuleData) {
				$themeModuleID = $themeModuleData['themeModuleID'];
				$themeModule = self::$themeModules[$themeModuleID];
				if ($themeModule->getThemeModuleType()->hasContent($themeModule, $themeModulePosition, $additionalData)) {
					$themeModules[] = $themeModule;
				}
			}
		}

		return $themeModules;
	}

	/**
	 * Returns the theme layout options.
	 *
	 * @return	array
	 */
	public static function getThemeLayoutOptions() {
		$themeLayouts = self::getThemeLayouts();
		$themeLayoutOptions = array();

		foreach ($themeLayouts as $themeLayout) {
			$themeName = $themeLayout->getTheme()->themeName;
			if (!isset($themeLayoutOptions[$themeName])) {
				$themeLayoutOptions[$themeName] = array();
			}
			$themeLayoutOptions[$themeName][$themeLayout->themeLayoutID] = $themeLayout->title;
		}

		return $themeLayoutOptions;
	}

	/**
	 * Returns a list of all theme layouts.
	 *
	 * @return 	array<ThemeLayout>
	 */
	public static function getThemeLayouts() {
		if (self::$themeLayouts == null) {
			self::$themeLayouts = WCF::getCache()->get('themeLayout-'.PACKAGE_ID, 'layouts');
		}

		return self::$themeLayouts;
	}

	/**
	 * Returns the theme layout with the given theme layout id from cache.
	 *
	 * @param 	integer		$themeLayoutID
	 * @return	ThemeLayout
	 */
	public static function getThemeLayout($themeLayoutID) {
		$themeLayouts = self::getThemeLayouts();

		if (!isset($themeLayouts[$themeLayoutID])) {
			throw new IllegalLinkException();
		}

		return $themeLayouts[$themeLayoutID];
	}

	/**
	 * Returns all available positions in which theme modules can be placed.
	 *
	 * @return	array		All available positions in which theme modules can be placed.
	 */
	public static function getThemeModulePositions() {
		return self::$themeModulePositions;
	}

	/**
	 * Sets all available positions in which theme modules can be placed.
	 *
	 * @param        array		$themeModulePositions	All available positions in which theme modules can be
	 *                                           		placed.
	 */
	public static function setThemeModulePositions($themeModulePositions) {
		self::$themeModulePositions = $themeModulePositions;
	}
}
?>