function M_redirect(MobileURL, Home){
	try {
		// avoid loops within mobile site
		if(document.getElementById("dmRoot") != null)
		{
			return;
		}
		var CurrentUrl = location.href
		var noredirect = document.location.search;
		if (noredirect.indexOf("no_redirect=true") < 0){
			if ((navigator.userAgent.match(/(iPhone|iPod|BlackBerry|Android|webOS|Windows CE|IEMobile|Opera Mini|Opera Mobi|HTC|LG-|LGE|SAMSUNG|Samsung|SEC-SGH|Symbian|Nokia|PlayStation|PLAYSTATION|Nintendo DSi)/i)) ) {
				location.replace(MobileURL);
				if(Home){
					
				}
				else
				{
					location.replace(MobileURL);
				}
			}
		}	
	}
	catch(err){}
}