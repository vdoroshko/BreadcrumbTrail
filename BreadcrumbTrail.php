<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Breadcrumb trail management class
 *
 * PHP version 4
 *
 * Copyright (c) 2005-2017, Vitaly Doroshko
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   HTML
 * @package    BreadcrumbTrail
 * @author     Vitaly Doroshko <vdoroshko@mail.ru>
 * @copyright  2005-2017 Vitaly Doroshko
 * @license    http://opensource.org/licenses/BSD-3-Clause
 *             BSD 3-Clause License
 * @version    1.0
 * @link       https://github.com/vdoroshko/BreadcrumbTrail
 * @since      1.0
 */
require_once 'PEAR.php';

// {{{ constants and globals
// {{{ error codes

/**
 * Unknown error
 */
define('BREADCRUMBTRAIL_ERROR', -1);

/**
 * An argument passed is not an object of class BreadcrumbItem
 */
define('BREADCRUMBTRAIL_ERROR_INVALID_ITEM', -2);

/**
 * Breadcrumb trail is empty
 */
define('BREADCRUMBTRAIL_ERROR_EMPTY', -3);

// }}}
// }}}

// {{{ classes
// {{{ class BreadcrumbTrail

/**
 * Breadcrumb trail management class
 *
 * @category   HTML
 * @package    BreadcrumbTrail
 * @author     Vitaly Doroshko <vdoroshko@mail.ru>
 * @copyright  2005-2017 Vitaly Doroshko
 * @license    http://opensource.org/licenses/BSD-3-Clause
 *             BSD 3-Clause License
 * @link       https://github.com/vdoroshko/BreadcrumbTrail
 * @since      1.0
 */
class BreadcrumbTrail
{
    // {{{ properties

    /**
     * Array containing the breadcrumb trail items
     *
     * @var    array
     * @access private
     * @since  1.0
     */
    var $_breadcrumbItems;

    // }}}
    // {{{ constructor

    /**
     * Constructs a new BreadcrumbTrail object
     *
     * @access public
     */
    function BreadcrumbTrail()
    {
        $this->_breadcrumbItems = array();
    }

    // }}}
    // {{{ push()

    /**
     * Pushes an item onto the end of the breadcrumb trail
     *
     * @param  object  $breadcrumbItem The BreadcrumbItem object to push onto the breadcrumb trail
     * @return mixed   The new number of items in the breadcrumb trail or a
     *                 BreadcrumbTrail_Error object on error
     * @access public
     * @since  1.0
     */
    function push($breadcrumbItem)
    {
        if (!is_a($breadcrumbItem, 'BreadcrumbItem')) {
            return BreadcrumbTrail::raiseError(BREADCRUMBTRAIL_ERROR_INVALID_ITEM);
        }

        return array_push($this->_breadcrumbItems, $breadcrumbItem);
    }

    // }}}
    // {{{ pop()

    /**
     * Pops an item from the end of the breadcrumb trail and returns it
     *
     * @return mixed   A BreadcrumbItem object from the end of the breadcrumb
     *                 trail or BreadcrumbTrail_Error object on error
     * @access public
     * @since  1.0
     */
    function pop()
    {
        if (empty($this->_breadcrumbItems)) {
            return BreadcrumbTrail::raiseError(BREADCRUMBTRAIL_ERROR_EMPTY);
        }

        return array_pop($this->_breadcrumbItems);
    }

    // }}}
    // {{{ top()

    /**
     * Returns an item from the end of the breadcrumb trail without removing it
     *
     * @return mixed   A BreadcrumbItem object from the end of the breadcrumb
     *                 trail or BreadcrumbTrail_Error object on error
     * @access public
     * @since  1.0
     */
    function top()
    {
        if (empty($this->_breadcrumbItems)) {
            return BreadcrumbTrail::raiseError(BREADCRUMBTRAIL_ERROR_EMPTY);
        }

        return $this->_breadcrumbItems[count($this->_breadcrumbItems) - 1];
    }

    // }}}
    // {{{ count()

    /**
     * Returns the number of items in the breadcrumb trail
     *
     * @return integer The number of items in the breadcrumb trail
     * @access public
     * @since  1.0
     */
    function count()
    {
        return count($this->_breadcrumbItems);
    }

    // }}}
    // {{{ isEmpty()

    /**
     * Checks if the breadcrumb trail is empty
     *
     * @return boolean true if the breadcrumb trail is empty or false otherwise
     * @access public
     * @since  1.0
     */
    function isEmpty()
    {
        return empty($this->_breadcrumbItems);
    }

    // }}}
    // {{{ contains()

    /**
     * Checks if the breadcrumb trail contains the given item
     *
     * @param  object  $breadcrumbItem The BreadcrumbItem object to look for
     * @return mixed   true if the breadcrumb trail contains the given item, false otherwise,
     *                 a BreadcrumbTrail_Error object on error
     * @access public
     * @see    BreadcrumbItem::equals()
     * @since  1.0
     */
    function contains($breadcrumbItem)
    {
        for ($i = 0; $i < count($this->_breadcrumbItems); $i++) {
            $result = $this->_breadcrumbItems[$i]->equals($breadcrumbItem);
            if (BreadcrumbTrail::isError($result)) {
                return $result;
            }

            if ($result) {
                return true;
            }
        }

        return false;
    }

