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

{{Form::open(['action'=>['Consumer\AjaxController@formGetProducts'],'method'=>'Get','enctype'=>'multipart/form-data','id'=>'formGetProducts'])}}
@csrf
<input type="hidden" name="minPrice" id="minPrice" value="" />
<input type="hidden" name="maxPrice" id="maxPrice" value="" />
<input type="hidden" name="searchWord" id="searchWord" value="" />
<input type="hidden" name="catId" id="catId" value="" />
<input type="hidden" name="subcatId" id="subcatId" value="" />
<input type="hidden" name="sortType" id="sortType" value="" />
<input type="hidden" name="searchType" id="searchType" value="" />
{{Form::close()}}
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
    var procatId = "<?php echo $details['procatId']; ?>";
    var prosubcatId = "<?php echo $details['prosubcatId']; ?>";
    var sortby = "<?php echo $details['sortby']; ?>";

    var titlehead = "<?php echo $details['title']; ?>";

    $(document).ready(function() {

        // alert(procatId + ' ' + prosubcatId);

        // alert(titlehead);

        $("#keyword").val(searchkeyword);
        $("#sortby").val(sortby);
        $("#titlehead").text(titlehead);

        $(document).keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                // alert('You pressed a "enter" key in somewhere');
                // applyfilter();
                submitForm('keyword');
            }
        });


        if ((procatId != '') && (prosubcatId == '')) {
            // alert("one");
            $(".subcatmenu").removeClass('active');
            $(".catmenu ul").css('display', 'none');
            $(".catmenu").removeClass('menu-open');
            $("#" + procatId).addClass('menu-open');
            $("#" + procatId + " ul").css('display', 'block');
        }

        if ((procatId != '') && (prosubcatId != '')) {
            $(".catmenu ul").css('display', 'none');
            $(".catmenu").removeClass('menu-open');
            $("#" + procatId).addClass('menu-open');
            $("#" + procatId + " ul").css('display', 'block');
            $("#" + prosubcatId + " a").addClass('active');
        }

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
                // applyfilter();
                submitForm('price');
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
            // applyfilter();
            submitForm('keyword');
        });

        $("#sortby").change(function() {

            submitForm('sortby');

            // // alert($(this).val());
            // var sortby = $(this).val();
            // var title = '';
            // if (sortby == 'recent') {
            //     title = 'Sort by Recent';
            // }
            // if (sortby == 'popular') {
            //     // Popular
            //     title = 'Sort by Popular Products';
            // }
            // if (sortby == 'priceAsc') {
            //     // Price - Low to High
            //     title = 'Sort by Price - Low to High';
            // }
            // if (sortby == 'priceDesc') {
            //     // Price - High to Low
            //     title = 'Sort by Price - High to Low';
            // }

            // $("#titlehead").text(title);

            // applyfilter();
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
            $("#keyword").blur();
            $("#productsData").html(result);
        });

    }



    function submitForm(searchType) {
        var min = $("#slider-range").slider("values", 0);
        var max = $("#slider-range").slider("values", 1);

        // var procatId = 0; 
        // var prosubcatId = 0; 
        var keyword = $("#keyword").val();
        var sortby = $("#sortby").val();


        $("#minPrice").val(Number(min));
        $("#maxPrice").val(Number(max));
        $("#searchWord").val(keyword);
        $("#catId").val(Number(procatId));
        $("#subcatId").val(Number(prosubcatId));
        $("#sortType").val(sortby);
        $("#searchType").val(searchType);

        $("#formGetProducts").submit();
    }
</script>
@endsection