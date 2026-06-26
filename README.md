# Head First 物件導向分析與設計 — 學習筆記

跟著《深入淺出物件導向分析與設計》(Head First Object-Oriented Analysis & Design) 從第 1 章開始學習的紀錄。

- **範例語言**：PHP 為主（讀書上 Java 原文，練習用 PHP 重寫）
- **學習方式**：一次一小節，讀概念 → **做填空練習** → 整理筆記 → commit
- **練習形式**：教練(Claude)搭好程式骨架並「挖空格」(`TODO`)，學習者親手填空、自己跑、看自我檢查結果。骨架要附自我檢查 assertion，填對會看到 ✅。完整答案不直接給，貼上來對答案。

## 給 AI 教練的接手說明（新 session 先讀這段）

> 跨 session 不會自動記得，請依此接手。完整脈絡讀 `PROGRESS.md` + `notes/`。

- **學習者**：Adrian，寫 Laravel/PHP，要同時學 OOA&D 觀念 + 練 PHP 語法。回覆用繁體中文，程式術語用英文。說人話、先給結論。
- **教學方式**：一次一小節。**開新練習前先講一段相關 PHP 語法概念**（怎麼寫、長怎樣），再給「填空練習檔」(`exercises/`，挖 `TODO`+`___`、附自我檢查 assertion)。**完整答案不主動給**，他填完跑、貼結果，教練**實際 `php` 跑過**再對答案、講錯在哪。隨時把 PHP 語法對照 Laravel。
- **目前進度**：第 1 章。已完成 §1、§2-1~§2-3（用 `Builder` enum 修好搜尋 bug）。**下一步 §2-4**：`Inventory::search()` 改回傳「清單」(一次回所有符合的吉他)，並把搜尋條件抽成 `GuitarSpec` 解耦。§2-4 要先教 `array_filter`/`array_map`/`foreach 累加`。
- **git 規則**：commit 用 `git -c user.name="adrian_huang" -c user.email="adrian_huang@shepherdtech.group"`。一節一 commit，更新 `PROGRESS.md`。

## 目錄結構

| 路徑 | 內容 |
|------|------|
| `notes/` | 每章的觀念整理筆記 |
| `code/` | 各章程式範例（PHP 重寫，自己跑過） |
| `exercises/` | 「動動腦 / 削尖你的鉛筆」習題解答 |
| `PROGRESS.md` | 學習進度與心得 log |

## 章節進度

| # | 章名 | 主題 | 狀態 |
|---|------|------|------|
| 1 | Great Software Begins Here | 寫出好軟體的三步驟（Rick 的吉他店） | ⏳ 進行中 |
| 2 | Give Them What They Want | 蒐集需求 | ⬜ |
| 3 | I Love You, You're Perfect... Now Change | 需求會變 | ⬜ |
| 4 | Taking Your Software into the Real World | 分析 | ⬜ |
| 5 | Good Design = Flexible Software | 好設計與 OO 原則 | ⬜ |
| 6 | Solving Really Big Problems | 處理大型問題 | ⬜ |
| 7 | Architecture | 架構 | ⬜ |
| 8 | Design Principles | 設計原則 | ⬜ |
| 9 | Iterating & Testing | 迭代與測試 | ⬜ |
| 10 | The OOA&D Lifecycle | 整合全書 | ⬜ |

> 章名與順序會在讀到時依書本實際內容微調。
