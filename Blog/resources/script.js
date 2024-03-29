/**
 * Blog Javascript actions
 *
 * @category   Ajax
 * @package    Blog
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @author     Ali Fazelzadeh <afz@php.net>
 * @copyright  2005-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
/**
 * Use async mode, create Callback
 */
var BlogCallback = {

    deletecomment: function(response) {
        showResponse(response);
        if (response[0]['css'] == 'notice-message') {
            $('comments_datagrid').deleteItem();
            var limit = $('comments_datagrid').getCurrentPage();
            var formData = getDataOfLCForm();
            updateCommentsDatagrid(limit, formData['filter'],
                                   formData['search'], formData['status'],
                                   true);
        }
    },

    deletecomments: function(response) {
        if (response[0]['css'] == 'notice-message') {
            var rows = $('comments_datagrid').getSelectedRows();
            if (rows.length > 0) {
                for(var i=0; i<rows.length; i++) {
                    $('comments_datagrid').deleteItem();
                }
            }
            PiwiGrid.multiSelect($('comments_datagrid'));
            var limit = $('comments_datagrid').getCurrentPage();
            var formData = getDataOfLCForm();
            updateCommentsDatagrid(limit, formData['filter'],
                                   formData['search'], formData['status'],
                                   true);
        } else {
            PiwiGrid.multiSelect($('comments_datagrid'));
        }
        showResponse(response);
    },

    deleteentries: function(response) {
        if (response[0]['css'] == 'notice-message') {
            var rows = $('posts_datagrid').getSelectedRows();
            if (rows.length > 0) {
                for(var i=0; i<rows.length; i++) {
                    $('posts_datagrid').deleteItem();
                }
            }
            PiwiGrid.multiSelect($('posts_datagrid'));
            var limit = $('posts_datagrid').getCurrentPage();
            var formData = getDataOfLEForm();
            updatePostsDatagrid(formData['period'], formData['category'],
                                formData['status'], formData['search'], 0, true);
        } else {
            PiwiGrid.multiSelect($('posts_datagrid'));
        }
        showResponse(response);
    },

    changeentrystatus: function(response) {
        if (response[0]['css'] == 'notice-message') {
            PiwiGrid.multiSelect($('posts_datagrid'));
            resetLEForm();
            var formData = getDataOfLEForm();
            updatePostsDatagrid(formData['period'], formData['category'],
                                formData['status'], formData['search'], 0, true);
        } else {
            PiwiGrid.multiSelect($('posts_datagrid'));
        }
        showResponse(response);
    },

    deletetrackbacks: function(response) {
        if (response[0]['css'] == 'notice-message') {
            var rows = $('trackbacks_datagrid').getSelectedRows();
            if (rows.length > 0) {
                for(var i=0; i<rows.length; i++) {
                    $('trackbacks_datagrid').deleteItem();
                }
            }
            PiwiGrid.multiSelect($('trackbacks_datagrid'));
            var limit = $('trackbacks_datagrid').getCurrentPage();
            var formData = getDataOfLTBForm();
            updateTrackbacksDatagrid(limit, formData['filter'],
                                   formData['search'], formData['status'],
                                   true);
        } else {
            PiwiGrid.multiSelect($('trackbacks_datagrid'));
        }
        showResponse(response);
    },

    markas: function(response) {
        if (response[0]['css'] == 'notice-message') {
            PiwiGrid.multiSelect($('comments_datagrid'));
            resetLCForm();
            var formData = getDataOfLCForm();
            updateCommentsDatagrid(0,
                                   formData['filter'],
                                   formData['search'],
                                   formData['status'],
                                   true);
        } else {
            PiwiGrid.multiSelect($('comments_datagrid'));
        }
        showResponse(response);
    },

    trackbackmarkas: function(response) {
        if (response[0]['css'] == 'notice-message') {
            PiwiGrid.multiSelect($('trackbacks_datagrid'));
            resetLTBForm();
            var formData = getDataOfLTBForm();
            updateTrackbacksDatagrid(0,
                                   formData['filter'],
                                   formData['search'],
                                   formData['status'],
                                   true);
        } else {
            PiwiGrid.multiSelect($('trackbacks_datagrid'));
        }
        showResponse(response);
    },

    savesettings: function(response) {
        showResponse(response);
    },

    getcategoryform: function(response) {
        fillCatInfoForm(response);
    },

    getcategorycombo: function(response) {
        updateCategoryCombo();
    },

    addcategory: function(response) {
        showResponse(response);
        if (response[0]['css'] == 'notice-message') {
            resetCategoryForms();
        }
    },

    updatecategory: function(response) {
        showResponse(response);
        if (response[0]['css'] == 'notice-message') {
            resetCategoryForms();
        }
    },

    deletecategory: function(response) {
        showResponse(response);
        if (response[0]['css'] == 'notice-message') {
            resetCategoryForms();
        }
    },

    autodraft: function(response) {
        showSimpleResponse(response);
    }
}

