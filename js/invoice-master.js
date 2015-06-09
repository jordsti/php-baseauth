
function deletePermission(permissionId)
{
	var linkId = 'permission_delete_' + permissionId;
	
	var deleteLink = document.getElementById(linkId);
	
	if(deleteLink)
	{
		deleteLink.innerHTML = "DELETE";
		deleteLink.setAttribute('href', 'groups.php?action=delete_permission&permission_id='+permissionId);
	}
}


function deletePermissionPrompt(permissionId)
{
	var linkId = 'permission_delete_' + permissionId;
	
	var deleteLink = document.getElementById(linkId);
	if(deleteLink)
	{
		deleteLink.innerHTML = 'Are you sure ? (Double-Click)';
	}
}

function deleteGroup(groupId)
{
	var linkId = 'group_delete_' + groupId;
	
	var deleteLink = document.getElementById(linkId);
	
	if(deleteLink)
	{
		deleteLink.innerHTML = "DELETE";
		deleteLink.setAttribute('href', 'groups.php?action=delete_group&group_id='+groupId);
	}
}


function deleteGroupPrompt(groupId)
{
	var linkId = 'group_delete_' + groupId;
	
	var deleteLink = document.getElementById(linkId);
	if(deleteLink)
	{
		deleteLink.innerHTML = 'Are you sure ? (Double-Click)';
	}
}

function deleteUser(userId)
{
	var linkId = 'user_delete_' + userId;
	
	var deleteLink = document.getElementById(linkId);
	
	if(deleteLink)
	{
		deleteLink.innerHTML = "DELETE";
		deleteLink.setAttribute('href', 'users.php?action=delete_user&user_id='+userId);
	}
}


function deleteUserPrompt(settingId)
{
	var linkId = 'user_delete_' + settingId;
	
	var deleteLink = document.getElementById(linkId);
	if(deleteLink)
	{
		deleteLink.innerHTML = 'Are you sure ? (Double-Click)';
	}
}

function deleteSetting(settingId)
{
	var linkId = 'setting_delete_' + settingId;
	
	var deleteLink = document.getElementById(linkId);
	
	if(deleteLink)
	{
		deleteLink.innerHTML = "DELETE";
		deleteLink.setAttribute('href', 'settings.php?action=delete_setting&setting_id='+settingId);
	}
}


function deleteSettingPrompt(settingId)
{
	var linkId = 'setting_delete_' + settingId;
	
	var deleteLink = document.getElementById(linkId);
	if(deleteLink)
	{
		deleteLink.innerHTML = 'Are you sure ? (Double-Click)';
	}
}
