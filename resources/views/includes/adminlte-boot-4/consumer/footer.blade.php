<script>
    var userCartProductCountUrl = "<?php echo url('consumers/ajax/usercartproductscount/{userId}'); ?>";
    var user = '<?php echo (Auth::user() != '') ? Auth::user()->id : 0; ?>';
    $(function() {
        // alert('Footer');
        // alert(user);
        if (user > 0) {
            userCartProductCount(user);
        }

    });


    function userCartProductCount(user) {
        // alert(proid);
        // alert(addtocartUrl);

        $.get(userCartProductCountUrl, {
            'userId': user
        }, function(result) {
            // alert(result);
            $("#cart_count").text(result);
        });
    }
</script>
<footer class="main-footer">
</footer>