<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');

/**
 * Shows the theme import form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeImportForm extends ACPForm {
	// system
	public $templateName = 'themeImport';
	public $activeMenuItem = 'wcf.acp.menu.link.theme.import';
	public $neededPermissions = 'admin.theme.canImportTheme';
	
	/**
	 * temporary theme filename
	 * 
	 * @var	string
	 */
	public $tmpName = '';
	
	/**
	 * theme editor object
	 * 
	 * @var	ThemeEditor
	 */
	public $theme = null;
	
	// parameters
	public $themeURL = 'http://';
	public $themeUpload;
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['themeURL'])) $this->themeURL = StringUtil::trim($_POST['themeURL']);
		if (isset($_FILES['themeUpload'])) $this->themeUpload = $_FILES['themeUpload'];
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();		
		
		// upload or download theme
		if ($this->themeUpload && $this->themeUpload['error'] != 4) {
			if ($this->themeUpload['error'] != 0) {
				throw new UserInputException('themeUpload', 'uploadFailed');
			}
			
			$this->tmpName = $this->themeUpload['tmp_name'];
			
			try {
				ThemeEditor::getThemeData($this->tmpName, 'theme');
			}
			catch (SystemException $e) {
				throw new UserInputException('themeUpload', 'invalid');
			}
			
			// copy file
			$newFilename = FileUtil::getTemporaryFilename('theme_');
			if (@move_uploaded_file($this->tmpName, $newFilename)) {
				$this->tmpName = $newFilename;
			}
		}
		else if ($this->themeURL != 'http://') {
			if (StringUtil::indexOf($this->themeURL, 'http://') !== 0) {
				throw new UserInputException('themeURL', 'downloadFailed');
			}
			
			try {
				$this->tmpName = FileUtil::downloadFileFromHttp($this->avatarURL, 'theme');
			}
			catch (SystemException $e) {
				throw new UserInputException('themeURL', 'downloadFailed');
			}
			
			try {
				ThemeEditor::getThemeData($this->tmpName, 'theme');
			}
			catch (SystemException $e) {
				throw new UserInputException('themeURL', 'invalid');
			}
		}
		else {
			throw new UserInputException('themeUpload');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// import theme
		$this->theme = ThemeEditor::import($this->tmpName);
		
		// reset cache
		WCF::getCache()->clearResource('theme-'.PACKAGE_ID);
		WCF::getCache()->clear(WCF_DIR.'cache', 'cache.templatePacks.php');
		WCF::getCache()->clear(WCF_DIR.'cache', 'cache.templates-*.php');
		$this->saved();
		
		// reset values
		$this->themeURL = 'http://';
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'themeURL' => $this->themeURL
		));
	}
}
?>