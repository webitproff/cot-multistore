# cot-multistore
Multistore Build modules and plugins for Cotonti v.0.9.26

<img src="https://raw.githubusercontent.com/webitproff/cot-multistore/refs/heads/main/multistore-cotonti-online-catalog.webp" alt="Multistore on Cotonti CMF">

# [Demo](https://multistore.previewit.work/mstore)
# [Support](https://abuyfile.com/ru/forums/mstore)
# [Source code on GitHub](https://github.com/webitproff/cot-multistore)

---

# 🇬🇧 Multistore for Cotonti v.0.9.26

Features of the Multistore module for [CMF Cotonti](https://github.com/Cotonti/Cotonti) v.0.9.26

Supports multiple online stores from one or many sellers.  
The "Multistore" build implements a virtual concept of a physical marketplace where sellers of different goods can have their own storefront.  

## The "Multistore" build includes plugins:

1. MStore Excel Import - import products from Excel.  
2. Mstore Filter - product filter  
3. Mstore Price - differentiated prices and currencies for products  
4. Mstore User Products - products by sellers  

---

### 1. MStore Excel Import.

This plugin greatly simplifies filling the storefront with products.  
Take any Excel file where the first row contains field names with their relevant content, upload it in the plugin’s admin panel, and you’ll see your Excel headers, for example: ProductCode, Title, Description, Price, CategoryName, UniqueID, ProductOnline, HTMLTitle, ID_user.  
On the left is the list of fields, and on the right a dropdown with your Excel column names. You map them (e.g., Category → CategoryName, Owner ID → ID_user), and then import. The whole procedure (upload, mapping, import) takes less than 2 minutes.  
Required fields: **Category, Title, Text, Owner ID**.  
Be careful with the **Owner ID** field — it’s the user ID of the seller whose products you are importing. If not specified, the seller will be admin.  
Settings include an option to automatically remove all links from product descriptions.  

---

### 2. Mstore Filter

The Mstore Filter plugin provides dynamic filtering capabilities for the Mstore module in Cotonti.  
It allows admins to define custom filter parameters for products and lets users filter products by these attributes (e.g., battery capacity, frame type, color).  
The plugin supports multiple filter types (range, select, checkboxes, radio buttons) and integrates easily with the Mstore module.  
Filtering is performed using SQL queries linking products with filter parameters. Supports conditions for ranges (max value), checkboxes (multi-select), and single values (select/radio). Displays the number of products found or a “no results” message.  

---

### 3. Mstore Price.

Differentiated prices and currencies for wholesale and retail sellers mean that different buyer categories (wholesale, dropshipping, retail) get different prices for the same product. Additionally, different currencies may be used depending on factors and market conditions.  
For example, the product **"E-Scooter X"** may have:  
- Retail price: 125.00 USD  
- Wholesale price: 100.00 EUR  
- MSRP: 12000.00 RUB  
- Pre-order price: 1.20 BTC  
- Dropshipping: 110.00 USDT  

The names of each price and currency are configured in the Mstore Price admin panel.  
When editing a product, all created price types are available, and you can set different prices in different currencies.  

---

### 4. Mstore User Products

The "Sellers and their products" plugin is a simple and convenient tool to display a list of users with their product counts and a detailed list of each seller’s products.  
When viewing the product list, items can be filtered by categories from a dropdown. Only categories where the seller has listed products are shown.  

# Multistore Extension Pack Installation Guide

1. Install the website engine [CMF Cotonti](https://github.com/Cotonti/Cotonti).
2. Download the source code of the "Multistore" build from the [GitHub](https://github.com/webitproff/cot-multistore/) repository and extract the archive to your computer.
3. Upload the contents of the `cot-multistore-main` folder to the root of your website where your CMF Cotonti is already installed.
4. Open `/datas/config.php` and set the frontend and admin themes:

```php
$cfg['defaulttheme'] = '2waydeal'; // Default theme code. frontend
$cfg['admintheme'] = 'cotcp';      // Put custom administration theme name here
```

5. Create a folder named `attacher` in the root of your website for storing images and files.
6. Install the `mstore` module first, then install the remaining plugins:

- `attacher` — attaches images and files to products
- `mstoreautoalias` — generates aliases from product titles
- `mstoreexcelimport` — imports products from Excel
- `mstorefilter` — product filter
- `mstoreprice` — prices and currencies
- `mstoreuserproducts` — lists of sellers and their products on the site

---
At the moment, the source code is being refactored and adapted for compatibility with PHP 8.4+, and therefore there may be various kinds of bugs and errors during the use of the Multistore build. All of them are quite easy to fix and solve, do not be afraid of them and give up if you do not understand something. Write about all the errors and bugs in the Multistore website assembly on the support forum and you will be the first to learn about their debugging.
**For any errors or questions, please post on the "Multistore" support forum [here](https://abuyfile.com/ru/forums/mstore).**

19 Aug 2025
---

# 🇷🇺 Multistore для Cotonti v.0.9.26

Возможности модуля Multistore для Cotonti v.0.9.26

Поддержка нескольких интернет-магазинов как от одного так и многих продавцов.  
Сборка сайта "Multistore" - реализует виртуальную концепцию физического рынка, где могут быть продавцы совершенно разных товаров и у каждого продавца может быть свой прилавок.

## В сборку "Multistore" входят плагины:

1. MStore Excel Import - импорт товаров из Excel документа.  
2. Mstore Filter - фильтр товаров  
3. Mstore Price -  дифференцированные цены и валюты на товар  
4. Mstore User Products - Товары по продавцам  

---

### 1. MStore Excel Import.

Плагин получился действительно упрощающий наполнение витрины товаров. Берем любой Excel документ, в котором в первой строке должны быть названия полей с их ревалентным содержим, загружаем в админке плагина, и в результате видим ваши заголовки из Excel, например, - Код_товара, Название_позиции, Описание, Цена, Название_группы, Уникальный_идентификатор, Продукт_на_сайте, HTML_заголовок, ID_user.  
Слева список полей, а справа выпадающий список названий колонок в вашем Excel. Проставляем "пары", например Category это Название_группы, или Owner ID это будет ID_user, и так по каждому полю, которые будут задействованы для импорта, а затем импортируем. Процедура загрузки файла, сопоставления полей, и завершения импорта занимает меньше двух минут.  
Обязательными полями являются: Category, Title, Text и Owner ID.  
Будьте внимательны с полем Owner ID - это ID пользователя на сайте, на имя которого как продавца вы импортируете товары. Если вы его не укажите то продавцом будет админ.  
В настройках плагина есть опция, которая позволяет удалять все ссылки в описаниях товаров.  

---

### 2. Mstore Filter

Плагин Mstore Filter предоставляет возможности динамической фильтрации для модуля Mstore на CMF Cotonti.  
Он позволяет администраторам задавать пользовательские параметры фильтрации для товаров и дает пользователям возможность фильтровать товары по этим параметрам, таким как пользовательские атрибуты (например, ёмкость батареи, тип рамы или цвет). Плагин поддерживает несколько типов фильтров (диапазон, выбор, чекбоксы, радиокнопки) и легко интегрируется с модулем Mstore.  
Фильтрация списка товаров выполняется через SQL-запросы для связи товаров с параметрами фильтра. Поддерживает условия для диапазонов (максимальное значение), чекбоксов (множественный выбор) и одиночных значений (select/radio). Показывает количество найденных товаров или сообщение об отсутствии результатов.  

---

### 3. Mstore Price.

Дифференцированные цены и валюты для оптовых и розничных продавцов означают, что для разных категорий покупателей (оптовых, дропшипперов и розничных) устанавливаются разные цены на один и тот же товар. Кроме этого, также могут использоваться разные валюты на товар, в зависимости от различных факторов и рыночных условий.  
Например, есть некий товар, с названием "Электросамокат X" и он может иметь такие цены и валюты:  
- Розничная цена 125.00 USD (Доллар США)  
- Оптовая цена 100.00 EUR (Евро)  
- РРЦ (Рекоменд.Розница) 12000.00 RUB (Российский рубль)  
- Стоимость "Под заказ" 1.20 BTC (Bitcoin)  
- Дропшиппинг 110.00 USDT (Tether)  

На самом деле, название каждой цены и каждой валюты заполняется в админке плагина "Mstore Price".  
При редактировании карточки товара "подтягивается" всё, что вы создали в админке плагина, и вы можете устанавливать разные цены в разной валюте.  

---

### 4. Mstore User Products

Плагин "Список продавцов и их товаров" — это простой и удобный инструмент показать список пользователей с количеством товаров у каждого продавца и подробный список самих товаров у конкретного пользователя.  
При просмотре списка товаров можно фильтровать товары по категориям из выпадающего списка. Стоит отметить, что в списке только те категории, в которых данный пользователь размещал товары.  

---
# Порядок установки сборки расширений "Multistore"

1. Устанавливаем движок сайта [CMF Cotonti](https://github.com/Cotonti/Cotonti).
2. Скачиваем исходный код сборки "Multistore" с репозитория на [GitHub](https://github.com/webitproff/cot-multistore/) и распаковываем архив на компьютер.
3. Содержимое папки `cot-multistore-main` загрузить в корень сайта, где уже установлен ваш CMF Cotonti.
4. Открываем `/datas/config.php` и прописываем темы фронт-энда и админки:

```php
$cfg['defaulttheme'] = '2waydeal'; // Default theme code. frontend
$cfg['admintheme'] = 'cotcp';      // Put custom administration theme name here
```

5. В корне сайта создаем папку `attacher` — для хранения картинок и файлов.
6. Сразу устанавливается модуль `mstore`, а затем уже остальные плагины:

- `attacher` — прикрепление к товарам картинок и файлов
- `mstoreautoalias` — генерация алиасов из заголовков
- `mstoreexcelimport` — импорт товаров из Excel
- `mstorefilter` — фильтр товаров
- `mstoreprice` — цены и валюты
- `mstoreuserproducts` — списки продавцов и их товары на сайте

---
В данный момент исходный код рефакторится и адаптируется для совместимости с PHP 8.4 + и поэтому в процессе использования сборки Multistore могут быть различного рода баги и ошибки. Все они достаточно легко фиксятся и решаются, не стоит их бояться и опускать руки, если что-то не понимаете или не получается. Обо всех ошибках и багах в работе сборки сайта Multistore пишите на форуме поддержки и вы первыми узнаете об их отладке.
**Обо всех найденных ошибках или возникших вопросах, просьба писать [на форуме поддержки сборки "Multistore"](https://abuyfile.com/ru/forums/mstore).**

19 Aug 2025
