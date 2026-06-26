# PHP 語法速查（隨學隨補）

學 Head First OOA&D 過程中碰到的 PHP 語法，集中在這。對照 Laravel 幫助記憶。

---

## 1. 檔案開頭

```php
<?php
declare(strict_types=1);   // 嚴格型別：傳錯型別直接報錯，不偷偷轉型
```
> Laravel 框架每個 PHP 檔開頭都有 `declare(strict_types=1);`。

---

## 2. 類別 class

```php
class Guitar
{
    // 順序：trait → const → 屬性 → 建構子 → 方法
}
```

### 建構式屬性提升（PHP 8）
參數前加可見性修飾詞，PHP 自動「宣告屬性 + 賦值」：

```php
public function __construct(
    private string $serialNumber,   // 等於：宣告 private $serialNumber + $this->serialNumber = $serialNumber
    private float $price,
) {}
```
舊寫法（等價）：
```php
private string $serialNumber;
public function __construct(string $serialNumber) {
    $this->serialNumber = $serialNumber;
}
```

### 可見性
| 修飾詞 | 誰能存取 |
|--------|----------|
| `public` | 任何地方 |
| `private` | 只有這個 class 內部 → **封裝**：外部只能透過 getter 拿 |
| `protected` | 自己 + 子類別 |

---

## 3. 型別宣告

```php
public function getPrice(): float        // 回傳型別：冒號後面
public function setName(string $name): void   // void = 不回傳值
public function find(): ?Guitar          // ?Type = 可能回傳 Type 或 null
private Builder $builder;                 // 屬性型別也能是 enum / class
```

常見型別：`string` `int` `float` `bool` `array` `void`，或某個 class / enum 名稱。

---

## 4. 物件操作

```php
$g = new Guitar(...);        // 建立實例
$g->getBuilder();            // -> 呼叫「實例」的方法/屬性
Builder::FENDER;             // :: 取「類別/enum 本身」的東西（case、static、const）
Builder::cases();            // :: 呼叫 static 方法
```

---

## 5. 陣列 array（PHP 的「清單」與「字典」共用一種型別）

```php
$list = [];                  // 空陣列
$list = ['a', 'b', 'c'];     // 索引陣列（像 List）
$map  = ['name' => 'Rick'];  // 關聯陣列（像 Map / dict）

$list[] = 'd';               // append 到尾端
count($list);                // 長度
$map['name'];                // 取值
```

PHPDoc 補元素型別（PHP 陣列本身不帶）：
```php
/** @var Guitar[] */
private array $guitars = [];
```

### 走訪
```php
foreach ($guitars as $guitar) { ... }              // 值
foreach ($map as $key => $value) { ... }           // 鍵+值
```

### 三個常用「函式式」陣列工具（§2-4 會用）
```php
// filter：留下「回傳 true」的元素
$electric = array_filter($guitars, function (Guitar $g): bool {
    return $g->getType() === Type::ELECTRIC;
});

// map：把每個元素「轉換」成另一個東西，產生新陣列
$names = array_map(function (Guitar $g): string {
    return $g->getBuilder()->value;
}, $guitars);

// array_values：把 filter 後不連續的 key 重新編號成 0,1,2...
$electric = array_values($electric);
```
> Laravel 對照：`collect($guitars)->filter(...)->map(...)->values()` 是同一回事，
> 只是 Collection 把這些包成可串接的鏈式寫法。本書先用原生函式打基礎。

---

## 6. 比較

```php
===  !==     // 嚴格相等/不等（值+型別都要一樣）← 永遠優先用這個
==   !=      // 寬鬆，會自動轉型，容易出錯，少用
```
enum 之間直接用 `===`：case 是 singleton，`Builder::FENDER === Builder::FENDER` 永遠 true。

---

## 7. 字串

```php
'單引號'              // 不解析變數，原樣輸出
"雙引號 {$obj->m()}"  // 解析變數/方法，要包 {$...}
'a' . 'b'             // . 是字串串接（不是 +）
"價格 \${$price}"     // \$ 印出真正的 $；\n 換行
```
> Laravel 對照：`"{$obj->m()}"` ≈ Blade 的 `{{ $obj->m() }}`。

---

## 8. enum（backed enum）

```php
enum Builder: string         // 底層型別 string
{
    case FENDER = 'Fender';   // 每個 case 綁一個值
    case GIBSON = 'Gibson';

    public function label(): string   // enum 裡可以寫方法
    {
        return $this->value;          // $this = 目前這個 case
    }
}
```

| 用法 | 作用 |
|------|------|
| `Builder::cases()` | 回所有 case 的陣列 |
| `Builder::from('Fender')` | 值→case；**找不到丟例外** |
| `Builder::tryFrom('x')` | 值→case；**找不到回 `null`** |
| `$case->name` | 常數名，如 `'FENDER'` |
| `$case->value` | 綁的值，如 `'Fender'` |

> Laravel 對照：fortune 的 enum 都 `use EnumTrait`，提供 `values()`/`toOptions()`，
> 底層就是靠 `cases()` + `->name`/`->value`。

---

## 9. 例外處理 try/catch

```php
try {
    new Guitar('', 0, 'fender', '...');   // 傳字串給 Builder 參數 → 失敗
} catch (\TypeError $e) {
    // 接住型別錯誤
}
```

---

## 10. 載入檔案（無 composer 時）

```php
require_once __DIR__ . '/Guitar.php';   // __DIR__ = 本檔所在資料夾
```
> Laravel 用 PSR-4 autoload 自動載入，平常看不到 `require`。

---

## 附：兩種錯誤分清楚
| 種類 | 何時發生 | 例子 |
|------|----------|------|
| **Parse error** | 文法不通，跑之前就掛 | enum 漏 `enum` 關鍵字、型別位置留 `___` |
| **Error / Exception** | 文法通、執行時才出事 | `___` 被當未定義常數、`TypeError` |
