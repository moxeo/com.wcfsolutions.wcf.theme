<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/theme/Theme.class.php');

/**
 * Represents a theme module.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module
 * @category	Community Framework
 */
class ThemeModule extends DatabaseObject {
	/**
	 * list of theme module types
	 *
	 * @var	array
	 */
	public static $themeModuleTypes = null;

	/**
	 * list of available theme module types
	 *
	 * @var	array<ThemeModuleType>
	 */
	public static $availableThemeModuleTypes = null;

	/**
	 * list of theme modules
	 *
	 * @var	array<ThemeModule>
	 */
	protected static $themeModules = null;

	/**
	 * list of theme module options
	 *
	 * @var	array
	 */
	protected $themeModuleOptions = null;

	/**
	 * Creates a new ThemeModule object.
	 *
	 * @param 	integer		$themeModuleID
	 * @param 	array		$row
	 * @param 	ThemeModule	$cacheObject
	 */
	public function __construct($themeModuleID, $row = null, $cacheObject = null) {
		if ($themeModuleID !== null) $cacheObject = self::getThemeModule($themeModuleID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}

	/**
	 * Returns the title of this theme module.
	 *
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}

	/**
	 * Returns the theme module type of this theme module.
	 *
	 * @return	ThemeModule
	 */
	public function getThemeModuleType() {
		return self::getThemeModuleTypeObject($this->themeModuleType);
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
	 * Returns the value of the theme module option with the given name.
	 *
	 * @param	string		$name
	 * @return	mixed
	 */
	public function getThemeModuleOption($name) {
		if ($this->themeModuleOptions === null) {
			$this->themeModuleOptions = array();
			if ($this->data['themeModuleData']) {
				$this->themeModuleOptions = unserialize($this->data['themeModuleData']);
			}
		}

		if (isset($this->themeModuleOptions[$name])) {
			return $this->themeModuleOptions[$name];
		}

		return null;
	}

	/**
	 * @see	DatabaseObject::__get()
	 */
	public function __get($name) {
		$value = parent::__get($name);
		if ($value === null) $value = $this->getThemeModuleOption($name);
		return $value;
	}

	/**
	 * Returns the object of a theme module type.
	 *
	 * @param	string		$themeModuleType
	 * @return	ThemeModuleType
	 */
	public static function getThemeModuleTypeObject($themeModuleType) {
		$types = self::getAvailableThemeModuleTypes();
		if (!isset($types[$themeModuleType])) {
			throw new SystemException("Unknown theme module type '".$themeModuleType."'", 11000);
		}
		return $types[$themeModuleType];
	}

	/**
	 * Returns a list of theme module types.
	 *
	 * @return	array
	 */
	public static function getThemeModuleTypes() {
		if (self::$themeModuleTypes === null) {
			WCF::getCache()->addResource('themeModuleTypes-'.PACKAGE_ID, WCF_DIR.'cache/cache.themeModuleTypes-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderThemeModuleTypes.class.php');
			self::$themeModuleTypes = WCF::getCache()->get('themeModuleTypes-'.PACKAGE_ID);
		}

		return self::$themeModuleTypes;
	}

	/**
	 * Returns a list of available theme module types.
	 *
	 * @return	array<ThemeModuleType>
	 */
	public static function getAvailableThemeModuleTypes() {
		if (self::$availableThemeModuleTypes === null) {
			$types = self::getThemeModuleTypes();
			foreach ($types as $type) {
				// get path to class file
				if (empty($type['packageDir'])) {
					$path = WCF_DIR;
				}
				else {
					$path = FileUtil::getRealPath(WCF_DIR.$type['packageDir']);
				}
				$path .= $type['classFile'];

				// include class file
				if (!class_exists($type['className'])) {
					if (!file_exists($path)) {
						throw new SystemException("Unable to find class file '".$path."'", 11000);
					}
					require_once($path);
				}

				// instance object
				if (!class_exists($type['className'])) {
					throw new SystemException("Unable to find class '".$type['className']."'", 11001);
				}
				self::$availableThemeModuleTypes[$type['themeModuleType']] = new $type['className'];
			}
		}
		return self::$availableThemeModuleTypes;
	}

	/**
	 * Returns the theme module type options.
	 *
	 * @return	array
	 */
	public static function getThemeModuleTypeOptions() {
		$options = array();

		$types = self::getThemeModuleTypes();
		foreach ($types as $type) {
			$category = WCF::getLanguage()->get('wcf.theme.module.type.category.'.$type['category']);

			if (!isset($options[$category])) {
				$options[$category] = array();
			}

			$options[$category][$type['themeModuleType']] = WCF::getLanguage()->get('wcf.theme.module.type.'.$type['themeModuleType']);
		}

		return $options;
	}

	/**
	 * Returns the theme module options.
	 *
	 * @param	integer		$themeID
	 * @param	array		$hiddenThemeModuleTypes
	 * @return	array
	 */
	public static function getThemeModuleOptions($themeID = 0, $hiddenThemeModuleTypes = array()) {
		$themeModules = self::getThemeModules();
		$themeModuleOptions = array();
		foreach ($themeModules as $themeModuleID => $themeModule) {
			if (in_array($themeModule->themeModuleType, $hiddenThemeModuleTypes)) continue;

			if ($themeID != 0) {
				if ($themeModule->themeID != $themeID) continue;
				$themeModuleOptions[$themeModuleID] = $themeModule->title;
			}
			else {
				$themeName = $themeModule->getTheme()->themeName;
				if (!isset($themeModuleOptions[$themeName])) {
					$themeModuleOptions[$themeName] = array();
				}
				$themeModuleOptions[$themeName][$themeModuleID] = $themeModule;
			}
		}
		return $themeModuleOptions;
	}

	/**
	 * Returns a list of all theme modules.
	 *
	 * @return 	array<ThemeModule>
	 */
	public static function getThemeModules() {
		if (self::$themeModules == null) {
			self::$themeModules = WCF::getCache()->get('themeModule-'.PACKAGE_ID);
		}

		return self::$themeModules;
	}

	/**
	 * Returns the theme module with the given theme module id from cache.
	 *
	 * @param 	integer		$themeModuleID
	 * @return	ThemeModule
	 */
	public static function getThemeModule($themeModuleID) {
		$themeModules = self::getThemeModules();

		if (!isset($themeModules[$themeModuleID])) {
			throw new IllegalLinkException();
		}

		return $themeModules[$themeModuleID];
	}
}
?>