/**
 * Reset ListEntries form
 */
function resetLEForm()
{
    var form = document.forms['ListEntries'];
    form.elements['show'].value     = '';
    form.elements['category'].value = '';
    form.elements['status'].value   = '';
    form.elements['search'].value   = '';
}

/**
 * Reset ListComments form
 */
function resetLCForm()
{
    var form = document.forms['ListComments'];
    form.elements['filterby'].value = '';
    form.elements['filter'].value   = '';
    form.elements['status'].value   = 'approved';
}

/**
 * Reset ListTrackbacks form
 */
function resetLTBForm()
{
    var form = document.forms['ListTrackbacks'];
    form.elements['filterby'].value = '';
    form.elements['filter'].value   = '';
    form.elements['status'].value   = 'various';
}

/**
 * Get data of the form ListEntries form
 */
function getDataOfLEForm()
{
    var form = document.forms['ListEntries'];

    var data = new Array();

    data['period']   = form.elements['show'].value;
    data['category'] = form.elements['category'].value;
    data['status']   = form.elements['status'].value;
    data['search']   = form.elements['search'].value;

    return data;
}

/**
 * Get data of the form ListComments form
 */
function getDataOfLCForm()
{
    var form = document.forms['ListComments'];

    var data = new Array();

    data['filter']   = form.elements['filterby'].value;
    data['search']   = form.elements['filter'].value;
    data['status']   = form.elements['status'].value;

    return data;
}

/**
 * Get data of the form ListTrackbacks form
 */
function getDataOfLTBForm()
{
    var form = document.forms['ListTrackbacks'];

    var data = new Array();

    data['filter']   = form.elements['filterby'].value;
    data['search']   = form.elements['filter'].value;
    data['status']   = form.elements['status'].value;

    return data;
}

/**
 * Prepare the preview
 */
function parseText(form)
{
    var title   = form.elements['title'].value;
    var content = getEditorValue('text_block');
    content = blogSync.parsetext(content);

    var preview = document.getElementById('preview');
    preview.style.display = 'block';

    var titlePreview   = document.getElementById('previewTitle');
    var contentPreview = document.getElementById('previewContent');

    titlePreview.innerHTML   = title;
    contentPreview.innerHTML = content;
}

/**
 * Delete a comment
 */
function deleteComment(id)
{
    blog.deletecomment(id);
}

/**
 * search for a post
 */
function searchPost()
{
    var formData = getDataOfLEForm();
    updatePostsDatagrid(formData['period'], formData['category'],
                        formData['status'], formData['search'], 0, true);

    return false;
}

/**
 * search for a comment
 */
function searchComment()
{
    var formData = getDataOfLCForm();
    updateCommentsDatagrid(0, formData['filter'], formData['search'], formData['status'], true);
    return false;
}

/**
 * search for a trackback
 */
function searchTrackback()
{
    var formData = getDataOfLTBForm();
    updateTrackbacksDatagrid(0, formData['filter'], formData['search'], formData['status'], true);
    return false;
}

/**
 * Update post datagrid
 */
function updatePostsDatagrid(period, cat, status, search, limit, resetCounter)
{
    var result = blogSync.searchposts(period, cat, status, search, limit);
    resetGrid('posts_datagrid', result);
    if (resetCounter) {
        var size = blogSync.sizeofsearch(period, cat, status, search);
        $('posts_datagrid').rowsSize    = size;
        $('posts_datagrid').setCurrentPage(0);
        $('posts_datagrid').updatePageCounter();
    }
}

