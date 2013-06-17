<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetEditor.class.php');

/**
 * Shows the theme stylesheet add form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeStylesheetAddForm extends ACPForm {
	// system
	public $templateName = 'themeStylesheetAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.stylesheet.add';
	public $neededPermissions = 'admin.theme.canAddThemeStylesheet';

	/**
	 * theme id
	 *
	 * @var	integer
	 */
	public $themeID = 0;

	/**
	 * theme editor object
	 *
	 * @var	ThemeEditor
	 */
	public $theme = null;

	/**
	 * theme layout editor object
	 *
	 * @var	ThemeLayoutEditor
	 */
	public $themeLayout = null;

	// parameters
	public $title = '';
	public $lessCode = '';

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		if ($this->themeID) {
			$this->theme = new Theme($this->themeID);
		}
	}

	/**
	 * @see	Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['lessCode'])) $this->lessCode = $_POST['lessCode'];
	}

	/**
	 * @see	Form::validate()
	 */
	public function validate() {
		parent::validate();

		// title
		if (empty($this->title)) {
			throw new UserInputException('title');
		}

		// less code
		if (empty($this->lessCode)) {
			throw new UserInputException('lessCode');
		}

		// test less code
		require_once(WCF_DIR.'lib/system/theme/3rdParty/lessc.inc.php');
		$compiler = new lessc();
		$compiler->setImportDir(array(WCF_DIR));

		try {
			$compiler->compile($this->lessCode);
		}
		catch (Exception $e) {
			throw new UserInputException('lessCode', 'syntaxError');
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		parent::save();

		// save theme stylesheet
		$this->themeStylesheet = ThemeStylesheetEditor::create($this->themeID, $this->title, $this->lessCode);
		$this->saved();

		// reset values
		$this->themeID = 0;
		$this->title = $this->lessCode = '';

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'add',
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'title' => $this->title,
			'lessCode' => $this->lessCode,
			'themeOptions' => Theme::getThemes()
		));
	}
}
?>