<?php
/**
 * Phoo - Search gadget hook
 *
 * @category   GadgetHook
 * @package    Phoo
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @copyright  2007-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class PhooSearchHook
{
    /**
     * Gets the gadget's search fields
     *
     * @access  public
     * @return  array   search fields array
     */
    function GetSearchFields() {
        return array(
                    array('[name]', '[description]'),
                    array('pi.[title]', 'pi.[description]'),
                    );
    }

    /**
     * Returns an array with the results of a search
     *
     * @access  public
     * @param   string  $pSql  Prepared search (WHERE) SQL
     * @return  array   An array of entries that matches a certain pattern
     */
    function Hook($pSql = array())
    {
        $orderType = $GLOBALS['app']->Registry->Get('/gadgets/Phoo/albums_order_type');
        if (!in_array($orderType, array('createtime DESC',
                                        'createtime',
                                        'name DESC',
                                        'name',
                                        'id DESC',
                                        'id', )))
        {
            $orderType = 'name';
        }
        
        $params = array();
        $params['published'] = true;

        // Process Albums
        $sql = '
            SELECT
                [id],
                [name],
                [description],
                [createtime]
            FROM [[phoo_album]] pa
            WHERE [published] = {published}
            ';

        $sql .= ' AND ' . $pSql[0];
        $sql .= ' ORDER BY pa.['.$orderType .']';

        $result = $GLOBALS['db']->queryAll($sql, $params);
        if (Jaws_Error::isError($result)) {
            return array();
        }

        $entries = array();
        $date = $GLOBALS['app']->loadDate();
        foreach ($result as $r) {
            $entry = array();
            $entry['title']   = $r['name'];
            $entry['url']     = $GLOBALS['app']->Map->GetURLFor('Phoo', 'ViewAlbum',
                                                                array('id' => $r['id']));
            $entry['image']   = 'gadgets/Phoo/images/logo.png';
            $entry['snippet'] = $r['description'];
            $entry['date']    = $date->ToISO($r['createtime']);
            $stamp            = str_replace(array('-', ':', ' '), '', $r['createtime']);
            $entries[$stamp]  = $entry;
        }

        // Process Images
        $sql = '
            SELECT
                pi.[id],
                pi.[filename],
                pi.[title],
                pi.[description],
                pi.[createtime],
                [phoo_album_id]
            FROM [[phoo_image]] pi
            LEFT JOIN [[phoo_image_album]] pia ON pia.[phoo_image_id] = pi.[id]
            LEFT JOIN [[phoo_album]] pa ON pa.[id] = pia.[phoo_album_id]
            WHERE
                pi.[published] = {published}
              AND
                pa.[published] = {published}
            ';

        $sql .= ' AND ' . $pSql[1];
        $sql .= ' ORDER BY pi.[createtime] DESC';

        $result = $GLOBALS['db']->queryAll($sql, $params);
        if (Jaws_Error::isError($result)) {
            return array();
        }

        include_once JAWS_PATH . 'include/Jaws/Image.php';
        foreach ($result as $r) {
            $entry = array();
            $entry['title']   = $r['title'];
            $entry['url']     = $GLOBALS['app']->Map->GetURLFor('Phoo', 'ViewImage',
                                                                array('albumid' => $r['phoo_album_id'],
                                                                      'id'      => $r['id'],
                                                                      ));
            $path = substr($r['filename'], 0, strrpos($r['filename'], '/'));
            $file = basename($r['filename']);

            $entry['image']   = $GLOBALS['app']->getDataURL("phoo/$path/thumb/$file");
            $entry['snippet'] = $r['description'];
            $entry['date']    = $date->ToISO($r['createtime']);
            $stamp            = str_replace(array('-', ':', ' '), '', $r['createtime']);
            $entries[$stamp]  = $entry;
        }

        return $entries;
    }

}