<?php

declare(strict_types=1);

// 沒有 composer autoload，就手動把要用的 class 載進來。
// (在 Laravel 裡這步是 PSR-4 autoload 自動做的，你平常不會看到 require。)
require_once __DIR__ . '/Guitar.php';
require_once __DIR__ . '/Inventory.php';

// --- 建立 Rick 的庫存 ---
$inventory = new Inventory();
$inventory->addGuitar('V95693', 1499.95, 'Fender', 'Stratocastor', 'electric', 'Alder', 'Alder');
$inventory->addGuitar('V9512', 1549.95, 'Gibson', 'Les Paul', 'electric', 'Mahogany', 'Maple');
$inventory->addGuitar('A512', 5495.95, 'Martin', 'D-18', 'acoustic', 'Mahogany', 'Adirondack');

// --- Erin 想要的吉他（注意 builder 是小寫 "fender"）---
$whatErinLikes = new Guitar('', 0, 'fender', 'Stratocastor', 'electric', 'Alder', 'Alder');

// --- 搜尋 ---
$guitar = $inventory->search($whatErinLikes);

if ($guitar !== null) {
    echo "Erin, you might like this {$guitar->getBuilder()} {$guitar->getModel()} guitar:\n";
    echo "  {$guitar->getBackWood()} back and sides,\n";
    echo "  {$guitar->getTopWood()} top.\n";
    echo "  You can have it for only \${$guitar->getPrice()}!\n";
} else {
    echo "Sorry, Erin, we have nothing for you.\n";
}
