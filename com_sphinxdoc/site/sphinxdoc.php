<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport('joomla.application.component.controller');

// Include dependancies
require_once JPATH_COMPONENT.DS.'helpers'.DS.'route.php';
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sphinxdoc'.DS.'helpers'.DS.'sphinxdoc.php';
//require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'icon.php';

// Get an instance of the controller prefixed by SphinxDoc
$controller = JController::getInstance('SphinxDoc');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
