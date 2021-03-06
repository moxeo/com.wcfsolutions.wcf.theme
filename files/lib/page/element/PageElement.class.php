<?php
/**
 * All page element classes should implement this interface.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	page.element
 * @category	Community Framework
 */
interface PageElement {
	/**
	 * Returns the identifier of this page element.
	 *
	 * @return	string
	 */
	public function getIdentifier();

	/**
	 * Returns the content (html code).
	 *
	 * @return	string
	 */
	public function getContent();
}
?>