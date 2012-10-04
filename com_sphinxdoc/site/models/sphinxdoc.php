<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * SphinxDoc Model
 */
class SphinxDocModelSphinxDoc extends JModelItem
{
	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		// Get the message id
		$id = JRequest::getInt('id');
		$this->setState('sphinxdoc.id', $id);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		parent::populateState();
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'SphinxDoc', $prefix = 'SphinxDocTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the message
	 * @return object The message to be displayed to the user
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('sphinxdoc.id');

		//if ($this->_item === null) {
		//	$this->_item = array();
		//}

//		if (!isset($this->_item[$pk])) {
//	public function getItem()
//	{
		if (!isset($this->item))
		{
			//$pk = JRequest::getInt('id');
			error_log('models.sphinxdocs PK '.$pk);

			$this->setState('sphinxdoc.id', $pk);

			//$id = $this->getState('sphinxdoc.id');
			//$db = $this->getDbo();

			$query = $this->_db->getQuery(true);

//			$this->_db->setQuery($this->_db->getQuery(true)
//				->from('#__sphinxdoc as h')
//				->leftJoin('#__categories as c ON h.catid=c.id')
//				->select('h.id, h.documentation, h.index, h.directory,
//						  h.description, h.params, c.title as category,
//						  h.created_by, h.created_by_alias, h.created,
//						  h.modified_by, h.modified')
//				->where('h.id=' . (int)$id));

			$query->select($this->getState(
				'item.select', 'sd.id, sd.documentation, sd.alias, ' .
				'sd.index, sd.directory, sd.description, ' .
				// If badcats is not null, this means that the article is inside an unpublished category
				// In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
				'CASE WHEN badcats.id is null THEN 1 ELSE 0 END AS state, ' .
				'sd.catid, sd.created, sd.created_by, sd.created_by_alias, ' .
			// use created if modified is 0
			'CASE WHEN sd.modified = 0 THEN sd.created ELSE sd.modified END as modified, ' .
				'sd.modified_by, sd.language, sd.ordering, sd.params'
				)
			);
			$query->from('#__sphinxdoc AS sd');

			// Join on category table.
			$query->select('c.title AS category, c.alias AS category_alias, c.access AS category_access');
			$query->join('LEFT', '#__categories AS c on c.id = sd.catid');

			// Join on user table.
			$query->select('u.name AS author');
			$query->join('LEFT', '#__users AS u on u.id = sd.created_by');

			// Join on contact table
			$subQuery = $this->_db->getQuery(true);
			$subQuery->select('contact.user_id, MAX(contact.id) AS id, contact.language');
			$subQuery->from('#__contact_details AS contact');
			$subQuery->where('contact.published = 1');
			$subQuery->group('contact.user_id, contact.language');
			$query->select('contact.id as contactid' );
			$query->join('LEFT', '(' . $subQuery . ') AS contact ON contact.user_id = sd.created_by');

			// Join over the categories to get parent category titles
			$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
			$query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

			$query->where('sd.id = ' . (int) $pk);

			// Join to check for category published state in parent categories up the tree
			// If all categories are published, badcats.id will be null, and we just use the article state
			$subquery = ' (SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
			$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
			$subquery .= 'WHERE parent.extension = ' . $this->_db->quote('com_sphinxdoc');
			$subquery .= ' AND parent.published <= 0 GROUP BY cat.id)';
			$query->join('LEFT OUTER', $subquery . ' AS badcats ON badcats.id = c.id');

			$this->_db->setQuery($query);

			//$data = $db->loadObject();

			if (!$this->item = $this->_db->loadObject())
			{
				$this->setError($this->_db->getError());
			}
			else
			{
				// Load the JSON string
				$params = new JRegistry;
				$params->loadJSON($this->item->params);
				$this->item->params = $params;

				// Merge global params with item params
				$params = clone $this->getState('params');
				$params->merge($this->item->params);
				$this->item->params = $params;
			}
		}
		error_log('models.sphinxdocs return item '.$this->item->alias);
		return $this->item;
	}
}
