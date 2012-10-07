<?php
/**
 * Quotes Gadget
 *
 * @category   GadgetModel
 * @package    Quotes
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2007-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class QuotesModel extends Jaws_Model
{
    /**
     * Retrieve quote
     *
     * @access  public
     * @return  array   An array of quote information and Jaws_Error on error
     */
    function GetQuote($id)
    {
        $sql = '
            SELECT  [id], [gid], [title], [quotation], [quote_type],
                    [rank], [start_time], [stop_time], [show_title], [published]
            FROM [[quotes]]
            WHERE [id] = {id}';

        $params       = array();
        $params['id'] = $id;
        $types = array('integer', 'integer', 'text', 'text', 'integer',
                       'integer', 'timestamp', 'timestamp', 'boolean', 'boolean');
        $res = $GLOBALS['db']->queryRow($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return new Jaws_Error($res->getMessage(), 'SQL');
        }

        return $res;
    }

    /**
     * Retrieve quotes
     *
     * @access  public
     * @return  array   An array of available quotes and Jaws_Error on error
     */
    function GetQuotes($id = -1, $gid = -1, $limit = 0, $offset = null)
    {
        $sql = '
            SELECT
                [id], [gid], [title], [quotation], [quote_type],
                [rank], [start_time], [stop_time], [show_title], [published]
            FROM 
                [[quotes]] ';

        if (($id != -1) && ($gid != -1)) {
            $sql.= '
                WHERE [[quotes]].[id] = {id} AND [[quotes]].[gid] = {gid}
                ORDER BY [[quotes]].[id] ASC';
        } elseif ($gid != -1) {
            $sql.= '
                WHERE [[quotes]].[gid] = {gid}
                ORDER BY [[quotes]].[id] ASC';
        } elseif ($id != -1) {
            $sql.= '
                WHERE [id] = {id}
                ORDER BY [id] ASC';
        } else {
            $sql.= '
                ORDER BY [id] ASC';
        }

        $params        = array();
        $params['id']  = $id;
        $params['gid'] = $gid;

        if (!empty($limit)) {
            $res = $GLOBALS['db']->setLimit($limit, $offset);
            if (Jaws_Error::IsError($res)) {
                return new Jaws_Error($rs->getMessage(), 'SQL');
            }
        }

        $types = array('integer', 'integer', 'text', 'text', 'integer',
                       'integer', 'timestamp', 'timestamp', 'boolean', 'boolean');
        $res = $GLOBALS['db']->queryAll($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return new Jaws_Error($res->getMessage(), 'SQL');
        }

        return $res;
    }

    /**
     * Get information of a quote group
     *
     * @access  public
     * @param   int     $gid    Group ID
     * @return  array   Group information and Jaws_Error on error
     */
    function GetGroup($gid)
    {
        $sql = '
            SELECT
                [id], [title], [view_mode], [view_type], [show_title], [limit_count], [random], [published]
            FROM [[quotes_groups]]
            WHERE [id] = {gid}';
        $params        = array();
        $params['gid'] = $gid;
        $types = array('integer', 'text', 'integer', 'integer', 'boolean', 'integer', 'boolean', 'boolean');
        $res = $GLOBALS['db']->queryRow($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return new Jaws_Error($res->getMessage(), 'SQL');
        }

        return $res;
    }

    /**
     * Retrieve groups
     *
     * @access  public
     * @param
     * @param
     * @return  array   An array of available quotes groups and Jaws_Error on error
     */
    function GetGroups($gid = -1, $id = -1)
    {
        $sql = '
            SELECT
                [id], [title], [view_mode], [view_type], [show_title], [limit_count], [random], [published]
            FROM
                [[quotes_groups]]';

        if (($gid != -1) && ($id != -1)) {
            $sql.= '
                INNER JOIN [[quotes]] ON [[quotes_groups]].[id] = [[quotes]].[gid]
                WHERE [[quotes_groups]].[id] = {gid} AND [[quotes]].[id] = {id}
                ORDER BY [[quotes_groups]].[id] ASC';
        } elseif ($id != -1) {
            $sql.= '
                INNER JOIN [[quotes]] ON [[quotes_groups]].[id] = [[quotes]].[gid]
                WHERE [[quotes]].[id] = {id}
                ORDER BY [[quotes_groups]].[id] ASC';
        } elseif ($gid != -1) {
            $sql.= '
                WHERE [id] = {gid}
                ORDER BY [id] ASC';
        } else {
            $sql.= '
                ORDER BY [id] ASC';
        }

        $params        = array();
        $params['gid'] = $gid;
        $params['id']  = $id;

        $types = array('integer', 'text', 'integer', 'integer', 'boolean', 'integer', 'boolean', 'boolean');
        $res = $GLOBALS['db']->queryAll($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return new Jaws_Error($res->getMessage(), 'SQL');
        }

        return $res;
    }

    /**
     * Retrieve quotes that can be published
     *
     * @access  public
     * @return  array   An array of available quotes and Jaws_Error on error
     */
    function GetPublishedQuotes($gid, $limit = null, $randomly = false)
    {
        $sql = '
            SELECT
                [id], [title], [quotation], [rank], [show_title]
            FROM [[quotes]]
            WHERE
                ([gid] = {gid})
              AND
                ([published] = {published})
              AND
                (([start_time] IS NULL) OR ({now} >= [start_time]))
              AND
                (([stop_time] IS NULL) OR ({now} <= [stop_time]))';

        if ($randomly) {
            $GLOBALS['db']->dbc->loadModule('Function', null, true);
            $rand = $GLOBALS['db']->dbc->function->random();
            $sql.= '
                ORDER BY '.$rand;
        } else {
            $sql.= '
                ORDER BY [rank] ASC, [id] DESC';
        }

        $params  = array();
        $params['gid']       = $gid;
        $params['published'] = true;
        $params['now']       = $GLOBALS['db']->Date();

        if (!empty($limit)) {
            $res = $GLOBALS['db']->setLimit($limit);
            if (Jaws_Error::IsError($res)) {
                return new Jaws_Error($res->getMessage(), 'SQL');
            }
        }

        $types = array('integer', 'text', 'text', 'integer', 'boolean');
        $res = $GLOBALS['db']->queryAll($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return false;
        }
        return $res;
    }

    /**
     * Retrieve recent quotes
     *
     * @access  public
     * @return  array   An array of available quotes and Jaws_Error on error
     */
    function GetRecentQuotes($limit = null, $randomly = false)
    {
        $sql = '
            SELECT
                [id], [title], [quotation], [rank], [show_title]
            FROM [[quotes]]
            WHERE
                ([published] = {published})
              AND
                (([start_time] IS NULL) OR ({now} >= [start_time]))
              AND
                (([stop_time] IS NULL) OR ({now} <= [stop_time]))';

        if ($randomly) {
            $GLOBALS['db']->dbc->loadModule('Function', null, true);
            $rand = $GLOBALS['db']->dbc->function->random();
            $sql.= '
                ORDER BY '.$rand;
        } else {
            $sql.= '
                ORDER BY [id] DESC';
        }

        $params = array();
        $params['published'] = true;
        $params['now']       = $GLOBALS['db']->Date();

        if (!empty($limit)) {
            $res = $GLOBALS['db']->setLimit($limit);
            if (Jaws_Error::IsError($res)) {
                return new Jaws_Error($res->getMessage(), 'SQL');
            }
        }

        $types = array('integer', 'text', 'text', 'integer', 'boolean');
        $res = $GLOBALS['db']->queryAll($sql, $params, $types);
        if (Jaws_Error::IsError($res)) {
            return false;
        }
        return $res;
    }

}