/**
 * Get posts data
 */
function getData(limit)
{
    switch($('action').value) {
    case 'ListEntries':
        if (limit == undefined) {
            limit = $('posts_datagrid').getCurrentPage();
        }
        var formData = getDataOfLEForm();
        updatePostsDatagrid(formData['period'], formData['category'],
                            formData['status'], formData['search'],
                            limit, false);
        break;
    case 'ManageComments':
        if (limit == undefined) {
            limit = $('comments_datagrid').getCurrentPage();
        }
        var formData = getDataOfLCForm();
        updateCommentsDatagrid(limit, formData['filter'],
                               formData['search'], formData['status'],
                               false);
        break;
    case 'ManageTrackbacks':
        if (limit == undefined) {
            limit = $('trackbacks_datagrid').getCurrentPage();
        }
        var formData = getDataOfLTBForm();
        updateTrackbacksDatagrid(limit, formData['filter'],
                                 formData['search'], formData['status'],
                                 false);
        break;
    }
}

/**
 * Get first values of posts or comments
 */
function firstValues()
{
    switch($('action').value) {
    case 'ListEntries':
        var firstValues = $('posts_datagrid').getFirstPagerValues();
        getData(firstValues);
        $('posts_datagrid').firstPage();
        break;
    case 'ManageComments':
        var firstValues = $('comments_datagrid').getFirstPagerValues();
        getData(firstValues);
        $('comments_datagrid').firstPage();
        break;
    case 'ManageTrackbacks':
        var firstValues = $('trackbacks_datagrid').getFirstPagerValues();
        getData(firstValues);
        $('trackbacks_datagrid').firstPage();
        break;
    }
}

/**
 * Get previous values of posts or comments
 */
function previousValues()
{
    switch($('action').value) {
    case 'ListEntries':
        var previousValues = $('posts_datagrid').getPreviousPagerValues();
        getData(previousValues);
        $('posts_datagrid').previousPage();
        break;
    case 'ManageComments':
        var previousValues = $('comments_datagrid').getPreviousPagerValues();
        getData(previousValues);
        $('comments_datagrid').previousPage();
        break;
    case 'ManageTrackbacks':
        var previousValues = $('trackbacks_datagrid').getPreviousPagerValues();
        getData(previousValues);
        $('trackbacks_datagrid').previousPage();
        break;
    }
}

/**
 * Get next values of posts or comments
 */
function nextValues()
{
    switch($('action').value) {
    case 'ListEntries':
        var nextValues = $('posts_datagrid').getNextPagerValues();
        getData(nextValues);
        $('posts_datagrid').nextPage();
        break;
    case 'ManageComments':
        var nextValues = $('comments_datagrid').getNextPagerValues();
        getData(nextValues);
        $('comments_datagrid').nextPage();
        break;
    case 'ManageTrackbacks':
        var nextValues = $('trackbacks_datagrid').getNextPagerValues();
        getData(nextValues);
        $('trackbacks_datagrid').nextPage();
        break;
    }
}

/**
 * Get last values of posts or comments
 */
function lastValues()
{
    switch($('action').value) {
    case 'ListEntries':
        var lastValues = $('posts_datagrid').getLastPagerValues();
        getData(lastValues);
        $('posts_datagrid').lastPage();
        break;
    case 'ManageComments':
        var lastValues = $('comments_datagrid').getLastPagerValues();
        getData(lastValues);
        $('comments_datagrid').lastPage();
        break;
    case 'ManageTrackbacks':
        var lastValues = $('trackbacks_datagrid').getLastPagerValues();
        getData(lastValues);
        $('trackbacks_datagrid').lastPage();
        break;
    }
}

/**
 * Update comments datagrid
 */
function updateCommentsDatagrid(limit, filter, search, status, resetCounter)
{
    result = blogSync.searchcomments(limit, filter, search, status);
    resetGrid('comments_datagrid', result);
    if (resetCounter) {
        var size = blogSync.sizeofcommentssearch(filter, search, status);
        $('comments_datagrid').rowsSize    = size;
        $('comments_datagrid').setCurrentPage(0);
        $('comments_datagrid').updatePageCounter();
    }
}

/**
 * Update trackbacks datagrid
 */
