<?php
/**
 * Quotes - URL List gadget hook
 *
 * @category   GadgetHook
 * @package    Quotes
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @copyright  2007-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class QuotesURLListHook
{
    /**
     * Returns an array with all available items the Menu gadget can use
     *
     * @access  public
     * @return  array   List of URLs
     */
    function Hook()
    {
        $urls   = array();
        $urls[] = array('url'   => $GLOBALS['app']->Map->GetURLFor('Quotes', 'RecentQuotes'),
                        'title' => _t('QUOTES_NAME'));

        $model  = $GLOBALS['app']->loadGadget('Quotes', 'Model');
        $groups = $model->GetGroups();
        if (!Jaws_Error::isError($groups)) {
            $max_size = 20;
            foreach ($groups as $group) {
                $url = $GLOBALS['app']->Map->GetURLFor('Quotes', 'ViewGroupQuotes', array('id' => $group['id']));
                $urls[] = array('url'   => $url,
                                'title' => ($GLOBALS['app']->UTF8->strlen($group['title']) > $max_size)?
                                            $GLOBALS['app']->UTF8->substr($group['title'], 0, $max_size).'...' :
                                            $group['title']);
            }
        }

        return $urls;
    }
}
