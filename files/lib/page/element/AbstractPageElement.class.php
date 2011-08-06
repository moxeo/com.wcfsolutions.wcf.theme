<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/page/element/PageElement.class.php');

/**
 * Provides default implementations for page elements.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	page.element
 * @category	Community Framework
 */
abstract class AbstractPageElement extends MultipleLinkPage implements PageElement {
	/**
	 * parsed content
	 * 
	 * @var	string
	 */
	public $content = '';
	
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
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// assign parameters
		WCF::getTPL()->assign(array(
			'identifier' => $this->getIdentifier()
		));
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