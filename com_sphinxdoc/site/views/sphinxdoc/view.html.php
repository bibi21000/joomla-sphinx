<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * HTML View class for the SphinxDoc Component
 */
class SphinxDocViewSphinxDoc extends JView
{
	protected $print;
	protected $state;

    // Overwriting JView display method
    function display($tpl = null)
    {

		JHTML::stylesheet('media/com_sphinxdoc/css/site.stylesheet.css' );

        // Assign data to the view
        $this->item = $this->get('Item');
		error_log('item '.$this->item->id);

		//echo $this->item->id;
		$this->print	= JRequest::getBool('print');
		$this->state	= $this->get('State');
		$dispatcher	= JDispatcher::getInstance();

        // Create a shortcut for $item.
        $item = &$this->item;

        // Retrieve params
        $this->params = $this->get('State')->get('params');

        $homePath = SphinxDocHelper::getHomePath();
        $item->home = $homePath;

        //Load the HTML page
        $html = file_get_contents($homePath.DS.$item->directory.DS.$item->index, FILE_USE_INCLUDE_PATH);
        //Create a new DOM document
        $dom = new DOMDocument;

        //Parse the HTML. The @ is used to suppress any parsing errors
        //that will be thrown if the $html string isn't valid XHTML.
        @$dom->loadHTML($html);

        //Get all links. You could also use any other tag name here,
        //like 'img' or 'table', to extract other tags.
        $links = $dom->getElementsByTagName('a');

        $slug = $item->alias ? ($item->id.':'.$item->alias) : $item->id;
        //$slug   = $item->id;

		$item->defaultpage = "";

        $item->pages = array();
        //Iterate over the extracted links and display their URLs
        foreach ($links as $link){
			if ($item->defaultpage == "") :
				$item->defaultpage = $link->getAttribute('href');
			endif;

            // TODO: Change based on shownoauth
            $fulllink = JRoute::_(SphinxdocHelperRoute::getSphinxdocRoute($slug));

            $page = array(
                'fulllink' => $fulllink."&page=".$link->getAttribute('href'),
                'title' => $link->nodeValue
            );
            $item->pages[$link->getAttribute('href')] = $page;
        }
        $fulllink = JRoute::_(SphinxdocHelperRoute::getSphinxdocRoute($slug));
        $page = array(
                'fulllink' => $fulllink."&showall=1",
                'title' => JText::_('COM_SPHINXDOC_ALL_PAGES')
            );
        $item->pages['all'] = $page;

		$offset = $this->state->get('list.offset');

        $showall = JRequest::getInt('showall');
        $page = JRequest::getString('page');
        if ($page == "") :
            $page = $item->defaultpage;
        endif;
		//Create a context to use utf8
		$opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
		$context = stream_context_create($opts);
        if ($showall == 1) :
			$item->text = "";
            //Iterate over the extracted links and display their URLs
            foreach ($item->pages as $cle => $element){
                if ($cle != "all") :
					$temp = file_get_contents($this->item->home.DS.$this->item->directory.DS.$cle, FILE_USE_INCLUDE_PATH, $context);
                    $item->text = $item->text.$temp;
                endif;
            }
        else :
            //Load the HTML page
            $item->text = file_get_contents($this->item->home.DS.$this->item->directory.DS.$page, FILE_USE_INCLUDE_PATH, $context);
        endif;

		//
		// Process the content plugins.
		//
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$this->params, $offset));

		$item->event = new stdClass();
		$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.article', &$item, &$this->params, $offset));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Display the view
        parent::display($tpl);
    }
}