    // }}}
    // {{{ rewind()

    /**
     * Sets the internal pointer in the breadcrumb trail to the first item and
     * returns it
     *
     * @return mixed   A BreadcrumbItem object from the beginning of the breadcrumb
     *                 trail or false if the breadcrumb trail is empty
     * @access public
     * @since  1.0
     */
    function rewind()
    {
        return reset($this->_breadcrumbItems);
    }

    // }}}
    // {{{ valid()

    /**
     * Checks if the current position of the internal pointer in the breadcrumb
     * trail is valid
     *
     * @return boolean true if the current position of the internal pointer in the
     *                 breadcrumb trail is valid or false otherwise
     * @access public
     * @since  1.0
     */
    function valid()
    {
        return key($this->_breadcrumbItems) !== null;
    }

    // }}}
    // {{{ key()

    /**
     * Returns the current position of the internal pointer in the breadcrumb
     * trail
     *
     * @return mixed   The current position of the internal pointer in the
     *                 breadcrumb trail or false if the internal pointer points
     *                 beyond the end of the breadcrumb trail or the breadcrumb
     *                 trail is empty
     * @access public
     * @since  1.0
     */
    function key()
    {
        return key($this->_breadcrumbItems);
    }

    // }}}
    // {{{ current()

    /**
     * Returns an item from the breadcrumb trail in current position of the
     * internal pointer
     *
     * @return mixed   A BreadcrumbItem object in current position of the internal
     *                 pointer or false if the internal pointer points beyond the
     *                 end of the breadcrumb trail or the breadcrumb trail is empty
     * @access public
     * @since  1.0
     */
    function current()
    {
        return current($this->_breadcrumbItems);
    }

    // }}}
    // {{{ next()

    /**
     * Advances the internal pointer in the breadcrumb trail to the next item and
     * returns it
     *
     * @return mixed   A BreadcrumbItem object in the next position of the
     *                 internal pointer or false if there are no more items
     * @access public
     * @since  1.0
     */
    function next()
    {
        return next($this->_breadcrumbItems);
    }

    // }}}
    // {{{ raiseError()

    /**
     * Constructs a BreadcrumbTrail_Error object with an error code and an error
     * message
     *
     * @param  integer $code The error code
     * @return object  A new BreadcrumbTrail_Error object
     * @access public
     * @static
     * @since  1.0
     */
    function &raiseError($code)
    {
        return new BreadcrumbTrail_Error($code);
    }

    // }}}
    // {{{ isError()

    /**
     * Determines if a variable is a BreadcrumbTrail_Error object
     *
     * @param  object  $object The variable to check
     * @return boolean true if the variable is a BreadcrumbTrail_Error object or
     *                 false otherwise
     * @access public
     * @static
     * @since  1.0
     */
    function isError($object)
    {
        return is_a($object, 'BreadcrumbTrail_Error');
    }

    // }}}
    // {{{ errorMessage()

    /**
     * Returns a textual error message for an error code
     *
     * @param  integer $code The error code
     * @return string  The error message
     * @access public
     * @static
     * @since  1.0
     */
    function errorMessage($code)
    {
        static $errorMessages;

        if (empty($errorMessages)) {
            $errorMessages = array(
                BREADCRUMBTRAIL_ERROR              => 'unknown error',
                BREADCRUMBTRAIL_ERROR_INVALID_ITEM => 'argument 1 must be an object of class BreadcrumbItem',
                BREADCRUMBTRAIL_ERROR_EMPTY        => 'breadcrumb trail is empty'
            );
        }

        return isset($errorMessages[(integer)$code]) ? $errorMessages[(integer)$code] : $errorMessages[BREADCRUMBTRAIL_ERROR];
    }

    // }}}
}

// }}}
// {{{ class BreadcrumbItem

/**
 * A breadcrumb item class
 *
 * @category   HTML
 * @package    BreadcrumbTrail
 * @author     Vitaly Doroshko <vdoroshko@mail.ru>
 * @copyright  2005-2017 Vitaly Doroshko
 * @license    http://opensource.org/licenses/BSD-3-Clause
 *             BSD 3-Clause License
 * @link       https://github.com/vdoroshko/BreadcrumbTrail
 * @since      1.0
 */
class BreadcrumbItem
{
    // {{{ properties

    /**
     * URL of an item
     *
     * @var    string
     * @access private
     * @since  1.0
     */
    var $_url;

    /**
     * Short title of an item
     *
     * @var    string
     * @access private
     * @since  1.0
     */
    var $_shortTitle;

    /**
     * Long title of an item
     *
     * @var    string
     * @access private
     * @since  1.0
     */
    var $_longTitle;

