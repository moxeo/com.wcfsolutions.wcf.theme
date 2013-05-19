<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ThemeLayoutAddForm.class.php');

/**
 * Shows the theme layout edit form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeLayoutEditForm extends ThemeLayoutAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.theme.layout';
	public $neededPermissions = 'admin.theme.canEditThemeLayout';

	/**
	 * theme layout id
	 *
	 * @var	integer
	 */
	public $themeLayoutID = 0;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		ACPForm::readParameters();

		// get theme layout
		if (isset($_REQUEST['themeLayoutID'])) $this->themeLayoutID = intval($_REQUEST['themeLayoutID']);
		$this->themeLayout = new ThemeLayoutEditor($this->themeLayoutID);

		// get theme
		$this->theme = new Theme($this->themeLayout->themeID);

		// get theme stylesheet options
		$this->themeStylesheetOptions = ThemeStylesheet::getThemeStylesheetOptions($this->theme->themeID);
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		if (!count($_POST)) {
			// get values
			$this->title = $this->themeLayout->title;
			$this->themeStylesheetIDs = $this->themeLayout->getAssignedThemeStylesheets();
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// update theme layout
		$this->themeLayout->update($this->title, $this->themeStylesheetIDs);

		// reset cache
		WCF::getCache()->clearResource('themeLayout-'.PACKAGE_ID);
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
			'themeLayoutID' => $this->themeLayoutID,
			'themeLayout' => $this->themeLayout
		));
	}
}
?>