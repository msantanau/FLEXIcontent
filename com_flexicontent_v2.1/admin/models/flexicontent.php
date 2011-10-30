<?php
/**
 * @version 1.5 stable $Id: flexicontent.php 262 2010-06-11 05:02:20Z enjoyman $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * FLEXIcontent Component Model
 *
 * @package Joomla
 * @subpackage FLEXIcontent
 * @since		1.0
 */
class FLEXIcontentModelFlexicontent extends JModel
{
	protected $_buttons;
	/**
	 * Helper method to return button list.
	 *
	 * This method returns the array by reference so it can be
	 * used to add custom buttons or remove default ones.
	 *
	 * @return	array	An array of buttons
	 * @since	1.6
	 */
	public function &getButtons()
	{
		if (empty($this->_buttons)) {
			$option = JRequest::getVar('option');
			
			$this->_buttons = array(
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=items',
					'icon' 	=> 'icon-48-items.png',
					'text' 	=> JText::_( 'FLEXI_ITEMS' ),
					'access' => false
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=types&amp;format=raw',
					'icon' 	=> 'icon-48-item-add.png',
					'text' 	=> JText::_( 'FLEXI_NEW_ITEM' ),
					'access' => 'flexicontent.create',
					'modal' => true
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=categories',
					'icon' 	=> 'icon-48-categories.png',
					'text' 	=> JText::_( 'FLEXI_CATEGORIES' ),
					'access' => 'flexicontent.managecat'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=category',
					'icon' 	=> 'icon-48-category-add.png',
					'text' 	=> JText::_( 'FLEXI_NEW_CATEGORY' ),
					'access' => 'flexicontent.createcat'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=types',
					'icon' 	=> 'icon-48-types.png',
					'text' 	=> JText::_( 'FLEXI_TYPES' ),
					'access' => 'flexicontent.managetype'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=type',
					'icon' 	=> 'icon-48-type-add.png',
					'text' 	=> JText::_( 'FLEXI_NEW_TYPE' ),
					'access' => 'flexicontent.managetype'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=fields',
					'icon' 	=> 'icon-48-fields.png',
					'text' 	=> JText::_( 'FLEXI_NEW_FIELD' ),
					'access' => 'flexicontent.fields'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=field',
					'icon' 	=> 'icon-48-field-add.png',
					'text' 	=> JText::_( 'FLEXI_FIELDS' ),
					'access' => 'flexicontent.fields'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=tags',
					'icon' 	=> 'icon-48-tags.png',
					'text' 	=> JText::_( 'FLEXI_TAGS' ),
					'access' => 'flexicontent.tags'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=tag',
					'icon' 	=> 'icon-48-tag-add.png',
					'text' 	=> JText::_( 'FLEXI_NEW_TAG' ),
					'access' => 'flexicontent.tags'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=filemanager',
					'icon' 	=> 'icon-48-editcss.png',
					'text' 	=> JText::_( 'FLEXI_FILEMANAGER' ),
					'access' => 'flexicontent.managefile'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=templates',
					'icon' 	=> 'icon-48-file.png',
					'text' 	=> JText::_( 'FLEXI_TEMPLATES' ),
					'access' => 'flexicontent.templates'
				),
				array(
					'link' 	=> 'index.php?option='.$option.'&amp;view=stats',
					'icon' 	=> 'icon-48-stats.png',
					'text' 	=> JText::_( 'FLEXI_STATISTICS' ),
					'access' => 'flexicontent.stats'
				),
				array(
					'link' 	=> 'index.php?option=com_plugins&filter_folder=flexicontent_fields&tmpl=component',
					'icon' 	=> 'icon-48-plugins.png',
					'text' 	=> JText::_( 'FLEXI_PLUGINS' ),
					'access' => 'core.manage',
					'modal' => true
				)				
			);
		}
		
		return $this->_buttons;
	}	
	
}
?>
