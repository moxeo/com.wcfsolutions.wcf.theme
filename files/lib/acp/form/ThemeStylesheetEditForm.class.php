<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ThemeStylesheetAddForm.class.php');

/**
 * Shows the theme stylesheet edit form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeStylesheetEditForm extends ThemeStylesheetAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.theme.stylesheet';
	public $neededPermissions = 'admin.theme.canEditThemeStylesheet';

	/**
	 * theme stylesheet id
	 *
	 * @var	integer
	 */
	public $themeStylesheetID = 0;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		ACPForm::readParameters();

		// get theme stylesheet
		if (isset($_REQUEST['themeStylesheetID'])) $this->themeStylesheetID = intval($_REQUEST['themeStylesheetID']);
		$this->themeStylesheet = new ThemeStylesheetEditor($this->themeStylesheetID);

		// get theme
		$this->theme = new Theme($this->themeStylesheet->themeID);
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		if (!count($_POST)) {
			// get values
			$this->title = $this->themeStylesheet->title;
			$this->lessCode = $this->themeStylesheet->lessCode;
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// update theme stylesheet
		$this->themeStylesheet->update($this->title, $this->lessCode);

		// recompile affected theme layout stylesheets
		$this->themeStylesheet->recompileAffectedThemeLayoutStylesheets();
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
			'themeStylesheetID' => $this->themeStylesheetID,
			'themeStylesheet' => $this->themeStylesheet
		));
	}
}
?>