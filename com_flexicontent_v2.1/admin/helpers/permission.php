<?php
// No direct access
defined('_JEXEC') or die;

/**
 * FLEXIcontent helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @since		1.6
 */
class FLEXIcontentHelperPermission
{
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	string	$extension	The extension.
	 * @param	int		$categoryId	The category ID.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($extension = 0, $Id = 0)
	{
		$user		= JFactory::getUser();
		$result		= new JObject;

		if (empty($extension) && empty($Id)) {
			$assetName = 'com_flexicontent';
		}
		else if (empty($extension)) {
			$assetName = 'flexicontent.'.(int) $Id;
		}
		else {
			$assetName = 'flexicontent.'.$extension.'.'.(int) $categoryId;
		}

		$actions = array(
			'core.admin', 'core.manage',
			'flexicontent.managetype', 'flexicontent.fields', 'flexicontent.archives', 'flexicontent.stats', 'flexicontent.templates',
			'flexicontent.versioning', 'flexicontent.tags', 'flexicontent.usetags', 'flexicontent.newtag',
			'flexicontent.create', 'flexicontent.editall', 'flexicontent.editall.state', 'flexicontent.deleteall', 'flexicontent.order',
			'flexicontent.copyitems', 'flexicontent.paramsitem', 'flexicontent.displayallitems',
			'flexicontent.managecat', 'flexicontent.usercats', 'flexicontent.viewtree', 'flexicontent.createcat', 'flexicontent.editallcat',
			'flexicontent.deleteallcat', 'flexicontent.editallcat.state', 'flexicontent.multicat',
			'flexicontent.managefile', 'flexicontent.uploadfiles', 'flexicontent.viewallfiles',
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}