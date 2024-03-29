<?php
/**
 * ServerTime Gadget
 *
 * @category   Gadget
 * @package    ServerTime
 * @author     Jonathan Hernandez <ion@suavizado.com>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class ServerTimeHTML extends Jaws_GadgetHTML
{
    /**
     * Displays the server time
     *
     * @access  public
     * @return  string  XHTML template content
     */
    function DefaultAction()
    {
        $layoutGadget = $GLOBALS['app']->LoadGadget('ServerTime', 'LayoutHTML');
        return $layoutGadget->Display();
    }

}