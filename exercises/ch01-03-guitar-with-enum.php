<?php

declare(strict_types=1);

/*
 |--------------------------------------------------------------------------
 | §2-3 練習：把 Builder enum 接回 Guitar / Inventory
 |--------------------------------------------------------------------------
 | 上一節你做出了 Builder enum。這節把它「裝進」物件裡：
 |   - Guitar 的 builder 不再是 string，而是 Builder 型別
 |   - Inventory 比對 builder 時，從「字串比對」變成「enum 比對」
 |
 | 重點觀念：enum 的每個 case 是「單例」(singleton)，所以 Builder::FENDER
 |          永遠 === Builder::FENDER。比 enum 直接用 === / !== 就好，
 |          不需要 .equals()，更不會有大小寫問題。
 |
 | 填好 4 個 ___ 空格，然後跑：
 |     php exercises/ch01-03-guitar-with-enum.php
 | 目標：3 個 ✅。
 */

// 這個 enum 上一節你已經學會了，這裡直接給你當「零件」用：
enum Builder: string
{
    case FENDER = 'Fender';
    case GIBSON = 'Gibson';
    case MARTIN = 'Martin';
}

// ─────────────────────────────────────────────────────────────────────────
class Guitar
{
    public function __construct(
        private string $serialNumber,
        private float $price,
        // ── TODO 1：builder 不再是 string。把型別改成我們的 enum。──
        //   提示：型別名稱就是 enum 的名字。
        private Builder $builder,
        private string $model,
    ) {
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    // ── TODO 2：getter 回傳的也不再是 string，回傳型別跟著改成 enum。──
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}

// ─────────────────────────────────────────────────────────────────────────
class Inventory
{
    /**
     * @var Guitar[]
     */
    private array $guitars = [];

    public function addGuitar(
        string $serialNumber,
        float $price,
        // ── TODO 3：這個參數型別也要改成 enum（跟 Guitar 一致）。──
        Builder $builder,
        string $model,
    ): void {
        $this->guitars[] = new Guitar($serialNumber, $price, $builder, $model);
    }

    public function search(Guitar $searchGuitar): ?Guitar
    {
        foreach ($this->guitars as $guitar) {
            // ── TODO 4：比對 builder。──
            //   兩個都是 Builder enum，直接用 !== 比「是不是同一個 case」。
            //   不一樣就 continue 跳過。把 ___ 換成「庫存這把吉他的 builder」。
            if ($searchGuitar->getBuilder() !== $guitar->getBuilder() ) {
                continue;
            }

            // model 還是字串，所以維持字串比對（空字串代表不指定）。
            $model = $searchGuitar->getModel();
            if ($model !== '' && $model !== $guitar->getModel()) {
                continue;
            }

            return $guitar;
        }

        return null;
    }
}

// ═════════════════════════════════════════════════════════════════════════
//  自我檢查 — 不要改下面
// ═════════════════════════════════════════════════════════════════════════

function check(int $no, string $desc, bool $pass): void
{
    echo ($pass ? "✅" : "❌") . " {$no}. {$desc}\n";
}

$inventory = new Inventory();
$inventory->addGuitar('V95693', 1499.95, Builder::FENDER, 'Stratocastor');
$inventory->addGuitar('V9512', 1549.95, Builder::GIBSON, 'Les Paul');

// Erin 現在用 enum 指定 builder，「大小寫」這件事根本不存在了：
$whatErinLikes = new Guitar('', 0, Builder::FENDER, 'Stratocastor');
$found = $inventory->search($whatErinLikes);

check(1, '找到 Erin 的 Fender Strat', $found !== null && $found->getSerialNumber() === 'V95693');

check(2, 'enum 用 === 直接比 case', Builder::FENDER === Builder::FENDER && Builder::FENDER !== Builder::GIBSON);

// ★關鍵★ 舊 bug：傳小寫字串 'fender'。現在型別系統會直接擋下 → 丟 TypeError。
$blocked = false;
try {
    new Guitar('', 0, 'fender', 'Stratocastor');  // 故意傳字串，應該失敗
} catch (\TypeError $e) {
    $blocked = true;
}
check(3, "傳字串 'fender' 被型別系統擋下 (TypeError)，bug 連寫都寫不出來", $blocked);

/*
 |--------------------------------------------------------------------------
 | HINTS（真的卡住再看）
 |--------------------------------------------------------------------------
 | TODO 1: private Builder $builder
 | TODO 2: public function getBuilder(): Builder
 | TODO 3: Builder $builder
 | TODO 4: $guitar->getBuilder()
 |
 | 想一想：第 3 題為什麼這麼有威力？
 |   以前 builder 是 string，'fender'/'Fender'/'fendr' 都塞得進去，
 |   要等到搜尋才發現對不上。現在 builder 是 Builder 型別，
 |   非法值在「建立物件」那一刻就被擋下 —— 錯誤提早到最早能發現的地方。
 */
