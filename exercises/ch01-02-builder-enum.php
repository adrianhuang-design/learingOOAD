<?php

declare(strict_types=1);

/*
 |--------------------------------------------------------------------------
 | §2-2 練習：用 enum 消滅「大小寫 / 打錯字」bug
 |--------------------------------------------------------------------------
 | 目標：把吉他的 builder（製造商）從「自由字串」升級成「只能是合法值」的 enum。
 |
 | 怎麼做：把下面 5 個【TODO】的空格填好（空格標成 ___），然後跑：
 |     php exercises/ch01-02-builder-enum.php
 | 全部填對會看到 5 個 ✅。看到 ❌ 或 parse error 就回頭看該題提示。
 |
 | 語法小抄都在每個 TODO 上方。卡住就看檔案最下面的「HINTS」。
 */

// ─────────────────────────────────────────────────────────────────────────
// TODO 1：宣告一個「backed enum」(底層值是 string)，名字叫 Builder。
//   語法 →   enum 名稱: 底層型別
//   (backed enum 就是「每個成員都綁一個純量值」的 enum，這裡綁 string)
// 把下面這行的 ___ 補成正確的關鍵字與型別：
// ─────────────────────────────────────────────────────────────────────────
___ Builder: ___
{
    // ─────────────────────────────────────────────────────────────────────
    // TODO 2：定義 case。每個 case = 一個合法製造商。
    //   語法 →   case 常數名稱 = '對外字串值';
    //   第一個已示範。請「照樣」補齊 GIBSON、MARTIN 兩個（值見註解）。
    // ─────────────────────────────────────────────────────────────────────
    case FENDER = 'Fender';
    ___ GIBSON = 'Gibson';
    case MARTIN = ___;   // 值應該是字串 'Martin'

    // ─────────────────────────────────────────────────────────────────────
    // TODO 3：補一個方法，回傳「給人看的標籤」。這裡先直接回傳這個 case 的值。
    //   提示：在 enum 方法裡，$this 就是「目前這個 case」，
    //         它有兩個內建屬性： ->name (常數名，如 'FENDER')
    //                          ->value (綁的值，如 'Fender')
    //   這題要回傳「值」。
    // ─────────────────────────────────────────────────────────────────────
    public function label(): string
    {
        return $this->___;
    }
}

// ═════════════════════════════════════════════════════════════════════════
//  自我檢查 — 不要改下面，填對上面這裡就會全 ✅
// ═════════════════════════════════════════════════════════════════════════

function check(int $no, string $desc, bool $pass): void
{
    echo ($pass ? "✅" : "❌") . " {$no}. {$desc}\n";
}

// 1. cases() 會回傳「所有 case 的陣列」，你定義了 3 個 → 應為 3
check(1, 'enum 有 3 個 case', count(Builder::cases()) === 3);

// 2. from('Fender') 用合法值取得對應 case
check(2, "from('Fender') 取得 FENDER", Builder::from('Fender') === Builder::FENDER);

// 3. ★關鍵★ tryFrom('fender') 用「小寫」非法值 → 回 null（bug 在這一步就被擋掉！）
check(3, "tryFrom('fender') 小寫被擋成 null", Builder::tryFrom('fender') === null);

// 4. 你寫的 label() 應回傳值字串
check(4, 'GIBSON->label() === "Gibson"', Builder::GIBSON->label() === 'Gibson');

// 5. name 與 value 的差別
check(5, 'name 是常數名 / value 是綁的值',
    Builder::FENDER->name === 'FENDER' && Builder::FENDER->value === 'Fender');

/*
 |--------------------------------------------------------------------------
 | HINTS（真的卡住再看）
 |--------------------------------------------------------------------------
 | TODO 1: 關鍵字是 enum，底層型別是 string → 「enum Builder: string」
 | TODO 2: 每個 case 都用 case 開頭；字串要用引號 → 「case MARTIN = 'Martin';」
 | TODO 3: 回傳值用 $this->value
 |
 | 想一想：from() 與 tryFrom() 差在哪？
 |   - from('不存在') 會「丟例外」
 |   - tryFrom('不存在') 會「回 null」
 |   （這跟 Laravel collection 的 first() vs firstOrFail 的味道類似）
 */
