/**
 * Forum JS actions
 *
 * @category   Ajax
 * @package    Forum
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2010 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
/**
 * Use async mode, create Callback
 */
var ForumCallback = {

    updategroup: function(response) {
        if (response['css'] == 'notice-message') {
            $('group_'+$('gid').value).getElementsByTagName('a')[0].innerHTML = $('title').value;
            stopAction();
        }
        showResponse(response);
    }
}

/**
 * Select Tree row
 *
 */
function selectTreeRow(rowElement)
{
    if (selectedRow) {
        selectedRow.style.backgroundColor = selectedRowColor;
    }
    selectedRowColor = rowElement.style.backgroundColor;
    rowElement.style.backgroundColor = '#eec';
    selectedRow = rowElement;
}

/**
 * Unselect Tree row
 *
 */
function unselectTreeRow()
{
    if (selectedRow) {
        selectedRow.style.backgroundColor = selectedRowColor;
    }
    selectedRow = null;
    selectedRowColor = null;
}

function AddNewForumGroup(gid) {
    var mainDiv = document.createElement('div');
    var div =$('group_1').getElementsByTagName('div')[0].cloneNode(true);
    mainDiv.className = 'forum_groups';
    mainDiv.id = "group_"+gid;
    mainDiv.appendChild(div);
    $('forums_tree').appendChild(mainDiv);
    var links = mainDiv.getElementsByTagName('a');
    links[0].href      = 'javascript: editGroup('+gid+');';
    links[0].innerHTML = $('title').value;
    links[1].href = 'javascript: addForum('+gid+', 0);';
}

function AddNewForumItem(gid, fid, order)
{
    var mainDiv = document.createElement('div');
    var div =$('group_1').getElementsByTagName('div')[0].cloneNode(true);
    mainDiv.className = 'forum_levels';
    mainDiv.id = "forum_"+fid;
    mainDiv.appendChild(div);
    var parentNode = $('group_'+gid);
    parentNode.appendChild(mainDiv);
    //set ordering
    var oldOrder = $A(parentNode.childNodes).indexOf($('forum_'+fid));
    if (order < oldOrder) {
        parentNode.insertBefore($('forum_'+fid), parentNode.childNodes[order]);
    }
    //--
    var links = mainDiv.getElementsByTagName('a');
    links[0].href      = 'javascript: editForum(this, '+fid+');';
    links[0].innerHTML = $('title').value;
    links[1].href = 'javascript: addForum('+gid+', '+ fid +');';
    var images = mainDiv.getElementsByTagName('img');
    images[0].src = forumImageSrc;
    // hide forum actions
    mainDiv.getElementsByTagName('div')[2].style.visibility = 'hidden';
}

/**
 * Saves data / changes
 */
function saveForums()
{
    if ((jawsTrim($('title').value) == '')) {
        alert(incompleteFields);
        return false;
    }

    if (currentAction == 'Groups') {
        cacheForumForm = null;
        if (selectedGroup == null) {
            var response = forumSync.insertgroup(
                                    $('title').value,
                                    $('description').value,
                                    $('fast_url').value,
                                    $('order').value,
                                    $('locked').value,
                                    $('published').value);
            if (response['css'] == 'notice-message') {
                AddNewForumGroup(response['data']);
                stopAction();
            }
            showResponse(response);
        } else {
            forumAsync.updategroup(
                                $('gid').value,
                                $('title').value,
                                $('description').value,
                                $('fast_url').value,
                                $('order').value,
                                $('locked').value,
                                $('published').value);
        }
    } else {
        if (selectedForum == null) {
            var response = forumSync.insertforum(
                                    $('gid').value,
                                    $('title').value,
                                    $('description').value,
                                    $('fast_url').value,
                                    $('order').value,
                                    $('locked').value,
                                    $('published').value);
            if (response['css'] == 'notice-message') {
                AddNewForumItem($('gid').value, response['data'], $('order').value);
                stopAction();
            }
            showResponse(response);
        } else {
            var response = forumSync.updateforum(
                                    $('fid').value,
                                    $('gid').value,
                                    $('title').value,
                                    $('description').value,
                                    $('fast_url').value,
                                    $('order').value,
                                    $('locked').value,
                                    $('published').value);
            if (response['css'] == 'notice-message') {
                $('forum_'+$('fid').value).getElementsByTagName('a').innerHTML = $('title').value;
                var new_parentNode = $('group_'+$('gid').value);
                if ($('forum_'+$('fid').value).parentNode != new_parentNode) {
                    if ($('order').value > (new_parentNode.childNodes.length - 1)) {
                        new_parentNode.appendChild($('forum_'+$('fid').value));
                    } else {
                        new_parentNode.insertBefore($('forum_'+$('fid').value), new_parentNode.childNodes[$('order').value]);
                    }
                } else {
                    var oldOrder = $A(new_parentNode.childNodes).indexOf($('forum_'+$('fid').value));
                    if ($('order').value > oldOrder) {
                        new_parentNode.insertBefore($('forum_'+$('fid').value), new_parentNode.childNodes[$('order').value].nextSibling);
                    } else {
                        new_parentNode.insertBefore($('forum_'+$('fid').value), new_parentNode.childNodes[$('order').value]);
                    }
                }
                stopAction();
            }
            showResponse(response);
        }
    }
}

