<?php
/**
 * Blocks Admin Gadget
 *
 * @category   GadgetAdmin
 * @package    Blocks
 * @author     Jonathan Hernandez <ion@suavizado.com>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class BlocksAdminHTML extends Jaws_GadgetHTML
{
    /**
     * Creates and prints the administration template
     *
     * @access  public
     * @return  string  XHTML Template content
     */
    function Admin()
    {
        $this->AjaxMe('script.js');

        $tpl = new Jaws_Template('gadgets/Blocks/templates/');
        $tpl->Load('AdminBlocks.html');
        $tpl->SetBlock('blocks');

        $tpl->SetVariable('base_script', BASE_SCRIPT);

        // Block List
        $model = $GLOBALS['app']->LoadGadget('Blocks', 'AdminModel');
        $blocks = $model->GetBlocks(false);
        $blocksCombo =& Piwi::CreateWidget('Combo', 'block_id');
        $blocksCombo->SetID('block_id');
        $blocksCombo->SetStyle('width: 100%; max-width: 210px; margin-bottom: 10px;');
        $blocksCombo->SetSize(16);
        $blocksCombo->AddEvent(ON_CHANGE, 'edit(this.value, \'' . _t('GLOBAL_EDIT') . '\');');
        $selected = 0;
        foreach ($blocks as $b) {
            $blocksCombo->AddOption($b['title'], $b['id']);
        }

        unset($blocks);
        $blocksCombo->SetDefault($selected);
        $tpl->SetVariable('block_list', $blocksCombo->Get());

        // New Button
        if ($this->GetPermission('AddBlock')) {
            $newButton =& Piwi::CreateWidget('Button', 'newButton', _t('BLOCKS_NEW'), STOCK_NEW);
            $newButton->AddEvent(ON_CLICK, 'createNewBlock(\'' . _t('BLOCKS_NEW') . '\');');
            $newButton->SetID('newButton');
            $tpl->SetVariable('new_button', $newButton->Get());
        } else {
            $tpl->SetVariable('new_button', '');
        }

        // Tabs titles
        $tpl->SetVariable('edit', _t('GLOBAL_EDIT'));
        $tpl->SetVariable('preview', _t('GLOBAL_PREVIEW'));

        // Edit form
        $idHidden =& Piwi::CreateWidget('HiddenEntry', 'id');
        $idHidden->SetID('hidden_id');
        $tpl->SetVariable('hidden_id', $idHidden->Get());
        $title =& Piwi::CreateWidget('Entry', 'title', '', _t('BLOCKS_TITLE'));
        $title->SetID('block_title');
        $title->SetStyle('width: 99%');
        $tpl->SetVariable('lbl_block_id', _t('GLOBAL_ID'));
        $tpl->SetVariable('block_title',  _t('BLOCKS_TITLE'));
        $tpl->SetVariable('title_field', $title->Get());

        $contents =& $GLOBALS['app']->LoadEditor('Blocks', 'block_contents');
        $contents->setID('block_contents');
        $contents->TextArea->SetStyle('width: 99%;');
        $contents->SetWidth('100%');

        $tpl->SetVariable('contents', _t('BLOCKS_CONTENT'));
        $tpl->SetVariable('contents_field', $contents->Get());
        $dispTitle =& Piwi::CreateWidget('CheckButtons', 'display_title');
        // FIXME: This is an ugly hack to add an ID to a Option...
        $dispTitle->AddOption(_t('BLOCKS_DISPLAYTITLE'), 'true', null, true);
        $tpl->SetVariable('display_title', $dispTitle->Get());

        $preview =& Piwi::CreateWidget('Button', 'previewButton', _t('GLOBAL_PREVIEW'), STOCK_NEW);
        $preview->SetID('previewButton');
        $preview->AddEvent(ON_CLICK, 'preview();');
        $tpl->SetVariable('preview_button', $preview->Get());

        $save =& Piwi::CreateWidget('Button', 'save', _t('GLOBAL_SAVE'), STOCK_SAVE);
        $save->SetID('saveButton');
        $save->AddEvent(ON_CLICK, 'updateBlock();');
        $tpl->SetVariable('save', $save->Get());

        if ($this->GetPermission('DeleteBlock')) {
           $del =& Piwi::CreateWidget('Button', 'delete', _t('GLOBAL_DELETE'), STOCK_DELETE);
            $del->AddEvent(ON_CLICK, 'if (confirm(\'' . _t('BLOCKS_CONFIRM_DELETE_BLOCK') . '\')) { deleteBlock(); }');
            $del->SetID('delButton');
            $tpl->SetVariable('delete', $del->Get());
        } else {
            $tpl->SetVariable('delete', '');
        }

        $cancel =& Piwi::CreateWidget('Button', 'cancel', _t('GLOBAL_CANCEL'), STOCK_CANCEL);
        $cancel->AddEvent(ON_CLICK, 'returnToEdit();');
        $cancel->SetID('cancelButton');
        $tpl->SetVariable('cancel', $cancel->Get());

        $edit =& Piwi::CreateWidget('Button', 'editButton', _t('GLOBAL_EDIT'), STOCK_EDIT);
        $edit->AddEvent(ON_CLICK, 'switchTab(\'edit\')');
        $edit->SetID('editButton');
        $tpl->SetVariable('edit_button', $edit->Get());

        // Messages
        $tpl->SetVariable('incompleteBlockFields', _t('GLOBAL_ERROR_INCOMPLETE_FIELDS'));
        $tpl->SetVariable('retrieving_message',    _t('BLOCKS_MSGRETRIEVING'));
        $tpl->SetVariable('updating_message',      _t('BLOCKS_MSGUPDATING'));
        $tpl->SetVariable('deleting_message',      _t('BLOCKS_MSGDELETING'));
        $tpl->SetVariable('saving_message',        _t('BLOCKS_MSGSAVING'));
        $tpl->SetVariable('sending_message',       _t('BLOCKS_MSGSENDING'));

        // Acl
        $tpl->SetVariable('acl_add', $this->GetPermission('AddBlock')?'true':'false');
        $tpl->SetVariable('acl_edit', $this->GetPermission('EditBlock')?'true':'false');
        $tpl->SetVariable('acl_delete', $this->GetPermission('DeleteBlock')?'true':'false');

        $tpl->ParseBlock('blocks');
        return $tpl->Get();
    }
}