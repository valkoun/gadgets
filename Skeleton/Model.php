<?php
/**
 * Skeleton Model 
 *
 * @category   GadgetModel
 * @package    Skeleton
 * @author     Jon Wood <jon@substance-it.co.uk>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class SkeletonModel extends Jaws_Model
{
    /**
     * Returns version of Jaws
     *
     * @access  public
     * @return  string  Jaws version
     */
    function GetJawsVersion()
    {
        return $GLOBALS['app']->Registry->Get('/version');
    }

}