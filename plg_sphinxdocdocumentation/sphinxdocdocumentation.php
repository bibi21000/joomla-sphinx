<?php
/**
 * @package		Joomla.Plugin
 * @subpackage	Content.sphinxdocdocumentation
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE.DS.'components'.DS.'com_sphinxdoc'.DS.'helpers'.DS.'route.php';

class plgContentSphinxdocDocumentation extends JPlugin
{
	/**
	 * Plugin that loads module positions within content
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
		public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}


public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}
		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'sphinxdocdocumentation') === false) {
			return true;
		}

		// expression to search for (positions)
		$regex		= '/{sphinxdocdocumentation\s+(.*?)}/i';
		$style		= $this->params->def('style', 'none');
		$title		= null;

		// Find all instances of plugin and put in $matches for loadposition
		// $matches[0] is full pattern match, $matches[1] is the position
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {

				$matcheslist = explode(',', $match[1]);

				// We may not have a module style so fall back to the plugin default.
				if (!array_key_exists(1, $matcheslist)) {
					$matcheslist[1] = $style;
				}

				$documentation = trim($matcheslist[0]);
				$style    = trim($matcheslist[1]);

				$output = $this->_link($documentation, $style);
				// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
			}
		}
	}

	protected function _link($documentation, $style = 'none')
	{
		$document		= &JFactory::getDocument();
		$db 			= &JFactory::getDBO();

		$target='p';
		switch($target) {
			case 'b':
				$targetOutput = 'target="_blank" ';
			break;
			case 't':
				$targetOutput = 'target="_top" ';
			break;
			case 'p':
				$targetOutput = 'target="_parent" ';
			break;
			case 's':
				$targetOutput = 'target="_self" ';
			break;
			default:
				$targetOutput = '';
			break;
		}

		$query = 'SELECT sd.id, sd.documentation, sd.catid, sd.alias'
		. ' FROM #__sphinxdoc AS sd';

		if ((int)$documentation > 0) {
			$query .= ' WHERE sd.id = '.(int)$documentation;
		}


		$output = 'None';
		$db->setQuery($query);
		$items = $db->loadObjectList();
	//return empty($items);
		if (!empty($items)) {
			$output = '';
			foreach ($items as $item) {

				if (isset($item->id) && isset($item->alias)) {
					$slug = $item->alias ? ($item->id.':'.$item->alias) : $item->id;
					$fulllink = JRoute::_(SphinxdocHelperRoute::getSphinxdocRoute($slug));
					if (isset($item->documentation) && $item->documentation != '') {
						$textOutput = $item->documentation;
					} elseif (isset($item->alias) && $item->alias != '') {
						$textOutput = $item->alias;
					} else {
						//$textOutput = JText::_('PLG_CONTENT_PHOCADOWNLOAD_DOWNLOAD_FILE');
						$textOutput = "None";
					}
				$output .= '<a href="'. $fulllink.'"'.$targetOutput.'>'. $textOutput.'</a>';

				}
			}
			$output .= '';

		}
		return $output;
	}

}
