<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ThemeAddForm.class.php');

/**
 * Shows the theme edit form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeEditForm extends ThemeAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.theme';
	public $neededPermissions = 'admin.theme.canEditTheme';

	/**
	 * theme id
	 *
	 * @var	integer
	 */
	public $themeID = 0;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		$this->theme = new ThemeEditor($this->themeID);
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		if (!count($_POST)) {
			// get values
			$this->themeName = $this->theme->themeName;
			$this->templatePackID = $this->theme->templatePackID;
			$this->themeDescription = $this->theme->themeDescription;
			$this->themeVersion = $this->theme->themeVersion;
			$this->themeDate = $this->theme->themeDate;
			$this->dataLocation = $this->theme->dataLocation;
			$this->copyright = $this->theme->copyright;
			$this->license = $this->theme->license;
			$this->authorName = $this->theme->authorName;
			$this->authorURL = $this->theme->authorURL;
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// update theme
		$this->theme->update($this->themeName, $this->templatePackID, $this->themeDescription, $this->themeVersion, $this->themeDate, $this->dataLocation, $this->copyright, $this->license, $this->authorName, $this->authorURL);

		// reset cache
		WCF::getCache()->clearResource('theme-'.PACKAGE_ID);
		$this->saved();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'themeID' => $this->themeID,
			'theme' => $this->theme
		));
	}
}
?>