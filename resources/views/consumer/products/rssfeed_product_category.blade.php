<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
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
</urlset>