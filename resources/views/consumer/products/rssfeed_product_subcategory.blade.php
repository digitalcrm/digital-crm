<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <productsubcategory>
        <category>{{ $procat['procat']->category }}</category>
        <slug>{{ $procat['procat']->slug }}</slug>
        <?php

        if (count($procat['proArr']) > 0) {
            $products = $procat['proArr'];
            foreach ($products as $product) {
        ?>
                <product>
                    <name>{{ $product['name'] }}</name>
                    <description>{{ $product['description'] }}</description>
                    <price>{{ $product['price'] }}</price>
                    <vendor>{{ $product['vendor'] }}</vendor>
                    <slug>{{ $product['slug'] }}</slug>
                    <image>{{ $product['image'] }}</image>
                </product>
        <?php
            }
        }
        ?>
    </productsubcategory>
</urlset>