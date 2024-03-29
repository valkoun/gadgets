/**
 * Friends JS actions
 *
 * @category   Ajax
 * @package    Friends
 * @author     Pablo Fischer <pablo@pablo.com.mx>
 * @copyright  2005-2012 Jaws Development Group
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
/**
 * Use async mode, create Callback
 */
var FriendsCallback = { 
    newfriend: function(response) {
        if (response[0]['css'] == 'notice-message') {
            $('friends_datagrid').addItem();
            $('friends_datagrid').setCurrentPage(0);
        }
        showResponse(response);
        getDG();
    },

    deletefriend: function(response) {
        if (response[0]['css'] == 'notice-message') {
            $('friends_datagrid').deleteItem();          
        }
        showResponse(response);
        getDG();
    },
    
    updatefriend: function(response) {
        showResponse(response);
        getDG();
    },

    getfriend: function(response) {
        updateForm(response);
    },

    updateproperties: function(response) {
        showResponse(response);
    }
}

/**
 * Clean the form
 *
 */
function cleanForm(form) 
{
    form.elements['friend'].value = '';
    form.elements['url'].value    = 'http://';  
    form.elements['id'].value     = '';    
    form.elements['action'].value = 'AddFriend';
}

/**
 * Update form with new values
 *
 */
function updateForm(friendInfo) 
{
    $('friends_form').elements['friend'].value       = friendInfo['friend'].unescapeHTML();
    $('friends_form').elements['url'].value          = friendInfo['url'].unescapeHTML();
    $('friends_form').elements['id'].value           = friendInfo['id'];
    $('friends_form').elements['action'].value       = 'UpdateFriend';
}

/**
 * Add a friend: function
 */
function addFriend(form)
{
    var friendName = form.elements['friend'].value;
    var friendUrl  = form.elements['url'].value;
    
    friends.newfriend(friendName, friendUrl);
    cleanForm(form);
}


/**
 * Add a friend: function
 */
function updateFriend(form)
{
    var friendName = form.elements['friend'].value;
    var friendUrl  = form.elements['url'].value;
    var friendId   = form.elements['id'].value;

    friends.updatefriend(friendId, friendName, friendUrl);
    cleanForm(form);
}

/**
 * Submit the button
 */
function submitForm(form)
{
    if (form.elements['action'].value == 'AddFriend') {
        addFriend(form);
    } else {
        updateFriend(form);
    }
}

/**
 * Delete a friend : function
 */
function deleteFriend(id)
{
    friends.deletefriend(id);
    cleanForm($('friends_form'));
}

/**
 * Edit a friend
 *
 */
function editFriend(id)
{
    friends.getfriend(id);
}

/**
 * Update the properties
 *
 */
function updateProperties(form)
{
    var limitRandom = form.elements['limit_random'].value;
    friends.updateproperties(limitRandom);
}

var friends = new friendsadminajax(FriendsCallback);
friends.serverErrorFunc = Jaws_Ajax_ServerError;
friends.onInit = showWorkingNotification;
friends.onComplete = hideWorkingNotification;
