<?php

$products = [
    ["name" => "Keyboard" , "price" => 30, "inStock" => true],
    ["name" => "Mouse", "price" => 15, "inStock" => false],
    ["name" => "Monitor", "price" => 120,"inStock" => true],
    ["name" => "USB Cable" , "price" => 8, "inStock" => false],
];

function filter_products(array $products): array
{
    $result = [];
    foreach ($products as $product){
        if ($product["inStock"]){
            $result[] = $product;
        }
    }
    return $result;
}

$filtered = filter_products($products);

$filtered_ez = array_map(fn($product)=>$product["name"], array_filter($products, fn($product)=> $product["inStock"]));


print "Available products are ".implode(", ",$filtered_ez)."\n";

$price_avg = array_sum(array_map(fn($product) => $product["price"], $products)) / count($products);

print "Price avg is $price_avg";

print "\n";

function my_function(?int $value) : void
{
    print  "The value is $value\n";
}

$my_str = "viktor";
$my_str[1] = "a";

print $my_str;

print $my_str[1];

my_function(null);

print_r($filtered_ez);

