<?php

declare(strict_types=1);

/**
 * Rick 的整個庫存，以及搜尋方法。對應書上的 Inventory.java。
 */
class Inventory
{
    /**
     * 裝所有吉他的清單。PHP 沒有 Java 的 List 介面，
     * 就用最普通的「陣列」當清單。
     *
     * @var Guitar[]   ← 這行 PHPDoc 告訴 IDE / PHPStan：這是「Guitar 物件組成的陣列」。
     *                    PHP 陣列本身不帶元素型別，靠這個註解補強。
     */
    private array $guitars = [];

    /**
     * 加一把吉他進庫存。
     * 注意：這裡 new 出 Guitar 再 push 進陣列。
     */
    public function addGuitar(
        string $serialNumber,
        float $price,
        string $builder,
        string $model,
        string $type,
        string $backWood,
        string $topWood,
    ): void {
        // new 一個 Guitar 物件。參數順序要跟 Guitar 建構子一模一樣。
        $guitar = new Guitar(
            $serialNumber,
            $price,
            $builder,
            $model,
            $type,
            $backWood,
            $topWood,
        );

        // $this->guitars[] = ... 是 PHP 把元素「append 到陣列尾端」的寫法。
        $this->guitars[] = $guitar;
    }

    /**
     * 用序號找特定一把吉他。
     */
    public function getGuitar(string $serialNumber): ?Guitar
    {
        // ?Guitar 回傳型別：可能回傳 Guitar，也可能回傳 null（找不到時）。
        foreach ($this->guitars as $guitar) {
            if ($guitar->getSerialNumber() === $serialNumber) {
                return $guitar;
            }
        }

        return null;
    }

    /**
     * 搜尋：拿一把「範本吉他」($searchGuitar) 當搜尋條件，
     * 逐一比對庫存裡的吉他，回傳第一個全部特徵都吻合的。
     *
     * ⚠️ 這就是書上「有點亂」又「藏著 bug」的方法，我們先忠實照搬。
     */
    public function search(Guitar $searchGuitar): ?Guitar
    {
        foreach ($this->guitars as $guitar) {
            // 一個特徵一個特徵比。只要有一項不合，就 continue 跳過這把。

            $builder = $searchGuitar->getBuilder();
            // 條件：客人有指定 builder（非空字串）且 跟這把不一樣 → 跳過
            if ($builder !== '' && $builder !== $guitar->getBuilder()) {
                continue;
            }

            $model = $searchGuitar->getModel();
            if ($model !== '' && $model !== $guitar->getModel()) {
                continue;
            }

            $type = $searchGuitar->getType();
            if ($type !== '' && $type !== $guitar->getType()) {
                continue;
            }

            $backWood = $searchGuitar->getBackWood();
            if ($backWood !== '' && $backWood !== $guitar->getBackWood()) {
                continue;
            }

            $topWood = $searchGuitar->getTopWood();
            if ($topWood !== '' && $topWood !== $guitar->getTopWood()) {
                continue;
            }

            // 撐過所有 continue → 這把全部吻合 → 回傳它。
            return $guitar;
        }

        return null;
    }
}
