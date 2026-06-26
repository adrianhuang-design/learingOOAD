# 第 1 章 — Great Software Begins Here（良好應用程式的基石）

> 全章核心問題：**怎樣才能「每一次」都寫出偉大的軟體？**
> 範例：Rick 的吉他店搜尋工具。

## §1 場景與那個經典 bug

Rick 委外做了庫存 + 搜尋系統：客人講出想要的吉他特徵，`Inventory.search()`
從庫存配對。設計是 `Guitar`（一堆 `String` 屬性 + getter）和 `Inventory`（裝 `List`、提供 `search()`）。

### 出事
客人 Erin 想要 `"fender"` 的 Stratocastor，但 Rick 入庫時寫的是 `"Fender"`。

```java
// 搜尋條件（Erin）            // 庫存（Rick 入庫）
"fender"                        "Fender"
```

`search()` 內：

```java
if ((builder != null) && !builder.equals("") &&
    !builder.equals(guitar.getBuilder()))   // "fender".equals("Fender") == false
    continue;                                // → 符合的吉他被跳過！
```

`String.equals()` **區分大小寫** → 完全符合的吉他被 `continue` 跳掉 → `return null`
→ 客人流失。

### 我的「削尖你的鉛筆」答案
- **bug 原因**：大小寫不一致（`fender` vs `Fender`）。
- **第一個想改的**：把那些自由文字的 `String`（builder/type/wood）**抽成 enum**。

## §1 觀念 — 什麼是「偉大軟體」？

書安排三位工程師，各對一半（多重觀點）：

| 工程師 | 主張 | 對應 |
|--------|------|------|
| Joe  | 那堆 `String` 太可怕，用常量/物件取代 | 抽 enum（= 我的直覺） |
| Jill | `search()` 該回傳「清單」而非單一把 | 回傳 `List` |
| Frank| `Inventory` 與 `Guitar` 耦合太深 | 解耦 / 抽 spec |

**偉大軟體 = 兩件事**
1. 讓**客戶**滿意：做客戶要它做的事（功能對）。
2. 設計/編碼良好：易維護、重用、擴展（**程式設計師**也滿意）。

### 偉大軟體的簡易三步驟（順序是重點）
1. **確認軟體做了客戶要它做的事** ← 蒐集需求 + 分析（先「做對」）
2. **運用基本 OO 原則增加靈活性** ← 消除重複、用對 OO 技術
3. **努力做到可維護、可重用的設計** ← 套設計模式與原則

> 心法：**先讓它「做對」，再讓它「漂亮」。**
> enum 之所以對，不只是乾淨，而是它從根本消滅「大小寫/打錯字」這類
> 讓功能出錯的可能——同時服務第 1 步（功能對）與第 2 步（靈活）。

## §2 動手：用 enum 修掉 bug（PHP）

### §2-1 重現 bug
`code/ch01/` 用 PHP 忠實重寫爛版本：`Guitar`(一堆 string + getter)、`Inventory`(`search()` 用 `===` 字串比對)。
跑 `find_guitar.php` → `Sorry, Erin, we have nothing for you.`，bug 重現（`'fender'` vs `'Fender'`）。

### §2-2 做出 Builder enum（`exercises/ch01-02-builder-enum.php`）
```php
enum Builder: string {
    case FENDER = 'Fender';
    case GIBSON = 'Gibson';
    case MARTIN = 'Martin';
    public function label(): string { return $this->value; }
}
```
學到的 enum 語法：
- `enum 名稱: string { case X = '值'; }`：backed enum，每個 case 綁一個純量值。
- `Builder::cases()` 回所有 case 的陣列。
- `Builder::from('Fender')` 合法值取 case；**`from('不存在')` 丟例外**。
- `Builder::tryFrom('fender')` 非法值**回 `null`**（不丟例外）→ 小寫在這步就被擋掉。
- `$case->name`（常數名 `'FENDER'`）vs `$case->value`（綁的值 `'Fender'`）。

### §2-3 把 enum 接回物件（`exercises/ch01-03-guitar-with-enum.php`）
- `Guitar` 的 `builder` 型別 `string` → `Builder`（屬性、getter 回傳、`addGuitar` 參數都改）。
- `search()` 比對：`$searchGuitar->getBuilder() !== $guitar->getBuilder()`
  —— 兩個都是 enum，`!==` 直接比「是不是同一個 case」(case 是 singleton)，免 `.equals()`、免管大小寫。
- **威力點**：`new Guitar('', 0, 'fender', ...)` 會丟 `TypeError`。
  髒值從「搜尋時才發現」提前到「建立物件就擋下」——**錯誤提早到最早能抓到的地方**。

> 心法回顧：治標(字串比對時防呆) vs 治本(型別系統讓非法值寫不出來)。enum 是治本。

### error 種類小辨
- **Parse error**：文法不通（如 enum 漏寫 `enum` 關鍵字、留 `___` 在型別位置）。
- **Error / Exception**：文法通、執行時才出事（如 `___` 被當成沒定義的常數 → `Undefined constant`）。

## 待續 §2-4
- `search()` 改回傳「清單」(Jill 的觀點)：一次回所有符合的吉他，不只第一把。
- 進一步：把搜尋條件抽成獨立的 `GuitarSpec`，解開 `Inventory` 與 `Guitar` 的耦合(Frank 的觀點)。
