
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
