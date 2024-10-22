<?php
$employees = [
    ['name' => 'John', 'city' => 'Dallas'],
    ['name' => 'Jane', 'city' => 'Austin'],
    ['name' => 'Jake', 'city' => 'Dallas'],
    ['name' => 'Jill', 'city' => 'Dallas'],
];

$offices = [
    ['office' => 'Dallas HQ', 'city' => 'Dallas'],
    ['office' => 'Dallas South', 'city' => 'Dallas'],
    ['office' => 'Austin Branch', 'city' => 'Austin'],
];

$output = [
    "Dallas" => [
        "Dallas HQ" => ["John", "Jake", "Jill"],
        "Dallas South" => ["John", "Jake", "Jill"],
    ],
    "Austin" => [
        "Austin Branch" => ["Jane"],
    ],
];

// write elegant code using collections to generate the $output array.
//your code goes here..

$output = $employees->merge($offices)->groupBy('city')
    ->mapWithKeys(function ($data, $cityName) {
        $offices = $data->pluck('office')->filter()->values();

        return [$cityName => $offices->mapWithKeys(function ($office) use ($data) {
            return [$office => $data->pluck('name')->filter()->values()->all()];
        })];
    });

