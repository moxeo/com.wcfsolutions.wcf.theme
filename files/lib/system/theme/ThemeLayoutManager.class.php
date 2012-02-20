<?php
// wcf imports
require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayout.class.php');

/**
 * Manages the active theme layouts.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	system.theme
 * @category	Community Framework
 */
class ThemeLayoutManager {
	/**
	 * active theme layout object
	 *
	 * @var	ThemeLayout
	 */
	protected static $themeLayout = null;

	/**
	 * Changes the active theme layout.
	 *
	 * @param	integer		$themeLayoutID
	 */
	public static final function changeThemeLayout($themeLayoutID = 0) {
		if (self::$themeLayout !== null && self::$themeLayout->themeLayoutID == $themeLayoutID) return;

		// get cache
		$themeLayouts = WCF::getCache()->get('themeLayout-'.PACKAGE_ID, 'layouts');

		// fallback to default themeLayout
		if (!isset($themeLayouts[$themeLayoutID])) {
			// get default themeLayout id
			$defaultThemeLayoutID = WCF::getCache()->get('themeLayout-'.PACKAGE_ID, 'default');
			if ($defaultThemeLayoutID != 0) {
				$themeLayoutID = $defaultThemeLayoutID;
			}

			// no default themeLayout
			if (!isset($themeLayouts[$themeLayoutID])) {
				throw new SystemException('no default theme layout defined', 100000);
			}
		}

		// set themeLayout
		self::setThemeLayout($themeLayouts[$themeLayoutID]);
	}

	/**
	 * Sets the active theme layout directly.
	 *
	 * @param	ThemeLayout		$themeLayout
	 */
	public static function setThemeLayout(ThemeLayout $themeLayout) {
		self::$themeLayout = $themeLayout;
	}

	/**
	 * Returns the active theme layout.
	 *
	 * @return	ThemeLayout
	 */
	public static function getThemeLayout() {
		return self::$themeLayout;
	}
}
?>