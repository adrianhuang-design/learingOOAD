<?php

declare(strict_types=1);
// ↑ 嚴格型別模式。寫了這行，PHP 就不會偷偷幫你把 "5" 轉成 int 5。
//   傳錯型別會直接報錯 —— 逼你寫對。Laravel 框架原始碼每個檔案都有這行。

/**
 * 一把吉他。對應書上的 Guitar.java。
 *
 * 這是「貧血」版本：一堆字串屬性 + getter，沒有任何行為。
 * 之後我們會重構它（這正是第 1 章要教的）。
 */
class Guitar
{
    // PHP 8 的「建構式屬性提升」(constructor property promotion)。
    // 在參數前面加上可見性修飾詞 (private/public)，PHP 就會：
    //   (1) 自動宣告同名屬性
    //   (2) 自動把傳入的值 assign 到 $this->那個屬性
    // 等同於舊寫法「先宣告 private string $serialNumber; 再 $this->serialNumber = $serialNumber;」
    // —— 但省掉一堆重複。Laravel 的 DTO / 早期 service 很常見這種寫法。
    public function __construct(
        private string $serialNumber,
        private float $price,
        private string $builder,
        private string $model,
        private string $type,
        private string $backWood,
        private string $topWood,
    ) {
    }

    // getter：把 private 屬性「唯讀地」對外開放。
    // private 代表外部不能直接 $guitar->builder，只能透過方法拿 —— 這就是「封裝」。
    // 回傳型別寫在參數括號後面的冒號：「: string」代表這方法保證回傳字串。
    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    // 其餘 getter 全是同一個模式，只是換屬性名而已：
    public function getBuilder(): string
    {
        return $this->builder;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBackWood(): string
    {
        return $this->backWood;
    }

    public function getTopWood(): string
    {
        return $this->topWood;
    }
}
