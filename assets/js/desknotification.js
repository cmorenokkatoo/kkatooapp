   window.onload = function() {

    var btnNotification = document.getElementById("btnNotification");

    //Compatibility (Chrome Only)
    window.Notifications = window.webkitNotifications;

    if (window.Notifications) {

        btnNotification.addEventListener("click", function() {

            if (window.Notifications.checkPermission() == 0) { // Allowed
                var icon = "",
                    title = "Notificación de Campaña",
                    message = "Su campaña ha sido enviada.";

                var notification = window.Notifications.createNotification(icon, title, message);

                //Handlers
                notification.onshow = function() {
                    console.log("onshow fired");
                    setTimeout(function() {
                        notification.close();
                    }, 5000);
                };

                notification.onclick = function() {
                    console.log("onclick fired");
                };
                
                notification.onerror = function() {
                    console.log("onerror fired");
                };

                notification.onclose = function() {
                    console.log("onclose fired");
                };

                notification.show();

            }
            else {

                window.Notifications.requestPermission();
            }
        });
    }

    else {
        var span = document.querySelector("header h1 span");
        span.textContent = "Not supported";
        span.style.color = "red";
    }
}