    /**
     * Item description
     *
     * @var    string
     * @access private
     * @since  1.0
     */
    var $_description;

    // }}}
    // {{{ constructor

    /**
     * Constructs a new BreadcrumbItem object
     *
     * @param  string  $url The URL of the item
     * @param  string  $shortTitle (optional) The short title of an item
     * @param  string  $longTitle (optional) The long title of an item
     * @param  string  $description (optional) The item description
     * @access public
     */
    function BreadcrumbItem($url, $shortTitle = '', $longTitle = '', $description = '')
    {
        $this->_url         = (string)$url;
        $this->_shortTitle  = (string)$shortTitle;
        $this->_longTitle   = (string)$longTitle;
        $this->_description = (string)$description;
    }

    // }}}
    // {{{ getURL()

    /**
     * Returns the URL of an item
     *
     * @return string  The URL of an item
     * @access public
     * @since  1.0
     */
    function getURL()
    {
        return $this->_url;
    }

    // }}}
    // {{{ setURL()

    /**
     * Sets the URL of an item
     *
     * @param  string  $url The URL of an item
     * @return void
     * @access public
     * @since  1.0
     */
    function setURL($url)
    {
        $this->_url = (string)$url;
    }

    // }}}
    // {{{ getShortTitle()

    /**
     * Returns the short title of an item
     *
     * @return string  The short title of an item
     * @access public
     * @since  1.0
     */
    function getShortTitle()
    {
        return $this->_shortTitle;
    }

    // }}}
    // {{{ setShortTitle()

    /**
     * Sets the short title of an item
     *
     * @param  string  $shortTitle The short title of an item
     * @return void
     * @access public
     * @since  1.0
     */
    function setShortTitle($shortTitle)
    {
        $this->_shortTitle = (string)$shortTitle;
    }

    // }}}
    // {{{ getLongTitle()

    /**
     * Returns the long title of an item
     *
     * @return string  The long title of an item
     * @access public
     * @since  1.0
     */
    function getLongTitle()
    {
        return $this->_longTitle;
    }

    // }}}
    // {{{ setLongTitle()

    /**
     * Sets the long title of an item
     *
     * @param  string  $longTitle The long title of an item
     * @return void
     * @access public
     * @since  1.0
     */
    function setLongTitle($longTitle)
    {
        $this->_longTitle = (string)$longTitle;
    }

    // }}}
    // {{{ getDescription()

    /**
     * Returns the description of an item
     *
     * @return string  The description of an item
     * @access public
     * @since  1.0
     */
    function getDescription()
    {
        return $this->_descrption;
    }

    // }}}
    // {{{ setDescription()

    /**
     * Sets the description of an item
     *
     * @param  string  $description The descriptions of an item
     * @return void
     * @access public
     * @since  1.0
     */
    function setDescription($description)
    {
        $this->_description = (string)$description;
    }

    // }}}
    // {{{ equals()

    /**
     * Determines if the URL property of this object is equal to the URL property
     * of the given BreadcrumbTrail object
     *
     * @param  object  $breadcrumbItem The BreadcrumbItem object to compare with
     * @return mixed   true if the URL property of this object is equal to the URL
     *                 property of the given BreadcrumbTrail object, false
     *                 otherwise, a BreadcrumbTrail_Error object on error
     * @access public
     * @since  1.0
     */
    function equals($breadcrumbItem)
    {
        if (!is_a($breadcrumbItem, 'BreadcrumbItem')) {
            return BreadcrumbTrail::raiseError(BREADCRUMBTRAIL_ERROR_INVALID_ITEM);
        }

        return $this->_url == $breadcrumbItem->getURL();
    }

    // }}}
}

// }}}
// {{{ class BreadcrumbTrail_Error

/**
 * Class for reporting portable error messages
 *
 * @category   HTML
 * @package    BreadcrumbTrail
 * @author     Vitaly Doroshko <vdoroshko@mail.ru>
 * @copyright  2005-2017 Vitaly Doroshko
 * @license    http://opensource.org/licenses/BSD-3-Clause
 *             BSD 3-Clause License
 * @link       https://github.com/vdoroshko/BreadcrumbTrail
 * @since      1.0
 */
class BreadcrumbTrail_Error extends PEAR_Error
{
    // {{{ constructor

    /**
     * Constructs a new BreadcrumbTrail_Error object
     *
     * @param  integer $code The error code
     * @param  integer $mode (optional) The error mode
     * @param  mixed   $level (optional) The error level
     * @param  string  $debuginfo (optional) Additional debug info
     * @access public
     * @see    PEAR_Error
     */
    function BreadcrumbTrail_Error($code, $mode = null, $level = null, $debuginfo = null)
    {
        $this->PEAR_Error('BreadcrumbTrail Error: ' . BreadcrumbTrail::errorMessage($code), (integer)$code, $mode, $level, $debuginfo);
    }

    // }}}
}

// }}}
// }}}

?>
