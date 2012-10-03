<?php
// No direct access to this file
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * SphinxDoc component helper.
 */
abstract class SphinxDocHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu)
    {
        JSubMenuHelper::addEntry(JText::_('COM_SPHINXDOC_SUBMENU_MESSAGES'), 'index.php?option=com_sphinxdoc', $submenu == 'messages');
        JSubMenuHelper::addEntry(JText::_('COM_SPHINXDOC_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_sphinxdoc', $submenu == 'categories');
        // set some global property
        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-sphinxdoc {background-image: url(../media/com_sphinxdoc/images/sphinx-48x48.png);}');
        if ($submenu == 'categories')
        {
            $document->setTitle(JText::_('COM_SPHINXDOC_ADMINISTRATION_CATEGORIES'));
        }
    }
    /**
     * Get the actions
     */
    public static function getActions($messageId = 0)
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        if (empty($messageId)) {
            $assetName = 'com_sphinxdoc';
        }
        else {
            $assetName = 'com_sphinxdoc.message.'.(int) $messageId;
        }
        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
        );
        foreach ($actions as $action) {
            $result->set($action,   $user->authorise($action, $assetName));
        }
        return $result;
    }

    function getHomePath() {
        // Params
        $paramsC = &JComponentHelper::getParams( 'com_sphinxdoc' );
        // Folder where to stored documentations
        $home = $paramsC->get( 'home', '_sphinxdocs' );
        // Path of preview and play
        $home = JPath::clean($home);
        //$path['home']       = JPATH_ROOT .  DS . $home;
        return JPATH_ROOT .  DS . $home;
    }

    function updateImg( $html, $directory ) {
        //update the img src
        $dom = new DOMDocument;
        //Parse the HTML. The @ is used to suppress any parsing errors
        //that will be thrown if the $html string isn't valid XHTML.
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        //Get all links. You could also use any other tag name here,
        //like 'img' or 'table', to extract other tags.
        $imgs = $dom->getElementsByTagName('img');
        // Params
        $paramsC = &JComponentHelper::getParams( 'com_sphinxdoc' );
        // Folder where to stored documentations
        $home = $paramsC->get( 'home', '_sphinxdocs' );
        $newpath = "/".$home."/".$directory."/";
        foreach ($imgs as $img){
            $newimg = $newpath.$img->getAttribute('src');
            $img->setAttribute('src', $newimg);
        }
        return $dom->saveHTML();
    }

}
