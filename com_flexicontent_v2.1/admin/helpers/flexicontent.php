<?php
// No direct access to this file
defined('_JEXEC') or die;
 
abstract class FLEXIcontentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($vName) 
	{
		$canDo	= FLEXIcontentHelperPermission::getActions();
		
		JSubMenuHelper::addEntry( JText::_( 'FLEXI_ITEMS' ), 'index.php?option=com_flexicontent&view=items', $vName=='items');
		if ($canDo->get('flexicontent.managetype'))	JSubMenuHelper::addEntry( JText::_( 'FLEXI_TYPES' ), 'index.php?option=com_flexicontent&view=types', $vName=='types');
		if ($canDo->get('flexicontent.managecat'))	JSubMenuHelper::addEntry( JText::_( 'FLEXI_CATEGORIES' ), 'index.php?option=com_flexicontent&view=categories', $vName=='categories');
		if ($canDo->get('flexicontent.fields')) 	JSubMenuHelper::addEntry( JText::_( 'FLEXI_FIELDS' ), 'index.php?option=com_flexicontent&view=fields', $vName=='fields');
		if ($canDo->get('flexicontent.tags'))		JSubMenuHelper::addEntry( JText::_( 'FLEXI_TAGS' ), 'index.php?option=com_flexicontent&view=tags', $vName=='tags');
		if ($canDo->get('flexicontent.managefile')) JSubMenuHelper::addEntry( JText::_( 'FLEXI_FILEMANAGER' ), 'index.php?option=com_flexicontent&view=filemanager', $vName=='filemanager');
		JSubMenuHelper::addEntry( JText::_( 'FLEXI_SEARCH_INDEX' ), 'index.php?option=com_flexicontent&view=search',$vName=='search');
		if ($canDo->get('flexicontent.templates')) 	JSubMenuHelper::addEntry( JText::_( 'FLEXI_TEMPLATES' ), 'index.php?option=com_flexicontent&view=templates', $vName=='templates');
		if ($canDo->get('flexicontent.stats'))		JSubMenuHelper::addEntry( JText::_( 'FLEXI_STATISTICS' ), 'index.php?option=com_flexicontent&view=stats', $vName=='stats');
	}

	public function flexiExit($msg = null, $ajax = true)
	{
		if(!empty($msg)) {
			if($ajax) {
				ob_get_clean();
			}
			print_r( $msg );
		}
	
		exit;
	}	
}
