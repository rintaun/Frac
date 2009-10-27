// this is the real deal.
addEvent(window,'load',function(evt) {
	// everything we want to do to the page goes here. EVERYTHING. lawl

	var projectlist = $('.project'); // get all of our project listings

	for (i=0;i<projectlist.length;i++){
		projectlist[i].addEvent('click',function(evt) {
			var caller=$e(evt).caller;
			while (caller.nodeName != "DIV"){
				caller=caller.parentNode;
			}
			id = caller.id.substr(caller.id.indexOf("_")+1)
			window.location=basepath+'/projects/display/'+id;
		});
	}
});
