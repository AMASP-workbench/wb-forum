/**
 *	0.5.10
 */
 
var mod_forum = [ 
	'Sind Sie sicher das Sie \n\n#1\n\nl%F6schen wollen?\nDas kann nicht widerufen werden!',
	'Are you sure you want to delete \n\n#1\n\nnow? This can not be undone!',
	'Willen ze\n\n#1\n\n lossen?'
];

function delete_thread(aRef, aId, aTitle, aClass, aLang) {
	
	var s= "";
	switch(aLang) {
		case 'DE':
			s= mod_forum[0];
			break;
		
		case 'NL':
			s= mod_forum[2];
			break;
			
		case 'EN':
		default:
			s= mod_forum[1];
			break;
	}
	
	var message = unescape(s.replace("#1", aTitle));
	
	if(confirm( message )) {
		var f = document.getElementById(aRef);
		if(f){
			f.elements['postid'].value = aId;
			f.elements['class'].value = aClass;
			f.submit();
		}
		return true;
	} else {
		return false;
	}
}

function edit_post(aRef, aId, aTitle, aClass, aAction) {

	var f = document.getElementById(aRef);
	if(f){
		f.elements['postid'].value = aId;
		f.elements['class'].value = aClass;
		f.action = aAction;
		f.submit();
	}
	return true;
		
}