<?php
/**
 * Glossary Gadget Admin
 *
 * @category   GadgetModel
 * @package    Glossary
 * @author     Jonathan Hernandez <ion@suavizado.com>
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @copyright  2004-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
require_once JAWS_PATH . 'gadgets/Glossary/Model.php';

class GlossaryAdminModel extends GlossaryModel
{
    /**
     * Install the gadget
     *
     * @access  public
     * @return  mixed   True on success and Jaws_Error on failure
     */
    function InstallGadget()
    {
        $result = $this->installSchema('schema.xml');
        if (Jaws_Error::IsError($result)) {
            return $result;
        }

        $variables = array();
        $variables['timestamp'] = $GLOBALS['db']->Date();

        $result = $this->installSchema('insert.xml', $variables, 'schema.xml', true);
        if (Jaws_Error::IsError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Uninstalls the gadget
     *
     * @access  public
     * @return  mixed   True on Success or Jaws_Error on Failure
     */
    function UninstallGadget()
    {
        $result = $GLOBALS['db']->dropTable('glossary');
        if (Jaws_Error::IsError($result)) {
            $gName  = _t('GLOSSARY_NAME');
            $errMsg = _t('GLOBAL_ERROR_GADGET_NOT_UNINSTALLED', $gName);
            $GLOBALS['app']->Session->PushLastResponse($errMsg, RESPONSE_ERROR);
            return new Jaws_Error($errMsg, $gName);
        }

        return true;
    }

    /**
     * Update the gadget
     *
     * @access  public
     * @param   string  $old    Current version (in registry)
     * @param   string  $new    New version (in the $gadgetInfo file)
     * @return  mixed   True on Success or Jaws_Error on Failure
     */
    function UpdateGadget($old, $new)
    {
        if (version_compare($old, '0.7.0', '<')) {
            $result = $this->installSchema('0.7.0.xml', '', "$old.xml");
            if (Jaws_Error::IsError($result)) {
                return $result;
            }
        }

        $result = $this->installSchema('schema.xml', '', '0.7.0.xml');
        if (Jaws_Error::IsError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Deletes a term
     *
     * @acess   public
     * @param   int     $id  Term ID
     * @return  mixed   Returns true if term was deleted or Jaws_Error on error
     */
    function DeleteTerm($id)
    {
        $params       = array();
        $params['id'] = $id;
        $sql = "DELETE FROM [[glossary]] WHERE [id] = {id}";

        $result = $GLOBALS['db']->query($sql, $params);
        if (Jaws_Error::IsError($result)) {
            $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_ERROR_TERM_NOT_DELETED'), RESPONSE_ERROR);
            return new Jaws_Error(_t('GLOSSARY_ERROR_TERM_NOT_DELETED'), _t('GLOSSARY_NAME'));
        }

        $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_TERM_DELETED'), RESPONSE_NOTICE);
        return true;
    }


    /**
     * Updates a term
     *
     * @acess   public
     * @param   int     $id         Term's ID
     * @param   string  $term       Term
     * @param   string  $fast_url   
     * @param   string  $desc       Term's description
     * @return  mixed   Returns true if term was deleted or Jaws_Error on error
     */
    function UpdateTerm($id, $term, $fast_url, $desc)
    {
        $fast_url = empty($fast_url) ? $term : $fast_url;
        $fast_url = $this->GetRealFastUrl($fast_url, 'glossary', false);

        $params = array();
        $params['id']       = $id;
        $params['term']     = $term;
        $params['fast_url'] = $fast_url;
        $params['desc']     = $desc;
        $params['now']      = $GLOBALS['db']->Date();
        $sql = "
            UPDATE [[glossary]] SET
                [term] = {term},
                [fast_url] = {fast_url},
                [description] = {desc},
                [updatetime] = {now}
            WHERE [id] = {id}";

        $result = $GLOBALS['db']->query($sql, $params);
        if (Jaws_Error::IsError($result)) {
            $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_ERROR_TERM_NOT_UPDATED'), RESPONSE_ERROR);
            return new Jaws_Error(_t('GLOSSARY_ERROR_TERM_NOT_UPDATED'), _t('GLOSSARY_NAME'));
        }

        $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_TERM_UPDATED'), RESPONSE_NOTICE);
        return true;
    }

    /**
     * Adds a new term
     *
     * @acess   public
     * @param   string  $term       Term
     * @param   string  $fast_url   
     * @param   string  $desc       Term's description
     * @return  mixed   Returns true if term was added or Jaws_Error on error
     */
    function NewTerm($term, $fast_url, $desc)
    {
        $fast_url = empty($fast_url) ? $term : $fast_url;
        $fast_url = $this->GetRealFastUrl($fast_url, 'glossary');

        $params = array();
        $params['term']     = $term;
        $params['fast_url'] = $fast_url;
        $params['desc']     = $desc;
        $params['now']      = $GLOBALS['db']->Date();
        $sql = "
            INSERT INTO [[glossary]]
                ([term], [fast_url], [description], [createtime], [updatetime])
            VALUES
                ({term}, {fast_url}, {desc}, {now}, {now})";

        $result = $GLOBALS['db']->query($sql, $params);
        if (Jaws_Error::IsError($result)) {
            $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_ERROR_TERM_NOT_CREATED'), RESPONSE_ERROR);
            return new Jaws_Error(_t('GLOSSARY_ERROR_TERM_NOT_CREATED'), _t('GLOSSARY_NAME'));
        }

        $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_TERM_ADDED'), RESPONSE_NOTICE);

        $sql = "SELECT [id] FROM [[glossary]] WHERE [createtime] = {now}";
        $row = $GLOBALS['db']->queryRow($sql, $params);
        if (Jaws_Error::IsError($row)) {
            $GLOBALS['app']->Session->PushLastResponse(_t('GLOSSARY_ERROR_TERM_NOT_CREATED'), RESPONSE_ERROR);
            return new Jaws_Error(_t('GLOSSARY_ERROR_TERM_NOT_CREATED'), _t('GLOSSARY_NAME'));
        }

        if (isset($row['id'])) {
            return $row['id'];
        }

        return false;
    }

}