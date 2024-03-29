<?php
/**
 * Blocks - URL List gadget hook
 *
 * @category   GadgetHook
 * @package    Blocks
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2008-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class BlocksURLListHook
{
    /**
     * Returns an array with all available items the Menu gadget 
     * can use
     *
     * @access  public
     * @return  array   URLs array
     */
    function Hook()
    {
        $urls = array();
        //Blocks model
        $model  = $GLOBALS['app']->loadGadget('Blocks', 'Model');
        $blocks = $model->GetBlocks(true);
        if (!Jaws_Error::IsError($blocks)) {
            $max_size = 20;
            foreach ($blocks as $block) {
                $url = $GLOBALS['app']->Map->GetURLFor('Blocks', 'ViewBlock', array('id' => $block['id']));
                $urls[] = array('url'   => $url,
                                'title' => ($GLOBALS['app']->UTF8->strlen($block['title']) > $max_size)?
                                            $GLOBALS['app']->UTF8->substr($block['title'], 0, $max_size) . '...' :
                                            $block['title']);
            }
        }

        return $urls;
    }
}