function updateTrackbacksDatagrid(limit, filter, search, status, resetCounter)
{
    result = blogSync.searchtrackbacks(limit, filter, search, status);
    resetGrid('trackbacks_datagrid', result);
    if (resetCounter) {
        var size = blogSync.sizeoftrackbackssearch(filter, search, status);
        $('trackbacks_datagrid').rowsSize    = size;
        $('trackbacks_datagrid').setCurrentPage(0);
        $('trackbacks_datagrid').updatePageCounter();
    }
}

/**
 * Delete comment
 */
function commentDelete(row_id)
{
    var confirmation = confirm(deleteConfirm);
    if (confirmation) {
        blog.deletecomments(row_id);
    }
}

/**
 * Delete trackback
 */
function trackbackDelete(row_id)
{
    var confirmation = confirm(deleteConfirm);
    if (confirmation) {
        blog.deletetrackbacks(row_id);
    }
}

/**
 * Executes an action on comments
 */
function commentDGAction(combo)
{
    var rows = $('comments_datagrid').getSelectedRows();
    var selectedRows = false;
    if (rows.length > 0) {
        selectedRows = true;
    }

     if (combo.value == 'delete') {
        if (selectedRows) {
            var confirmation = confirm(deleteConfirm);
            if (confirmation) {
                blog.deletecomments(rows);
            }
        }
    } else if (combo.value != '') {
        if (selectedRows) {
            blog.markas(rows, combo.value);
        }
    }
}

/**
 * Executes an action on trackbacks
 */
function trackbackDGAction(combo)
{
    var rows = $('trackbacks_datagrid').getSelectedRows();
    var selectedRows = false;
    if (rows.length > 0) {
        selectedRows = true;
    }

     if (combo.value == 'delete') {
        if (selectedRows) {
            var confirmation = confirm(deleteConfirm);
            if (confirmation) {
                blog.deletetrackbacks(rows);
            }
        }
    } else if (combo.value != '') {
        if (selectedRows) {
            blog.trackbackmarkas(rows, combo.value);
        }
    }
}

/**
 * Executes an action on trackbacks
 */
function entryDGAction(combo)
{
    var rows = $('posts_datagrid').getSelectedRows();
    var selectedRows = false;
    if (rows.length > 0) {
        selectedRows = true;
    }

     if (combo.value == 'delete') {
        if (selectedRows) {
            var confirmation = confirm(deleteConfirm);
            if (confirmation) {
                blog.deleteentries(rows);
            }
        }
    } else if (combo.value != '') {
        if (selectedRows) {
            blog.changeentrystatus(rows, combo.value);
        }
    }
}

/**
 * Update the blog settings
 */
function saveSettings(form)
{
    var defaultView      = form.elements['default_view'].value;
    var lastEntries      = form.elements['last_entries_limit'].value;
    var popularLimit     = form.elements['popular_limit'].value;
    var lastComments     = form.elements['last_comments_limit'].value;
    var recentComments   = form.elements['last_recentcomments_limit'].value;
    var defaultCat       = form.elements['default_category'].value;
    var xmlLimit         = form.elements['xml_limit'].value;
    var comments         = form.elements['comments'].value;
    var comment_status   = form.elements['comment_status'].value;
    var trackback        = form.elements['trackback'].value;
    var trackback_status = form.elements['trackback_status'].value;
    var pingback         = form.elements['pingback'].value;

    blog.savesettings(defaultView, lastEntries, popularLimit, lastComments, recentComments, defaultCat, 
                      xmlLimit, comments, comment_status, trackback, trackback_status,
                      pingback);
}

/**
 * Edit the category
 */
function editCategory(id)
{
    blog.getcategoryform('editcategory', id);
}

/**
 * Reset the category Info Form values
 */
function resetCategoryForm()
{
    blog.getcategoryform('new', 0);
    $('category_id').selectedIndex = -1;
}

/**
 * Update the category combo (the big one)
 */
function updateCategoryCombo(content)
{
    var form   = document.getElementById('categoriesComboTable');
    form.innerHTML = content;
}

/**
 * Get the big combo
 */
function resetCategoryForms()
{
    var categoryCombo = blogSync.getcategorycombo();
    updateCategoryCombo(categoryCombo);

    var catInfo = document.getElementById('catinfoform');
    catInfo.innerHTML = blogSync.getcategoryform('new', 0);
}

