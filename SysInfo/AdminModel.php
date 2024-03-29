<?php
/**
 * SysInfo Admin Gadget
 *
 * @category   GadgetModel
 * @package    SysInfo
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2008-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/lesser.html
 */
require_once JAWS_PATH . 'gadgets/SysInfo/Model.php';

class SysInfoAdminModel extends SysInfoModel
{
    /**
     * Installs the gadget
     *
     * @access  public
     * @return  mixed   True on successful installation, Jaws_Error otherwise
     */
    function InstallGadget()
    {
        return true;
    }

    /**
     * Updates the gadget
     *
     * @access  public
     * @param   string  $old    Current version (in registry)
     * @param   string  $new    New version (in the $gadgetInfo file)
     * @return  mixed   True on success, Jaws_Error otherwise
     */
    function UpdateGadget($old, $new)
    {
        // Registry keys
        $GLOBALS['app']->Registry->DeleteKey('/gadgets/SysInfo/frontend_avail');

        // ACL keys
        $GLOBALS['app']->ACL->NewKey('/ACL/gadgets/SysInfo/SysInfo',  'false');
        $GLOBALS['app']->ACL->NewKey('/ACL/gadgets/SysInfo/PHPInfo',  'false');
        $GLOBALS['app']->ACL->NewKey('/ACL/gadgets/SysInfo/JawsInfo', 'false');
        $GLOBALS['app']->ACL->NewKey('/ACL/gadgets/SysInfo/DirInfo',  'false');

        return true;
    }

}