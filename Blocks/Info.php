<?php
/**
 * Blocks Gadget Info
 *
 * @category   GadgetInfo
 * @package    Blocks
 * @author     Jonathan Hernandez <ion@suavizado.com>
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class BlocksInfo extends Jaws_GadgetInfo
{
    /**
     * Gadget version
     *
     * @var     string
     * @access  private
     */
    var $_Version = '0.3.0';

    /**
     * Gadget ACLs
     *
     * @var     array
     * @access  private
     */
    var $_ACLs = array(
        'AddBlock',
        'EditBlock',
        'DeleteBlock',
    );

}