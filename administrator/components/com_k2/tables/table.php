<?php
/**
 * @version     2.9.x
 * @package     K2
 * @author      JoomlaWorks https://www.joomlaworks.net
 * @copyright   Copyright (c) 2006 - 2018 JoomlaWorks Ltd. All rights reserved.
 * @license     GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

class K2Table extends JTable
{
    public function load($keys = null, $reset = true)
    {
        if (K2_JVERSION == '15')
        {
            return parent::load($keys);
        }
        else
        {
            return parent::load($keys, $reset);
        }
    }

}
