<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<productcategories>
    @foreach ($procats as $procat)
    <productcategory>
        <category>{{ $procat->category }}</category>
        <slug>{{ $procat->slug }}</slug>
        <?php
        if ($procat->tbl_product_subcategory != '') {
        ?>
            <subcategory>
                <?php
                $subcategorys = $procat->tbl_product_subcategory;
                foreach ($subcategorys as $subcategory) {
                ?>
                    <category>{{ $subcategory->category }}</category>
                    <slug>{{ $subcategory->slug }}</slug>
                <?php
                }
                ?>
            </subcategory>
        <?php
        }
        ?>
    </productcategory>
    @endforeach
</productcategories>