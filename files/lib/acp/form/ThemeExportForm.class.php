<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');

/**
 * Shows the theme export form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeExportForm extends ACPForm {
	// system
	public $templateName = 'themeExport';
	public $activeMenuItem = 'wcf.acp.menu.link.theme';
	public $neededPermissions = 'admin.theme.canExportTheme';
	
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
	
	// parameters
	public $filename = '';
	public $exportTemplates = 1;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get theme
		if (isset($_REQUEST['themeID'])) $this->themeID = intval($_REQUEST['themeID']);
		$this->theme = new ThemeEditor($this->themeID);	
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->filename = ThemeEditor::getFilename($this->theme->themeName);
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->exportTemplates = 0;
		if (isset($_POST['filename'])) $this->filename = StringUtil::trim($_POST['filename']);
		if (isset($_POST['exportTemplates'])) $this->exportTemplates = intval($_POST['exportTemplates']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();		
		
		// filename
		$this->filename = ThemeEditor::getFilename($this->filename);
		if (empty($this->filename)) {
			throw new UserInputException('filename');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// send headers
		header('Content-Type: application/x-gzip; charset='.CHARSET);
		header('Content-Disposition: attachment; filename="'.$this->filename.'-theme.tgz"');
		
		// export theme
		$this->theme->export($this->exportTemplates);
		$this->saved();
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'themeID' => $this->themeID,
			'theme' => $this->theme,
			'filename' => $this->filename,
			'exportTemplates' => $this->exportTemplates
		));
	}
}
?>