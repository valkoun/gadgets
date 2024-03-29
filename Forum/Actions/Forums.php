<?php
/**
 * Forum Core Gadget
 *
 * @category   Gadget
 * @package    Forum
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class Forum_Actions_Forums extends ForumHTML
{
    /**
     * Display groups and forums
     *
     * @access  public
     * @return  string  XHTML template content
     */
    function Forums()
    {
        $gModel = $GLOBALS['app']->LoadGadget('Forum', 'Model', 'Groups');
        $groups = $gModel->GetGroups(true);
        if (Jaws_Error::IsError($groups) || empty($groups)) {
            return false;
        }

        $objDate = $GLOBALS['app']->loadDate();
        $tpl = new Jaws_Template('gadgets/Forum/templates/');
        $tpl->Load('Forums.html');
        $tpl->SetBlock('forums');

        $tpl->SetVariable('title', _t('FORUM_NAME'));
        $tpl->SetVariable('url', $GLOBALS['app']->Map->GetURLFor('Forum', 'Forums'));

        foreach ($groups as $group) {
            $tpl->SetBlock('forums/group');
            $tpl->SetVariable('title', $group['title']);
            $tpl->SetVariable('lbl_topics', _t('FORUM_TOPICS'));
            $tpl->SetVariable('lbl_posts', _t('FORUM_POSTS'));
            $tpl->SetVariable('lbl_lastpost', _t('FORUM_LASTPOST'));

            $fModel = $GLOBALS['app']->LoadGadget('Forum', 'Model', 'Forums');
            $forums = $fModel->GetForums($group['id'], true, true);
            if (Jaws_Error::IsError($forums)) {
                continue;
            }

            foreach ($forums as $forum) {
                $tpl->SetBlock('forums/group/forum');
                $tpl->SetVariable('icon', '');
                $tpl->SetVariable('status', _t('FORUM_LOCKED'));
                $tpl->SetVariable('title', $forum['title']);
                $tpl->SetVariable('url', $GLOBALS['app']->Map->GetURLFor('Forum',
                                                                         'Topics', array('id' => $forum['id']))
                );
                $tpl->SetVariable('description', $forum['description']);
                $tpl->SetVariable('topics', $forum['topics']);
                $tpl->SetVariable('posts', $forum['posts']);

                // last post
                if (!empty($forum['last_post_id'])) {
                    $tpl->SetBlock('forums/group/forum/lastpost');
                    $tpl->SetVariable('postedby_lbl',_t('FORUM_POSTEDBY'));

                    $tpl->SetVariable('username', $forum['username']);
                    $tpl->SetVariable('nickname', $forum['nickname']);
                    $tpl->SetVariable('user_url',
                                      $GLOBALS['app']->Map->GetURLFor('Users', 'Profile'));
                    $tpl->SetVariable('lastpost_lbl',_t('FORUM_LASTPOSTED'));
                    $tpl->SetVariable('lastpost_date', $objDate->Format($forum['last_post_time']));
                    $tpl->SetVariable('lastpost_url',
                                      $GLOBALS['app']->Map->GetURLFor('Forum',
                                                                      'Forum', array('id' => $forum['id']))
                    );
                    $tpl->ParseBlock('forums/group/forum/lastpost');
                }

                $tpl->ParseBlock('forums/group/forum');
            }

            $tpl->ParseBlock('forums/group');
        }

        $tpl->ParseBlock('forums');
        return $tpl->Get();
    }

}