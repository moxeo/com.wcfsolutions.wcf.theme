<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ThemeLayoutEditForm.class.php');

/**
 * Shows the theme layout copy form.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	acp.form
 * @category	Community Framework
 */
class ThemeLayoutCopyForm extends ThemeLayoutEditForm {
	/**
	 * @see	Form::save()
	 */
	public function save() {
		AbstractForm::save();

		// copy theme layout
		$this->themeLayout->copy($this->title, $this->themeStylesheetIDs);

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
			'action' => 'copy'
		));
	}
}
?>