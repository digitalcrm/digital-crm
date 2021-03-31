@extends('layouts.adminlte-boot-4.consumer')

@section('content')
<style type="text/css">
    /* Supress pointer events */
    #slider-range {
        pointer-events: none;
    }

    /* Enable pointer events for slider handle only */
    #slider-range .ui-slider-handle {
        pointer-events: auto;
    }

    .clsDatePicker {
        z-index: 100000;
    }
</style>
<style>

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <br>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left small">
                        <?php
                        if ($details['category'] != '') {
                            echo '<li class="breadcrumb-item"><a class="text-muted" href=' . url('/shop/sitemap/products/category/' . $details['category']) . '>' . $details['category'] . '</a></li>';
                        }
                        if ($details['subcategory'] != '') {
                            echo '<li class="breadcrumb-item"><a class="text-muted" href=' . url('/shop/sitemap/products/subcategory/' . $details['subcategory']) . '>' . $details['subcategory']  . '</a></li>';
                        }

                        ?>

                    </ol>
                    <br>
                    <h1 class="m-0 text-dark" id="titlehead">
                        Products
                    </h1>
                    @if(session('success'))
                    <div id="alertSuccess" class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div id="alertError" class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <select id="sortby" name="sortby">
                            <option value="recent">Recent</option>
                            <option value="popular">Popular</option>
                            <option value="priceAsc">Price - Low to High</option>
                            <option value="priceDesc">Price - High to Low</option>
                        </select>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- /.container-fluid -->
    <input type="hidden" name="sortVal" id="sortVal" />
    <input type="hidden" name="catVal" id="catVal" />
    <input type="hidden" name="subcatVal" id="subcatVal" />
    <input type="hidden" name="priceVal" id="priceVal" />
    <!-- Main content -->
    <section class="content px-4">
        @if(session('success'))
        <div id="alertSuccess" class='alert alert-success'>
            {{session('success')}}
        </div>
        @endif

        @if(session('error'))
        <div id="alertError" class='alert alert-danger'>
            {{session('error')}}
        </div>
        @endif

        <input type="hidden" value="0" name="skipRec" id="skipRec">
        <div id="productsData" class="">
            @include('consumer.products.list')
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
    var productUrl = "<?php echo url('shop/ajax/getproducts/{skip}/{min_price}/{max_price}/{procatId}/{prosubcatId}/{keyword}/{sortby}'); ?>";
    var buynowUrl = "<?php echo url('shop/ajax/ajaxbuynow/{proId}'); ?>";
    var maxPrice = "<?php echo $details['maxPrice']; ?>";
    var prosubcatUrl = "<?php echo url('shop/ajax/getprosubcatoptions/{procatId}'); ?>";
    var searchkeyword = "<?php echo $details['keyword']; ?>";
    $(document).ready(function() {

        $("#keyword").val(searchkeyword);

        // alert($('#skipRec').val());

        // alert(maxPrice);
        $("#slider-range").mousedown(function() {
            return false;
        }); // For mousedown
        $("#slider-range").scroll(function() {
            return false;
        }); // For scrolling with the trackpad

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: maxPrice,
            values: [0, maxPrice],
            slide: function(event, ui) {
                // alert("$" + ui.values[0] + " - $" + ui.values[1]);
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                // applyfilter();
            },
            stop: function(event, ui) {
                // alert("$" + ui.values[0] + " - $" + ui.values[1]);
                applyfilter();
            }
        });

        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));

        $("#applyFilter").click(function() {
            // var min = $("#slider-range").slider("values", 0);
            // var max = $("#slider-range").slider("values", 1);
            // alert(min + ' ' + max);
            // alert('applyfilter');
            applyfilter();
        });

        $("#procatId").change(function() {
            var catId = $(this).val();
            // alert(catId);
            $.get(prosubcatUrl, {
                'procatId': catId,
            }, function(result) {
                // alert(result);
                $("#prosubcatId").html(result);
                applyfilter();
            });
        });

        $("#prosubcatId").change(function() {
            applyfilter();
        });

        $("#keywordSearch").click(function() {
            applyfilter();
        });

        $("#sortby").change(function() {
            // alert($(this).val());
            var sortby = $(this).val();
            var title = '';
            if (sortby == 'recent') {
                title = 'Sort by Recent';
            }
            if (sortby == 'popular') {
                // Popular
                title = 'Sort by Popular Products';
            }
            if (sortby == 'priceAsc') {
                // Price - Low to High
                title = 'Sort by Price - Low to High';
            }
            if (sortby == 'priceDesc') {
                // Price - High to Low
                title = 'Sort by Price - High to Low';
            }

            $("#titlehead").text(title);

            applyfilter();
        });


    });


    function addtoCartProduct(proid) {
        // alert(proid);
        // alert(addtocartUrl);

        $.get(addtocartUrl, {
            'proId': proid
        }, function(result) {
            // alert(result);
            var res = eval("(" + result + ")");

            if (res.status == 'success') {
                $("#cart_count").text(res.proCount);
                alert('Product added to cart successfully');
            }

            if (res.status == 'exist') {
                alert('Already added to cart');
            }

            if (res.status == 'error') {
                alert('Error occurred. Please try again later');
            }
        });
    }


    function buynowProduct(proid) {

        // alert(proid);

        $.get(buynowUrl, {
            'proId': proid
        }, function(result) {
            // alert(result);
            location.replace(result);
        });
    }

    function applyfilter() {

        var min = $("#slider-range").slider("values", 0);
        var max = $("#slider-range").slider("values", 1);
        // alert(min + ' ' + max);

        var procatId = 0; //$("#procatId").val()
        var prosubcatId = 0; //$("#prosubcatId").val()

        var keyword = $("#keyword").val();
        // alert(keyword);
        var sortby = $("#sortby").val();
        // alert(sortby);

        var skipRec = 0;

        // alert(min + ' ' + max + ' ' + procatId + ' ' + prosubcatId + ' ' + keyword + ' ' + sortby);

        $.get(productUrl, {
            'skip': skipRec,
            'min_price': min,
            'max_price': max,
            'procatId': procatId,
            'prosubcatId': prosubcatId,
            'keyword': keyword,
            'sortby': sortby,
        }, function(result) {
            // alert(result);
            $("#productsData").html(result);
        });

    }

    function getSubcatProducts(subcatId, procatslug) {

        var min = $("#slider-range").slider("values", 0);
        var max = $("#slider-range").slider("values", 1);
        // alert(min + ' ' + max);

        var procatId = 0; //$("#procatId").val()
        var prosubcatId = subcatId;
        var keyword = $("#keyword").val();
        var sortby = $("#sortby").val();

        var skipRec = 0;


        $.get(productUrl, {
            'skip': skipRec,
            'min_price': min,
            'max_price': max,
            'procatId': procatId,
            'prosubcatId': prosubcatId,
            'keyword': keyword,
            'sortby': sortby,
        }, function(result) {
            // alert(result);
            $("#productsData").html(result);
        });
    }
</script>
@endsection