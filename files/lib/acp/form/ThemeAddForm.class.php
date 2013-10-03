<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
require_once(WCF_DIR.'lib/data/template/TemplatePackEditor.class.php');

/**
 * Shows the theme add form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeAddForm extends ACPForm {
	// system
	public $templateName = 'themeAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.add';
	public $neededPermissions = 'admin.theme.canAddTheme';

	/**
	 * theme editor object
	 *
	 * @var	ThemeEditor
	 */
	public $theme = null;

	/**
	 * list of template packs
	 *
	 * @var	array<TemplatePack>
	 */
	public $templatePacks = array();

	// parameters
	public $themeName = '';
	public $templatePackID = 0;
	public $themeDescription = '';
	public $themeVersion = '';
	public $themeDate = '0000-00-00';
	public $fileLocation = '';
	public $copyright = '';
	public $license = '';
	public $authorName = '';
	public $authorURL = '';

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get theme id
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->templatePacks = TemplatePackEditor::getTemplatePacks();
	}

	/**
	 * @see	Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['themeName'])) $this->themeName = StringUtil::trim($_POST['themeName']);
		if (isset($_POST['templatePackID'])) $this->templatePackID = intval($_POST['templatePackID']);
		if (isset($_POST['themeDescription'])) $this->themeDescription = StringUtil::trim($_POST['themeDescription']);
		if (isset($_POST['themeVersion'])) $this->themeVersion = StringUtil::trim($_POST['themeVersion']);
		if (isset($_POST['themeDate'])) $this->themeDate = StringUtil::trim($_POST['themeDate']);
		if (isset($_POST['fileLocation'])) $this->fileLocation = StringUtil::trim($_POST['fileLocation']);
		if (isset($_POST['copyright'])) $this->copyright = StringUtil::trim($_POST['copyright']);
		if (isset($_POST['license'])) $this->license = StringUtil::trim($_POST['license']);
		if (isset($_POST['authorName'])) $this->authorName = StringUtil::trim($_POST['authorName']);
		if (isset($_POST['authorURL'])) $this->authorURL = StringUtil::trim($_POST['authorURL']);
	}

	/**
	 * @see	Form::validate()
	 */
	public function validate() {
		parent::validate();

		// theme name
		if (empty($this->themeName)) {
			throw new UserInputException('themeName');
		}

		// template pack
		if ($this->templatePackID) {
			$templatePack = new TemplatePackEditor($this->templatePackID);
			if (!$templatePack->templatePackID) {
				throw new UserInputException('templatePackID');
			}
		}

		// data location
		$this->fileLocation = ThemeEditor::getFilename($this->fileLocation);
		if (empty($this->fileLocation)) {
			throw new UserInputException('fileLocation');
		}
	}

	/**
	 * @see	Form::save()
	 */
	public function save() {
		parent::save();

		// save theme
		$this->theme = ThemeEditor::create($this->themeName, $this->templatePackID, $this->themeDescription, $this->themeVersion, $this->themeDate, $this->fileLocation, $this->copyright, $this->license, $this->authorName, $this->authorURL);

		// reset cache
		WCF::getCache()->clearResource('theme-'.PACKAGE_ID);
		$this->saved();

		// reset values
		$this->themeID = 0;
		$this->themeName = $this->themeDescription = $this->themeVersion = $this->fileLocation = $this->copyright = $this->license = $this->authorName = $this->authorURL = '';
		$this->themeDate = '0000-00-00';

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
			'templatePacks' => $this->templatePacks,
			'templatePackID' => $this->templatePackID,
			'themeName' => $this->themeName,
			'themeDescription' => $this->themeDescription,
			'themeVersion' => $this->themeVersion,
			'themeDate' => $this->themeDate,
			'fileLocation' => $this->fileLocation,
			'copyright' => $this->copyright,
			'license' => $this->license,
			'authorName' => $this->authorName,
			'authorURL' => $this->authorURL
		));
	}
}
?>