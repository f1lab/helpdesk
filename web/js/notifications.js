function chek(){
	$.getJSON("unread",
		function (data){
        // console.log(data);
        if(data.countUnreadedTickets > 0){
                var n = window.webkitNotifications.createNotification(
                        "http://helpdesk-dev.f1lab.ru/img/new.png",
                        "Непрочитанных заявок " + data.countUnreadedTickets,
                        ""
                        );

                n.ondisplay = function() {
                setTimeout(function () {n.cancel();}, 20000);
                };
                n.onclick = function(){
                              window.open("http://helpdesk-dev.f1lab.ru/");
                              n.cancel();
                            }
                n.show();
        }
    }

  )
}

/*if (window.webkitNotifications.checkPermission() == 0){
var timerId = setInterval("chek()", 60000);
}
else{
 console.log("Notifications not supported!");
 var p = window.webkitNotifications.requestPermission();
}*/
