<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<button>Notify me!</button>
<div class="h1" id="h"></div>
<script>
    window.addEventListener('load', function () {
        if (window.Notification && Notification.permission !== "granted") {
            Notification.requestPermission(function (status) {
                if (Notification.permission !== status) {
                    Notification.permission = status;
                }
            });
        }
        var button = document.getElementsByTagName('button')[0];
        button.addEventListener('click', function () {
            if (window.Notification && Notification.permission === "granted") {
                var n = new Notification("Joshua Moreno",
                    {
                        type: "progress",
                        body: "Joshua Moreno - +6643557882\nTest\nTest",
                        tag: 'incomingcall',
                        icon: 'https://morenojoshua.com/yo_reduced.jpg'
                    }
                );
                n.onclick = notificationClick();
                n.onclose = notificationClose();
            }
            else if (window.Notification && Notification.permission !== "denied") {
                Notification.requestPermission(function (status) {
                    if (Notification.permission !== status) {
                        Notification.permission = status;
                    }
                    else {
                        alert("Please allow desktop notifications");
                    }
                });
            }
            else {
                alert("Please allow desktop notifications");
            }
        });
    });

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>