<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/page/element/PageElement.class.php');

/**
 * Provides default implementations for form elements.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	form.element
 * @category	Community Framework
 */
abstract class AbstractFormElement extends AbstractForm implements PageElement {
	/**
	 * parsed content
	 *
	 * @var	string
	 */
	private $content = '';

	/**
	 * @see	Page::show()
	 */
	public function show() {
		if (empty($this->neededPermissions) || WCF::getUser()->getPermission($this->neededPermissions)) {
			// read data
			$this->readData();

			// assign variables
			$this->assignVariables();

			// call show event
			EventHandler::fireAction($this, 'show');

			// parse content
			if (!empty($this->templateName)) {
				$this->content = WCF::getTPL()->fetch($this->templateName);
			}
		}
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		// get identifier
		$identifier = '';
		if (isset($_POST['identifier'])) $identifier = $_POST['identifier'];

		// submit
		if ($identifier == $this->getIdentifier()) {
			parent::submit();
		}
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// assign parameters
		WCF::getTPL()->assign(array(
			'identifier' => $this->getIdentifier(),
			'formElementInputTag' => '<input type="hidden" name="identifier" value="'.$this->getIdentifier().'" />'
		));
	}

	/**
	 * Sets the content (i.e. HTML output) of this form element.
	 *
	 * @param	string		$content	The content (i.e. HTML output) of this form element.
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @see	PageElement::getContent()
	 */
	public function getContent() {
		// call getContent event
		EventHandler::fireAction($this, 'getContent');

		return $this->content;
	}
}
?>