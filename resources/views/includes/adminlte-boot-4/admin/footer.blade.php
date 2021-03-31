<footer class="main-footer">
    <!-- <strong>Copyright &copy; 2014-2018 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.0-beta.1
    </div> -->
</footer>
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
        $.get(url, {
            'aid': aid
        }, function(result, status) {
            //            alert(result + " " + status);
            var res = eval("(" + result + ")");
            $("#spanUnread").text(res.unread);
            $("#notUl").html(res.notlist);
        });
    }
</script>