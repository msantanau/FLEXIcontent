<?php
/**
 * @version 1.5 stable $Id: select2.php 2014-07-16 10:17:11
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2014 Miguel Santana - www.lyquix.com
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); 
if (FLEXI_J16GE) {
	jimport('joomla.html.html');
	jimport('joomla.form.formfield');
	jimport('joomla.form.helper');
	JFormHelper::loadFieldClass('list');
}

/**
 * Renders a fields element
 *
 * @package 	Joomla
 * @subpackage	FLEXIcontent
 * @since		1.5
 */
class JFormFieldSelect2 extends JFormField
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var	$type = 'Select2';

	function getInput()
	{
		$doc	= JFactory::getDocument();
//		$doc->addScript(JURI::base().'components/com_flexicontent/assets/js'.DS.'select2.js');
		$doc->addScript(JURI::root().'components'.DS.'com_flexicontent'.DS.'librairies'.DS.'select2'.DS.'select2.js');
//		$doc->addStyleSheet(JURI::base().'components/com_flexicontent/assets/css'.DS.'select2.css');
		$doc->addStyleSheet(JURI::root().'components'.DS.'com_flexicontent'.DS.'librairies'.DS.'select2'.DS.'select2.css');
		$db		= JFactory::getDBO();
		$cparams = JComponentHelper::getParams('com_flexicontent');

		if (FLEXI_J16GE) {
			$node = & $this->element;
			$attributes = get_object_vars($node->attributes());
			$attributes = $attributes['@attributes'];
		} else {
			$attributes = & $node->_attributes;
		}
		
		
		// ***********************************
		// Values, form field name and id, etc
		// ***********************************
		$values			= FLEXI_J16GE ? $this->value : $value;

		if ( empty($values) )							$values = array();
		else if ( ! is_array($values) )		$values = !FLEXI_J16GE ? array($values) : explode("|", $values);

		$split_char = ",";
		// Get options and values
		$options = preg_split("/[\s]*".$split_char."[\s]*/", $attributes['options']);
		$default = preg_split("/[\s]*".$split_char."[\s]*/", @$attributes['default']);
		$fieldname	= FLEXI_J16GE ? $this->name : $control_name.'['.$name.']';
		$element_id = FLEXI_J16GE ? $this->id : $control_name.$name;
		$name = FLEXI_J16GE ? $attributes['name'] : $name;
		$control_name = FLEXI_J16GE ? str_replace($name, '', $element_id) : $control_name;
		
		// *******************************************
		// HTML Tag parameters parameters, and styling
		// *******************************************
		
		$style  = "float:left; white-space:nowrap; margin:2px 8px 0px;".(!FLEXI_J16GE ? "margin-right:12px; " : "");
		
		// *******************************************
		// ONLY FOR SELECT2 MULTI SELETEC AND SORTABLE
		// OTHER SELECT2 OPTIIONS TO BE DEVELOP
		// *******************************************
		if (@$attributes['sortable']=='sortable' || @$attributes['sortable']=='true' ) { // TO support Select2 drag and drop sorting of selected choices.
			$fieldname .= !FLEXI_J16GE ? "[]" : "";  // NOTE: this added automatically in J2.5
			$html .= '<input id="'.$element_id.'_options" name="'.$fieldname.' "type="hidden" class="'.@$attributes['class'].'" style="'.$style.'" value="'.$values[0].'">';
			$js 	= '<script>
jQuery(document).ready(function() {
  jQuery("#'.$element_id.'_options").select2({
	  createSearchChoice: null, /* to restrict any user inputs */
	  tags:["'.implode('","', $options).'"],
	  tokenSeparators: [","],
	  maximumSelectionSize: '.count($options).'
  });
  jQuery("#'.$element_id.'_options").on("change", function() { jQuery("#'.$element_id.'_options_val").html(jQuery("#'.$element_id.'_options").val());});
  jQuery("#'.$element_id.'_options").select2("container").find("ul.select2-choices").sortable({
	  containment: \'parent\',
	  start: function() { jQuery("#'.$element_id.'_options").select2("onSortStart"); },
	  update: function() { jQuery("#'.$element_id.'_options").select2("onSortEnd"); }
  });
});  
</script>';
//		$doc->addScriptDeclaration($js);
			$html .=$js;
		}
		else{// needs to be develop in order to handles all SELECT2 options
		  $html = JHTML::_('select.genericlist', $fields, $fieldname, $attribs, 'value', 'text', $values, $element_id);
		}
		return $html;
	}
}
?>
