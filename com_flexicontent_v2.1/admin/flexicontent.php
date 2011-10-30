<?php
/**
 * @version 1.5 stable $Id: admin.flexicontent.php 354 2010-06-29 12:08:53Z emmanuel.danan $ 
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

// No direct access to this file
defined('_JEXEC') or die;
 
if (!JFactory::getUser()->authorise('core.manage', 'com_flexicontent')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('FLEXIcontentHelperPermission', dirname(__FILE__) . DS . 'helpers' . DS . 'permission.php');
 
jimport('joomla.application.component.controller');

$controller = JController::getInstance('FLEXIcontent');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
