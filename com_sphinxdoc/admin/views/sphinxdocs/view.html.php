<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * SphinxDocs View
 */
class SphinxDocViewSphinxDocs extends JView
{
	/**
	 * SphinxDocs view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		// Get data from the model
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		//$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		$canDo = SphinxDocHelper::getActions();
		JToolBarHelper::title(JText::_('COM_SPHINXDOC_MANAGER_SPHINXDOCS'), 'sphinxdoc');
		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('sphinxdoc.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::editList('sphinxdoc.edit', 'JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'sphinxdocs.delete', 'JTOOLBAR_DELETE');
		}
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_sphinxdoc');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SPHINXDOC_ADMINISTRATION'));
	}
}
