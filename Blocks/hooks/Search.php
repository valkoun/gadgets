<?php
/**
 * Blocks - Search gadget hook
 *
 * @category   GadgetHook
 * @package    Blocks
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2008-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class BlocksSearchHook
{
    /**
     * Gets the gadget's search fields
     *
     * @access  public
     * @return  array   array of search fields
     */
    function GetSearchFields() {
        return array(
                    array('[title]', '[contents]'),
                    );
    }

    /**
     * Returns an array with the results of a search
     *
     * @access  public
     * @param   string  $pSql  Prepared search (WHERE) SQL
     * @return  array   An array of entries that matches a certain pattern
     */
    function Hook($pSql = '')
    {
        $sql = '
            SELECT
                [id], [title], [contents], [updatetime]
            FROM [[blocks]]
            ';

        $sql .= ' WHERE ' . $pSql;
        $sql .= ' ORDER BY [createtime] DESC';

        $result = $GLOBALS['db']->queryAll($sql);
        if (Jaws_Error::IsError($result)) {
            return array();
        }

        $date = $GLOBALS['app']->loadDate();
        $blocks = array();
        foreach ($result as $r) {
            $block = array();
            $block['title']   = $r['title'];
            $block['url']     = $GLOBALS['app']->Map->GetURLFor('Blocks', 'ViewBlock', array('id' => $r['id']));
            $block['image']   = 'gadgets/Blocks/images/logo.png';
            $block['snippet'] = $r['contents'];
            $block['date']    = $date->ToISO($r['updatetime']);
            $blocks[] = $block;
        }

        return $blocks;
    }

}