function setOrdersCombo(gid, pid, selected) {
    $('order').options.length = 0;
    if (pid == 0) {
        var new_parentNode = $('group_'+gid);
    } else {
        var new_parentNode = $('forum_'+pid);
    }
    var order = new_parentNode.childNodes.length;
    order = order - 1;

    if (($('fid').value < 1) || ($('forum_'+$('fid').value).parentNode != new_parentNode)) {
        order = order + 1;
    }

    for(var i = 0; i < order; i++) {
        $('order').options[i] = new Option(i+1, i+1);
    }
    if (selected == null) {
        $('order').value = order;
    } else {
        $('order').value = selected;
    }
}

/**
 * Add group
 */
function addGroup()
{
    if (cacheGroupForm == null) {
        cacheGroupForm = forumSync.getgroupui();
    }
    currentAction = 'Groups';

    $('edit_area').getElementsByTagName('span').innerHTML = addGroupTitle;
    selectedGroup = null;
    $('btn_cancel').style.display = 'inline';
    $('btn_del').style.display    = 'none';
    $('btn_save').style.display   = 'inline';
    $('btn_add').style.display    = 'none';
    $('forums_edit').innerHTML = cacheGroupForm;
}

/**
 */
function mm_enter(eid)
{
    m_bg_color = $(eid).style.backgroundColor;
    if ($(eid).parentNode.className != 'forum_groups') {
        $(eid).style.backgroundColor = "#f0f0f0";
    }
    $(eid).getElementsByTagName('div')[1].style.visibility = 'visible';
}

/**
 */
function mm_leave(eid)
{
    $(eid).style.backgroundColor = m_bg_color;
    if ($(eid).parentNode.className != 'forum_groups') {
        $(eid).getElementsByTagName('div')[1].style.visibility = 'hidden';
    }
}

/**
 * Add forum
 */
function addForum(gid, pid)
{
    if (cacheForumForm == null) {
        cacheForumForm = forumSync.getforumui();
    }

    stopAction();
    currentAction = 'Forums';

    if (pid == 0) {
        $('edit_area').getElementsByTagName('span').innerHTML =
            addForumTitle + ' - ' + $('group_'+gid).getElementsByTagName('a').innerHTML;
    } else {
        $('edit_area').getElementsByTagName('span').innerHTML =
            addForumTitle + ' - ' + $('group_'+gid).getElementsByTagName('a').innerHTML +
            ' - ' + $('forum_'+pid).getElementsByTagName('a').innerHTML;
    }

    selectedForum = null;
    $('btn_cancel').style.display = 'inline';
    $('btn_del').style.display    = 'none';
    $('btn_save').style.display   = 'inline';
    $('btn_add').style.display    = 'none';
    $('forums_edit').innerHTML = cacheForumForm;

    $('gid').value = gid;
    setOrdersCombo(gid, pid);
}

/**
 * Edit group
 */
