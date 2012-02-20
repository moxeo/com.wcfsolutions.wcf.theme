<?php
/**
 * All theme module type classes should implement this interface.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.theme
 * @subpackage	data.theme.module.type
 * @category	Community Framework
 */
interface ThemeModuleType {
	// display methods
	/**
	 * Caches all necessary theme module data to save performance.
	 *
	 * @param	ThemeModule		$themeModule
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 */
	public function cache(ThemeModule $themeModule, $themeModulePosition, $additionalData);

	/**
	 * Returns true, if the given theme module object has content.
	 *
	 * @param	ThemeModule		$themeModule
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 * @return	boolean
	 */
	public function hasContent(ThemeModule $themeModule, $themeModulePosition, $additionalData);

	/**
	 * Returns the content of the given theme module object (html code).
	 *
	 * @param	ThemeModule		$themeModule
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 * @return	string
	 */
	public function getContent(ThemeModule $themeModule, $themeModulePosition, $additionalData);

	/**
	 * Returns the searchable content of the given theme module object.
	 *
	 * @param	ThemeModule		$themeModule
	 * @param	string			$themeModulePosition
	 * @param	array			$additionalData
	 * @return	string
	 */
	public function getSearchableContent(ThemeModule $themeModule, $themeModulePosition, $additionalData);

	/**
	 * Returns the preview html code of the given theme module object.
	 *
	 * @param	ThemeModule		$themeModule
	 * @return	string
	 */
	public function getPreviewHTML(ThemeModule $themeModule);

	// form methods
	/**
	 * Reads the given form parameters.
	 */
	public function readFormParameters();

	/**
	 * Validates form inputs.
	 */
	public function validate();

	/**
	 * Returns the form data of this box tab type.
	 *
	 * @return	array
	 */
	public function getFormData();

	/**
	 * Sets the default form data of this box tab type.
	 *
	 * @param	array		$data
	 */
	public function setFormData($data);

	/**
	 * Assigns form variables to the template engine.
	 */
	public function assignVariables();

	/**
	 * Returns the name of the template.
	 *
	 * @return	string
	 */
	public function getFormTemplateName();
}
?>