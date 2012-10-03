<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * SphinxDocList Model
 */
class SphinxDocModelSphinxDocs extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('sd.id, sd.documentation, sd.directory, sd.description ');
		// From the hello table
		$query->from('#__sphinxdoc as sd');
		$query->select('c.title AS category, c.alias AS category_alias, c.access AS category_access');
		$query->join('LEFT', '#__categories AS c on c.id = sd.catid');
		return $query;
	}
}
