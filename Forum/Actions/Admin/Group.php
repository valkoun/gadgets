<?php
/**
 * Forum Admin Gadget
 *
 * @category   GadgetAdmin
 * @package    Forum
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class Forum_Actions_Admin_Group extends ForumAdminHTML
{
    /**
     * Show a form to edit a given group
     *
     * @access  public
     * @return  string  XHTML template content
     */
    function GetGroupUI()
    {
        $this->CheckPermission('default');
        $tpl = new Jaws_Template('gadgets/Forum/templates/');
        $tpl->Load('AdminGroupUI.html');
        $tpl->SetBlock('GroupsUI');

        $title =& Piwi::CreateWidget('Entry', 'title', '');
        $title->SetID('title');
        $tpl->SetVariable('lbl_title', _t('GLOBAL_TITLE'));
        $tpl->SetVariable('title', $title->Get());

        $description =& Piwi::CreateWidget('TextArea', 'description', '');
        $description->SetID('description');
        $tpl->SetVariable('lbl_description', _t('GLOBAL_DESCRIPTION'));
        $tpl->SetVariable('description', $description->Get());

        $fasturl =& Piwi::CreateWidget('Entry', 'fast_url', '');
        $fasturl->SetID('fast_url');
        $tpl->SetVariable('lbl_fast_url', _t('FORUM_FASTURL'));
        $tpl->SetVariable('fast_url', $fasturl->Get());

        $order =& Piwi::CreateWidget('Combo', 'order');
        $order->SetID('order');
        $tpl->SetVariable('lbl_order', _t('FORUM_ORDER'));
        $tpl->SetVariable('order', $order->Get());

        $locked =& Piwi::CreateWidget('Combo', 'locked');
        $locked->SetID('locked');
        $locked->AddOption(_t('GLOBAL_NO'),  0);
        $locked->AddOption(_t('GLOBAL_YES'), 1);
        $locked->SetDefault(0);
        $tpl->SetVariable('lbl_locked', _t('FORUM_LOCKED'));
        $tpl->SetVariable('locked', $locked->Get());

        $published =& Piwi::CreateWidget('Combo', 'published');
        $published->SetID('published');
        $published->AddOption(_t('GLOBAL_NO'),  0);
        $published->AddOption(_t('GLOBAL_YES'), 1);
        $published->SetDefault(1);
        $tpl->SetVariable('lbl_published', _t('GLOBAL_PUBLISHED'));
        $tpl->SetVariable('published', $published->Get());

        $tpl->ParseBlock('GroupsUI');
        return $tpl->Get();
    }

}