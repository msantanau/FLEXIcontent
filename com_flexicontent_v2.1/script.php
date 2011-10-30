<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.access.rules');

/**
 * Script file of FLEXIcontent component
 */
class com_flexicontentInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		$output[] = '
			<div>
		    	<img src="components/com_flexicontent/assets/images/logo.png" height="96" width="300" alt="FLEXIcontent Logo" />
			</div>
			<div>
	       	 	<strong>FLEXIcontent</strong><br />
	       	 	<span>Flexible content management system for Joomla! 1.6/1.7</span><br />
	        	<span class="small">by <a href="http://www.vistamedia.fr" target="_blank">Emmanuel Danan</a></span><br />
	        	<span class="small">and <a href="http://www.marvelic.co.th" target="_blank">Marvelic Engine Co.,Ltd.</a></span><br />
	       	 	<span>Logo and icons</span><br />
	        	<span class="small">by <a href="http://www.artefact-design.com" target="_blank">Greg Berthelot</a></span><br />
			</div>';
			
		$output[] = '
			<div class="posttasks">
				<strong>Postinstall Tasks</strong>
			</div>';

		echo implode('', $output);
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('UNINSTALL_TEXT') . '</p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// clear a cache
		$cache 		= & JFactory::getCache();
		$cache->clean( 'com_flexicontent' );
		$cache->clean( 'com_flexicontent_tmpl' );
		$cache->clean( 'com_flexicontent_cats' );
		$cache->clean( 'com_flexicontent_items' );
		
		// $parent is the class calling this method
		echo '<p>' . JText::_('UPDATE_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		$output [] = '<ul class="posttasks">';
		
		switch ($type) {
			case 'install':
				if ($this->_initialPermission()) {
					$output [] = '<li>Initial Permissions: <span class="">'.JText::_('Successfully') .'</span></li>';
				} else {
					JError::raiseWarning(100, JText::_('ERROR: Creating initial Permissions'));
					return false;
				}

				if ($this->createMenuItems()) {
					$output [] = '<li>Created default menu items used for SEF links: <span class="">'.JText::_('Successfully') .'</span></li>';
				} else {
					JError::raiseWarning(100, JText::_('ERROR: Creating default menu items'));
					return false;
				}
				
				if ($this->_createDefaultType()) {
					$output [] = '<li>Created default type: <span class="">'.JText::_('Successfully') .'</span></li>';
				} else {
					JError::raiseWarning(100, JText::_('ERROR: Creating default type'));
					return false;
				}

				if ($this->_createDefaultFields()) {
					$output [] = '<li>Created default fields: <span class="">'.JText::_('Successfully') .'</span></li>';
				} else {
					JError::raiseWarning(100, JText::_('ERROR: Creating default fields'));
					return false;
				}

				/*if ($this->publishPlugins()) {
					$output [] = '<div>Publishing pluggins: <span style="">'.JText::_('Successfully') .'</span></div>';
				} else {
					JError::raiseNotice(1, JText::_( 'FLEXI_COULD_NOT_PUBLISH_PLUGINS' ));
					return false;
				}*/	

				break;
			
			case 'uninstall':
				break;
			case 'update':
				break;
			default:
				
				break;
		}

		$output [] = '</ul>';
		echo implode('', $output);
	}

	/**
	 * Method to creat default permissions
	 * 
	 * @access	private
	 * @return	boolean	True on success
	 * @since 1.5
	 */
	private function _initialPermission() {
		$db = &JFactory::getDBO();

		$query	= $db->getQuery(true)
			->select('rules')
			->from('#__assets')
			->where('name = '.$db->quote('com_flexicontent'));
		$db->setQuery($query);
		$rules = $db->loadResult();
		$rule = new JRules($rules);
		
		$query	= $db->getQuery(true)
			->select('*')
			->from('#__usergroups');
		$db->setQuery($query);
		$groups = $db->loadObjectList();
		
		if(count($rule->getData())<=0) {
			
			$rules = array();
			$rule = array();
			foreach($groups as $g) {
				if(JAccess::checkGroup($g->id, 'core.admin')) {//super user
					$rule['core.admin'][$g->id] = 1;  //CanConfig
				}
				if(JAccess::checkGroup($g->id, 'core.manage')) {
					// Privilege 'core.manage', this is currently used only by joomla core !!! DO NOT RENAME !!!
					// Joomla Backend: (a) display FLEXIcontent menu item in Components menu (b) basic use permission for FLEXIcontent component
					$rule['core.manage'][$g->id] = 1;
					
					$rule['flexicontent.manageitem'][$g->id] = 1;
					$rule['flexicontent.managetype'][$g->id] = 1;//CanTypes
					$rule['flexicontent.createtype'][$g->id] = 1;
					$rule['flexicontent.deletetype'][$g->id] = 1;
					$rule['flexicontent.edittype'][$g->id] = 1;
					$rule['flexicontent.edittype.state'][$g->id] = 1;
					$rule['flexicontent.fields'][$g->id] = 1;//CanFields
					$rule['flexicontent.archives'][$g->id] = 1;//CanArchives
					$rule['flexicontent.stats'][$g->id] = 1;//CanStats
					$rule['flexicontent.templates'][$g->id] = 1;//CanTemplates
					$rule['flexicontent.versioning'][$g->id] = 1;//CanVersion
					$rule['flexicontent.tags'][$g->id] = 1;//CanTags
					$rule['flexicontent.usetags'][$g->id] = 1;//CanUseTags
					$rule['flexicontent.newtag'][$g->id] = 1;//CanNewTag
					$rule['flexicontent.order'][$g->id] = 1;//CanOrder
					$rule['flexicontent.copyitems'][$g->id] = 1;//CanCopy
					$rule['flexicontent.paramsitem'][$g->id] = 1;//CanParams
					$rule['flexicontent.displayallitems'][$g->id] = 1;//DisplayAllItems
					$rule['flexicontent.managecat'][$g->id] = 1;//CanCats
					$rule['flexicontent.usercats'][$g->id] = 1;//CanUserCats
					$rule['flexicontent.viewtree'][$g->id] = 1;//CanViewTree
					$rule['flexicontent.createcat'][$g->id] = 1;//CanAddCats
					$rule['flexicontent.editallcat'][$g->id] = 1;//CanEditAllCats
					$rule['flexicontent.deleteallcat'][$g->id] = 1;
					$rule['flexicontent.deleteown'][$g->id] = 1;
					$rule['flexicontent.editallcat.state'][$g->id] = 1;//CanPublishAllCats
					$rule['flexicontent.editown'][$g->id] = 1;
					$rule['flexicontent.edit.state'][$g->id] = 1;
					$rule['flexicontent.editown.state'][$g->id] = 1;
					$rule['flexicontent.deletecat'][$g->id] = 1;
					$rule['flexicontent.deleteowncat'][$g->id] = 1;
					$rule['flexicontent.editcat'][$g->id] = 1;
					$rule['flexicontent.editcat.state'][$g->id] = 1;
					$rule['flexicontent.editowncat.state'][$g->id] = 1;
					$rule['flexicontent.editowncat'][$g->id] = 1;
					$rule['flexicontent.managefile'][$g->id] = 1;//CanFiles
					$rule['flexicontent.uploadfiles'][$g->id] = 1;//CanUpload
					$rule['flexicontent.viewallfiles'][$g->id] = 1;//CanViewAllFiles
				}
				if(JAccess::checkGroup($g->id, 'core.create')) {
					$rule['flexicontent.create'][$g->id] = 1;//CanAdd
				}
				if(JAccess::checkGroup($g->id, 'core.delete')) {
					$rule['flexicontent.deleteall'][$g->id] = 1;//CanDelete
				}
				if(JAccess::checkGroup($g->id, 'core.edit')) {
					$rule['flexicontent.editall'][$g->id] = 1;//CanEdit
				}
				if(JAccess::checkGroup($g->id, 'core.edit.state')) {
					$rule['flexicontent.editall.state'][$g->id] = 1;//CanPublish
				}
				if(JAccess::checkGroup($g->id, 'core.edit.own')) {
					$rule['flexicontent.editown'][$g->id] = 1;
				}
			}
			foreach($rule as $key=>$ar) {
				$rules[$key] = new JRule($ar);
			}
			$jrules = new JRules($rules);
			
			$query	= $db->getQuery(true)
				->select('id')
				->from('#__assets')
				->where('name = '.$db->quote('com_flexicontent'));
			$db->setQuery($query);
			$id = $db->loadResult();

			if($id) {
				$query = $db->getQuery(true)
					->update('#__assets')
					->set('rules = '.$db->quote($jrules->__toString()))
					->where('id = '.$db->quote($id) );
				$db->setQuery($query);
				if(!$db->query()) return false;
			}
		}
		$query	= $db->getQuery(true)
			->select('id')
			->from('#__flexicontent_fields')
			->where('asset_id = 0');
		$db->setQuery($query);
		$rows = $db->loadResultArray();
		if(count($rows)>0) {
			$asset	= JTable::getInstance('Asset');
			$parentId = 1;
			foreach($rows as $id) {
				$name = "flexicontent.field.{$id}";
				$query	= $db->getQuery(true)
					->select('id')
					->from('#__assets')
					->where('name = '.$db->quote($name));
				$db->setQuery($query);
				$asset_id = $db->loadResult();
				if(!$asset_id) {
					$asset->loadByName($name);
					$asset->id = null;
					if ($asset->parent_id != $parentId) {
						$asset->setLocation($parentId, 'last-child');
					}
					// Prepare the asset to be stored.
					$asset->parent_id	= $parentId;
					$asset->name		= $name;
					$asset->title		= $name;
					$rules = array();
					$rule = array();
					foreach($groups as $g) {
						$rule['flexicontent.readfield'][$g->id] = 1;
					}
					$rules['flexicontent.readfield'] = new JRule($rule['flexicontent.readfield']);
					$jrules = new JRules($rules);
					$asset->rules = $jrules->__toString();
					if (!$asset->check() || !$asset->store(false)) {
						return false;
					}
					$asset_id = $asset->id;
				}

				$query = $db->getQuery(true)
					->update('#__flexicontent_fields')
					->set('asset_id = '.$db->quote($asset_id))
					->where('id = '.$db->quote($id) );
				$db->setQuery($query);
				$db->query();
			}
		}
		return true;
	}

	/**
	 * Method to create default menu items used for SEF links
	 * 
	 * @access	public
	 * @return	boolean	True on success
	 * @since 1.5
	 */
	public function createMenuItems()
	{
		$db =& JFactory::getDBO();
		
		$query	= $db->getQuery(true)
			->select('extension_id')
			->from('#__extensions')
			->where('element = '.$db->quote('com_flexicontent'))
			->where('type = '.$db->quote('component'));
		$db->setQuery($query);
		$flexi_comp_id = $db->loadResult();	
		
		$query	= $db->getQuery(true)
			->delete('#__menu_types')
			->where('menutype = '.$db->quote('flexihiddenmenu'));
		$db->setQuery($query);
		$db->query();

		$query	= $db->getQuery(true)
			->insert('#__menu_types')
			->set('menutype = '.$db->quote('flexihiddenmenu'))
			->set('title = '.$db->quote('FLEXIcontent Hidden Menu'))
			->set('description = '.$db->quote('A hidden menu to host Flexicontent needed links'));
		$db->setQuery($query);
		$db->query();

		$query	= $db->getQuery(true)
			->delete('#__menu')
			->where('menutype = '.$db->quote('flexihiddenmenu'));
		$db->setQuery($query);
		$db->query();
		
		$query 	=	"INSERT INTO #__menu (`menutype`,`title`,`alias`,`path`,`link`,`type`,`published`,`parent_id`,`component_id`,`level`,`ordering`,`checked_out`,`checked_out_time`,`browserNav`,`access`,`params`,`lft`,`rgt`,`home`)
		VALUES ".
		"('flexihiddenmenu','Site Content','site_content','site_content','index.php?option=com_flexicontent&view=flexicontent','component',1,1,$flexi_comp_id,1,1,0,'0000-00-00 00:00:00',0,0,'rootcat=0',0,0,0)";
		
		$db->setQuery($query);
		$result = $db->query();
		if($result) {
			// Save the created menu item as default_menu_itemid for the component
			$component =& JComponentHelper::getParams('com_flexicontent');
			$component->set('default_menu_itemid', $db->insertid());
			$cparams = $component->toString();

			$flexi =& JComponentHelper::getComponent('com_flexicontent');

			$query	= $db->getQuery(true)
				->insert('#__extensions')
				->set('params = '.$db->quote($cparams))
				->where('extension_id = '. (int) $flexi->id);
			$db->setQuery($query);
			$result = $db->query();
		}
		
		return $result ? true : false ;
	}

	/**
	 * Method to create default type : article
	 * 
	 * @access	private
	 * @return	boolean	True on success
	 * @since 1.5
	 */
	private function _createDefaultType() {
		$db =& JFactory::getDBO();

		$query = "INSERT INTO #__flexicontent_types VALUES(1, 'Article', 'article', 1, 0, '0000-00-00 00:00:00', 0, 'ilayout=default\nhide_maintext=0\nhide_html=0\nmaintext_label=\nmaintext_desc=\ncomments=\ntop_cols=two\nbottom_cols=two')" ;
		$db->setQuery($query);
		if (!$db->query()) {
			return false;
		} else {
			$query = "INSERT INTO #__flexicontent_fields_type_relations (`field_id`,`type_id`,`ordering`)
						VALUES
							(1,1,1),
							(2,1,2),
							(3,1,3),
							(4,1,4),
							(5,1,5),
							(6,1,6),
							(7,1,7),
							(8,1,8),
							(9,1,9),
							(10,1,10),
							(11,1,11),
							(12,1,12),
							(13,1,13),
							(14,1,14)" ;
			$db->setQuery($query);
			if (!$db->query()) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	/**
	 * Method to create default fields data
	 * 
	 * @access	private
	 * @return	boolean	True on success
	 * @since 1.5
	 */
	private function _createDefaultFields()
	{
		$db =& JFactory::getDBO();

		$query 	=	"INSERT INTO #__flexicontent_fields (`id`,`field_type`,`name`,`label`,`description`,`isfilter`,`iscore`,`issearch`,`isadvsearch`,`positions`,`published`,`attribs`,`checked_out`,`checked_out_time`,`access`,`ordering`)
			VALUES
				(1,'maintext','text','Description','The main description text (introtext/fulltext)',0,1,1,0,'description.items.default',1,'display_label=0\ntrigger_onprepare_content=0',0,'0000-00-00 00:00:00',1,2),
				(2,'created','created','Created','Creation date',0,1,1,0,'top.items.default\nabove-description-line1-nolabel.category.blog',1,'display_label=1\ndate_format=DATE_FORMAT_LC1\ncustom_date=\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,3),
				(3,'createdby','created_by','Created by','Item author',0,1,1,0,'top.items.default\nabove-description-line1-nolabel.category.blog',1,'display_label=1\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,4),
				(4,'modified','modified','Last modified','Date of the last modification',0,1,1,0,'top.items.default',1,'display_label=1\ndate_format=DATE_FORMAT_LC1\ncustom_date=\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,5),
				(5,'modifiedby','modified_by','Revised by','Name of the user which last edited the item',0,1,1,0,'top.items.default',1,'display_label=1\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,6),
				(6,'title','title','Title','The item title',0,1,1,0,'',1,'display_label=1',0,'0000-00-00 00:00:00',1,1),
				(7,'hits','hits','Hits','Number of hits',0,1,1,0,'',1,'display_label=1\npretext=\nposttext=views',0,'0000-00-00 00:00:00',1,7),
				(8,'type','document_type','Document type','Document type',0,1,1,0,'',1,'display_label=1\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,8),
				(9,'version','version','Version','Number of version',0,1,1,0,'',1,'display_label=1\npretext=\nposttext=',0,'0000-00-00 00:00:00',1,9),
				(10,'state','state','State','State',0,1,1,0,'',1,'display_label=1',0,'0000-00-00 00:00:00',1,10),
				(11,'voting','voting','Voting','The up and down voting buttons',0,1,1,0,'top.items.default\nabove-description-line2-nolabel.category.blog',1,'display_label=1\ndimension=16\nimage=components/com_flexicontent/assets/images/star-small.png',0,'0000-00-00 00:00:00',1,11),
				(12,'favourites','favourites','Favourites','The add to favourites button',0,1,1,0,'top.items.default\nabove-description-line2-nolabel.category.blog',1,'display_label=1',0,'0000-00-00 00:00:00',1,12),
				(13,'categories','categories','Categories','The categories assigned to this item',0,1,1,0,'top.items.default\nunder-description-line1.category.blog',1,'display_label=1\nseparatorf=2',0,'0000-00-00 00:00:00',1,13),
				(14,'tags','tags','Tags','The tags assigned to this item',0,1,1,0,'top.items.default\nunder-description-line2.category.blog',1,'display_label=1\nseparatorf=2',0,'0000-00-00 00:00:00',1,14)" ;
		$db->setQuery($query);
		if (!$db->query()) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Publish FLEXIcontent plugins
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function publishPlugins()
	{
		$db =& JFactory::getDBO();

		$query = $db->getQuery(true)
			->update('#__extensions')
			->set('enabled = 1')
			->where('type = '.$db->quote('plugin')
				. ' AND (folder = ' . $db->Quote('flexicontent_fields')
				. ' OR element = ' . $db->Quote('flexisearch')
				. ' OR element = ' . $db->Quote('flexisystem') . ')'
				. ' OR element = ' . $db->Quote('flexiadvsearch')
				. ' OR element = ' . $db->Quote('flexiadvroute'));
		$db->setQuery($query);
		if (!$db->query()) {
			return false;
		} else {
			return true;
		}
	}
}
