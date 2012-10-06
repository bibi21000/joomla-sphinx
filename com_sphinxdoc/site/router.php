<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_sphinxdoc component
 *
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function SphinxdocBuildRoute(&$query)
{
       $segments = array();
       if(isset($query['view']))
       {
                $segments[] = $query['view'];
                unset( $query['view'] );
       }
       if(isset($query['id']))
       {
                $segments[] = $query['id'];
                unset( $query['id'] );
       };
       if(isset($query['page']))
       {
                $segments[] = $query['page'];
                unset( $query['page'] );
       };
       return $segments;
}



/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function SphinxdocParseRoute($segments)
{
       $vars = array();
       $count = count( $segments );
       switch($segments[0])
       {
               case 'categories':
                       $vars['view'] = 'categories';
                       break;
               case 'category':
                       $vars['view'] = 'category';
                       $id = explode( ':', $segments[1] );
                       $vars['id'] = (int) $id[0];
                       break;
               case 'documentation':
                       $vars['view'] = 'documentation';
                       $id = explode( ':', $segments[1] );
                       $vars['id'] = (int) $id[0];
                       if(isset($segments[2]))
					   {
						   $page = explode( ':', $segments[2] );
						   $vars['page'] = $page[0];
					   };

                       break;
       }
       return $vars;
}