/**
 * Save the info of a category, updating or adding.
 */
function saveCategory(form)
{
    action = form.elements['action'].value;

    if (action == 'AddCategory') {
        blog.addcategory(form.elements['name'].value,
                         form.elements['description'].value,
                         form.elements['fast_url'].value,
                         form.elements['meta_keywords'].value,
                         form.elements['meta_desc'].value);
    } else {
        blog.updatecategory(form.elements['catid'].value,
                            form.elements['name'].value,
                            form.elements['description'].value,
                            form.elements['fast_url'].value,
                            form.elements['meta_keywords'].value,
                            form.elements['meta_desc'].value);
    }
}

/**
 * Fill the Category Info Form
 */
function fillCatInfoForm(content)
{
    var catInfo = document.getElementById('catinfoform');
    catInfo.innerHTML = content;
}

/**
 * Delete category
 */
function deleteCategory(form)
{
    var id = form.elements['catid'].value;
    blog.deletecategory(id);
}

/**
 * Create a new category
 */
function newCategory()
{
    resetCategoryForm();
}

/**
 * Just the mother function that will make sure that auto drafting is running
 * and is being run every ~ 120 seconds (2 minutes).
 *
 * @see AutoDraft();
 */
function startAutoDrafting()
{
    var title          = $('title').value;
    var fasturl        = $('fasturl').value;
    var meta_keywords  = $('meta_keywords').value;
    var meta_desc      = $('meta_desc').value;
    var allow_comments = $('allow_comments').checked;
    if ($('trackback_to')) {
        var trackbacks = $('trackback_to').value;
    }
    var published      = $('published').value;
    var summary        = getEditorValue('summary_block');
    var content        = getEditorValue('text_block');

    if (!title.blank() && (!summary.blank() || !content.blank()))
    {
        var timestamp = null;
        if ($('edit_timestamp').checked) {
            timestamp = $('pubdate').value;
        }

        var categoriesNode = $('categories').getElementsByTagName('input');
        var categories     = new Array();
        var catCounter     = 0;
        for(var i = 0; i < categoriesNode.length; i++) {
            if (categoriesNode[i].checked) {
                categories[catCounter] = categoriesNode[i].value;
                catCounter++;
            }
        }

        var id = '';
        var actioni = $('action').value;

        switch (actioni) {
            case 'SaveNewEntry':
                id = 'NEW';
                break;
            case 'SaveEditEntry':
                id = $('id').value;
                break;
        }

        blog.autodraft(id, categories, title, summary, content, fasturl, meta_keywords, meta_desc,
                       allow_comments, trackbacks, published, timestamp);
    }
    setTimeout('startAutoDrafting();', 120000);
}

/**
 * Auto Draft response
 */
function showSimpleResponse(message)
{
    if (!autoDraftDone) {
        var actioni   = $('action').value;
        if (actioni == 'SaveNewEntry' && message[0]['css'] == 'notice-message') {
            $('published').value = '0';
            $('id').value        = message[0]['message']['id'];
            $('action').value    = 'SaveEditEntry';
            message[0]['message'] = message[0]['message']['message'];
        }
        autoDraftDone = true;
    }
    showResponse(message, false);
}

/**
 * Toggle advanced
 */
function toggleAdvanced(checked)
{
    if (checked) {
        $('advanced').style.display = 'block';
    } else {
        $('advanced').style.display = 'none';
    }
}

/**
 * Toggle update publication time
 */
function toggleUpdate(checked)
{
    if (checked) {
        $('pubdate').disabled = false;
        $('pubdate_button').disabled = false;
    } else {
        $('pubdate').disabled = true;
        $('pubdate_button').disabled = true;
    }
}

var blog = new blogadminajax(BlogCallback);
blog.serverErrorFunc = Jaws_Ajax_ServerError;
blog.onInit = showWorkingNotification;
blog.onComplete = hideWorkingNotification;

var blogSync = new blogadminajax();
blogSync.serverErrorFunc = Jaws_Ajax_ServerError;
blogSync.onInit = showWorkingNotification;
blogSync.onComplete = hideWorkingNotification;

var firstFetch = true;
var autoDraftDone = false;
