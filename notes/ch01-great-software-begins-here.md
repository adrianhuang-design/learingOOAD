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

## 待續
- §2：實際動手——把 `String` 換成 enum、`search()` 改回傳 `List`、抽出 `GuitarSpec` 解耦。
- 用 PHP 重寫 `Guitar` / `Inventory`，重現 bug 再修。
