<?php
/**
 * Webcam Gadget
 *
 * @category   Gadget
 * @package    Webcam
 * @author     Jonathan Hernandez <ion@suavizado.com>
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class WebcamHTML extends Jaws_GadgetHTML
{
    /**
     * Calls Display function if no is specified
     *
     * @access  public
     * @return  string  XHTML content
     */
    function DefaultAction()
    {
        $layoutGadget = $GLOBALS['app']->LoadGadget('Webcam', 'LayoutHTML');
        return $layoutGadget->Display();
    }
}