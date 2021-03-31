<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <product>
        <name>{{ $product['name'] }}</name>
        <description>{{ $product['description'] }}</description>
        <price>{{ $product['price'] }}</price>
        <size>{{ $product['size'] }}</size>
        <units>{{ $product['units'] }}</units>
        <vendor>{{ $product['vendor'] }}</vendor>
        <slug>{{ $product['slug'] }}</slug>
        <image>{{ $product['image'] }}</image>
        <category>{{ $product['category'] }}</category>
        <subcategory>{{ $product['subcategory'] }}</subcategory>
    </product>
</urlset>