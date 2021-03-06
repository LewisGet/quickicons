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

include_once AKPATH_COMPONENT.'/viewlist.php' ;

/**
 * View class for a list of Akquickicons.
 */
class AkquickiconsViewImages extends AKViewList
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$canDo	= AkquickiconsHelper::getActions();
		if( !$canDo->get('image.manage') ) {
			$app = JFactory::getApplication() ;
			$app->redirect( 'index.php?option=com_akquickicons', JText::_('JERROR_ALERTNOAUTHOR') , 'warning');
			return ;
		}
		
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			
			if( JVERSION >= 3 ){
				$this->sidebar = JHtmlSidebar::render();
			}
		}
		
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$canDo	= AkquickiconsHelper::getActions();

		JToolBarHelper::title(JText::_('COM_AKQUICKICONS_TITLE_IMAGES'), 'mediamanager.png');
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_akquickicons');
		}

	}
	
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		$this->sort_fields = array(
			'a.ordering' 		=> JText::_('JGRID_HEADING_ORDERING'),
			'a.published' 		=> JText::_('JPUBLISHED'),
			'a.title' 			=> JText::_('JGLOBAL_TITLE'),
			'b.title' 			=> JText::_('JCATEGORY'),
			'd.title' 			=> JText::_('JGRID_HEADING_ACCESS'),
			'a.created_by' 		=> JText::_('JAUTHOR'),
			'e.title' 			=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.created' 		=> JText::_('JDATE'),
			'a.id' 				=> JText::_('JGRID_HEADING_ID')
		);
		
		return $this->sort_fields ;
	}
	
	
	
	
	/*
	 * function renderGrid
	 * @param $table
	 */
	
	public function renderGrid($table, $option = array())
	{
		// Set Grid
		// =================================================================================
		$grid = new JGrid();
		
		$grid->setTableOptions($option);
		$grid->setColumns( array_keys($table['thead']['tr'][0]['th']) ) ;
		
		
		
		// Thead
		// =================================================================================
		$grid->addRow($table['thead']['tr'][0]['option'], 1) ;
		
		foreach( $table['thead']['tr'][0]['th'] as $key => $th ):
			$grid->setRowCell($key, $th['content'] , $th['option']);
		endforeach;
		
		
		
		// Tbody
		// =================================================================================
		foreach( $table['tbody']['tr'] as $tr ):
			
			$grid->addRow($tr['option']) ;
			
			foreach( $tr['td'] as $key2 => $td ):
				
				$grid->setRowCell($key2, $td['content'] , $td['option']);
				
			endforeach;
			
		endforeach;
		
		
		return $grid ;
	}
}
