<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_akquickicons
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;
 
/**
 * Script file of HelloWorld component
 */
class com_AkquickiconsInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		// Set Default datas with asset.
		$db = JFactory::getDbo();
		
		$p_installer = $parent->getParent() ;
		$path = $p_installer->getPath('source');
		
		jimport('joomla.filesystem.folder');
		$origin = $path.DS.'images'.DS.'quickicons' ;
		$target = JPATH_ROOT.DS.'images'.DS.'quickicons' ;
		
		JFolder::move($origin,$target);
				
		// set Category
		$q = $db->getQuery(true) ;
		
		$q->select('id')
			->from('#__categories')
			->where("extension = 'com_akquickicons'")
			;
		
		$db->setQuery($q);
		$catids = $db->loadColumn();
		
		$cat = JTable::getInstance('Category') ;
		
		foreach( $catids as $catid ):
			$cat->load($catid);
			$cat->store();
		endforeach;
		
		// set icons
		$q = $db->getQuery(true) ;
		
		$q->select('id')
			->from('#__akquickicons_icons')
			;
		
		$db->setQuery($q);
		$icon_ids = $db->loadColumn();
		
		$table_path = $path.DS.'tables'.DS.'icon.php' ;
		include_once $table_path ;
		$icon = JTable::getInstance('icon', 'AkquickiconsTable') ;
		
		foreach( $icon_ids as $k => $icon_id ):
			$icon->load($icon_id);
			$icon->catid = $catids[0] ;
			$icon->store();
		endforeach;
		
		$this->catid = $catids[0] ;
		
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select('extension_id')
			->from('#__extensions')
			->where("element='mod_akquickicons'")
			;
		
		$db->setQuery($q);
		$result = $db->loadResult();
		
		if($result):
			$installer = new JInstaller();
			$installer->uninstall( 'module', $result );
			
			$q = $db->getQuery(true) ;
			
			$q->delete('#__categories')
				->where("extension='com_akquickicons'")
				;
			$db->setQuery($q);
			$db->query();
		endif;
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		if($type == 'update') {
			JFolder::delete( JPATH_ADMINISTRATOR.'/components/com_akquickicons/modules' );
			//JFolder::create( JPATH_ADMINISTRATOR.'/components/com_akquickicons' );
		}
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		
		$db = JFactory::getDbo();
		
		
		// Get install manifest
		// ========================================================================
		$p_installer 	= $parent->getParent() ;
		$installer 		= new JInstaller();
		$manifest 		= $p_installer->manifest ;
		$path 			= $p_installer->getPath('source');
		$result			= array() ;
		
		$css =
<<<CSS
	<style type="text/css">
		#ak-install-img {
			
		}
		
		#ak-install-msg {
			
		}
	</style>
CSS;
		
		echo $css ;
		include_once $path.'/windwalker/admin/installscript.php' ;
		
		// set Module active
		// ========================================================================
		if($type == 'install'):
			$q = $db->getQuery(true) ;
			
			$q->select('*')
				->from('#__modules')
				->where("module='mod_akquickicons'")
				;
			$db->setQuery($q);
			$module = $db->loadObject();
			$module->published = 1 ;
			$module->position = ( JVERSION >= 3 ) ? 'cpanel' : 'icon' ;
			$params = new stdClass ;
			$params->catid = $this->catid ;
			$module->params = json_encode($params);
			
			$db->updateObject( '#__modules',$module, 'id');
			
			$in = new stdClass ;
			$in->moduleid = $module->id ;
			$in->menuid = 0 ;
			$db->insertObject( '#__modules_menu',$in);
		endif;
	}
	
}