<script>
    var aid = "<?php echo Auth::user()->id; ?>";
    var url = "<?php echo url('admin/notifications/unread/{aid}'); ?>";
    $(function() {
        notificationTimer();
        setInterval(notificationTimer, 60000);

//        alert(aid + " " + url);



    });

    function notificationTimer() {
//        alert('timer');
        $.get(url, {'aid': aid}, function(result, status) {
//            alert(result + " " + status);
            var res = eval("(" + result + ")");
            $("#spanUnread").text(res.unread);
            $("#notUl").html(res.notlist);
        });
    }
</script>