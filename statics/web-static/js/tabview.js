/*
    tabview.js	
	
	Fumiya Sakai(Just One Factory)
	
*/

$(document).ready(function(){
	
	//Configure
	var CookieName = $.cookie("contents");
	
	//Parameters
	var tabs = $(".tabButton p a");
	var tabActive = "active";
	var tabContents = $(".tabView");
	var tabSection = ".tabView section";
	
	//Tabview Action
	$(tabSection).hide();
	$(tabs).click(function(e){
		e.preventDefault();
		values = $(this).attr("href");
		$(tabSection).hide();
		$(tabSection+values).fadeIn(300);		
		$(tabs).removeClass(tabActive);
		$(this).addClass(tabActive);
		$.cookie("contents", $(this).attr("href") , {expires: 7});
	});
	
	//Cookie Read Settings
	if (CookieName != null) {
		$(tabs).removeClass(tabActive);
		$("a[href="+CookieName+"]").addClass(tabActive);
		$(CookieName).fadeIn(300);
	} else {
		$(".tabButton p a:first").addClass(tabActive);
		$(tabContents+" section:first").fadeIn(300);
	}

});