function editGroup(gid)
{
    if (gid == 0) return;
    if (cacheGroupForm == null) {
        cacheGroupForm = forumSync.getgroupui();
    }
    currentAction = 'Groups';
    selectedGroup = gid;

    $('edit_area').getElementsByTagName('span').innerHTML =
        editGroupTitle + ' - ' + $('group_'+gid).getElementsByTagName('a').innerHTML;
    $('btn_cancel').style.display = 'inline';
    $('btn_del').style.display    = 'inline';
    $('btn_save').style.display   = 'inline';
    $('btn_add').style.display    = 'none';
    $('forums_edit').innerHTML = cacheGroupForm;  

    var group = forumSync.getgroup(selectedGroup);

    $('gid').value         = group['id'];
    $('title').value       = group['title'];
    $('description').value = group['description'];
    $('fast_url').value    = group['fast_url'];
    $('order').value       = group['order'];
    $('locked').value      = Number(group['locked']);
    $('published').value   = Number(group['published']);
}

/**
 * Edit forum
 */
function editForum(element, fid)
{
    if (fid == 0) return;
    selectTreeRow(element.parentNode);
    if (cacheForumForm == null) {
        cacheForumForm = forumSync.getforumui();
    }
    currentAction = 'Forums';

    $('edit_area').getElementsByTagName('span').innerHTML =
        editForumTitle + ' - ' + $('forum_'+fid).getElementsByTagName('a').innerHTML;
    $('btn_cancel').style.display = 'inline';
    $('btn_del').style.display    = 'inline';
    $('btn_save').style.display   = 'inline';
    $('btn_add').style.display    = 'none';
    $('forums_edit').innerHTML = cacheForumForm;  

    selectedForum = fid;
    var forum = forumSync.getforum(selectedForum);

    $('fid').value         = forum['id'];
    $('gid').value         = forum['gid'];
    $('title').value       = forum['title'];
    $('description').value = forum['description'];
    $('fast_url').value    = forum['fast_url'];

    $('order').value       = forum['order'];

    $('locked').value      = Number(forum['locked']);
    $('published').value   = Number(forum['published']);
}

/**
 * Delete group/forum
 */
function delForums()
{
    if (currentAction == 'Groups') {
        var gid = selectedGroup;
        var msg = confirmGroupDelete;
        msg = msg.substr(0,  msg.indexOf('%s%')) + $('group_'+gid).getElementsByTagName('a').innerHTML + msg.substr(msg.indexOf('%s%')+3);
        if (confirm(msg)) {
            cacheForumForm = null;
            var response = forumSync.deletegroup(gid);
            if (response['css'] == 'notice-message') {
                Element.remove($('group_'+gid));
            }
            stopAction();
            showResponse(response);
        }
    } else {
        var fid = selectedForum;
        var msg = confirmForumDelete;
        msg = msg.substr(0,  msg.indexOf('%s%')) + $('forum_'+fid).getElementsByTagName('a').innerHTML + msg.substr(msg.indexOf('%s%')+3);
        if (confirm(msg)) {
            var response = forumSync.deleteforum(fid);
            if (response['css'] == 'notice-message') {
                Element.remove($('forum_'+fid));
            }
            stopAction();
            showResponse(response);
        }
    }
}

/**
 * Stops doing a certain action
 */
function stopAction()
{
    $('btn_cancel').style.display = 'none';
    $('btn_del').style.display    = 'none';
    $('btn_save').style.display   = 'none';
    $('btn_add').style.display    = 'inline';

    selectedForum  = null;
    selectedGroup = null;
    currentAction = null;
    unselectTreeRow();

    $('forums_edit').innerHTML = '';
    $('edit_area').getElementsByTagName('span')[0].innerHTML = '';
}

var forumAsync = new forumadminajax(ForumCallback);
forumAsync.serverErrorFunc = Jaws_Ajax_ServerError;
forumAsync.onInit = showWorkingNotification;
forumAsync.onComplete = hideWorkingNotification;

var forumSync  = new forumadminajax();
forumSync.serverErrorFunc = Jaws_Ajax_ServerError;
forumSync.onInit = showWorkingNotification;
forumSync.onComplete = hideWorkingNotification;

//Current group
var selectedGroup = null;

//Current forum
var selectedForum = null;

//Cache for saving the group form template
var cacheGroupForm = null;

//Cache for saving the forum form template
var cacheForumForm = null;

//Forum items background color
var m_bg_color = null;
var org_m_bg_color = null;

//Which row selected in Tree
var selectedRow = null;
var selectedRowColor = null;
