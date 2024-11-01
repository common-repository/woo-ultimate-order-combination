=== Ultimate Order Combination ===
Contributors: fahadmahmood
Tags: woocommerce, orders, combine, merge, consolidate
Requires at least: 4.4
Tested up to: 6.5
Stable tag: 1.8.7
Requires PHP: 7.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
A great plugin to combine WooCommerce orders.

== Description ==
* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)
* Project URI: <http://androidbubble.com/blog/wordpress/plugins/woo-ultimate-order-combination>

WooCommerce is an awesome eCommerce plugin that allows you to sell anything. Ultimate Order Combination allows you to combine different orders into one order with all meta-data intact with it. You can remove all original orders related to combined order. You have the option to move orders to trash or delete orders permanently. Inventory stats will not be affected because of orders combination.


 
= How it works? =
[youtube http://www.youtube.com/watch?v=HAMuzSm0Jd0]


== Screenshots ==
1. How can i merge orders in basic version?
2. General setting.
3. Combined orders list.
4. Trashed orders > Restore/Delete permanently.
5. Proceed with selected status orders.
6. Remove original orders.
7. Retained meta keys from selected orders.
8. Automation - Settings
9. Automation - Rules
10. Combine / separate shipping.
11. Items with different attributes and values.
12. What to do with existing orders?
13. Order consideration and Time period
14. Double tick is for combined orders, Paw icon is used for sniffed/checked orders.
15. Combine with Gravity Form product based meta values.
16. Meta values selection for Gravity Forms.
17. Combine order related to the logged-in users only.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. There will now be a Split icon in the to Woocommerce  order overview page within the order actions.


== Frequently Asked Questions ==

= How can we restore combined orders? = 

[youtube http://www.youtube.com/watch?v=KOWb8-Ku5KY]

= What are the trashed orders and how can we restore it? = 

[youtube http://www.youtube.com/watch?v=GIyQ38NYnbk]

= How to move selected orders to trash after combination? = 

There are three ways to combine orders. 

1) Orders list > bulk options
[youtube http://www.youtube.com/watch?v=RMpGLphGp8w]

2) General Settings > Analyze

3) Automation Tab

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]
[youtube http://www.youtube.com/watch?v=eDhYqO2XieE]

All above methods allow you to optionally move to trash option.

= What premium version is about? =
[youtube http://www.youtube.com/watch?v=bn6wNjdNqyE]

= How can i merge orders in basic version? =
[youtube https://youtu.be/RDRvBUly2Go]

= How does it work with orders having variations? =
[youtube https://youtu.be/OZy9F5FKKsY]

= Is this compatible with WooCommerce? =
Yes

= Can we split orders with this plugin? =
No

= How it works? =
Under WooCommerce Menu > Orders List. You have a dropdown for bulk actions, there you will see an option to combine orders. This is the basic feature which is provided for FREE in this plugin. Other methods or options are added or will be added later, can be premium or free. Kindly don't misunderstand as it's an incomplete plugin. Basic plugin was released with one basic feature to combine orders.

[youtube http://www.youtube.com/watch?v=DsxXj-DuBW4]

= Can we combine orders by same customer id? =
Yes, these settings are available in premium version.

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]

= Can we combine orders by same customer email? =
Yes, these settings are available in premium version.

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]

= Can we combine orders by same phone number enetered during checkout? =
Yes, these settings are available in premium version.

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]

= Can we combine orders by identical billing address? =
Yes, these settings are available in premium version.

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]

= Can we combine orders by identical shipping address? =
Yes, these settings are available in premium version.

[youtube http://www.youtube.com/watch?v=GzNC6SmprHc]

= We have some items with different attributes and values, can these items be separated? =

Yes, as per choice these items can be separated. There is a checkbox "Keep Order Items Separate using Attributes and Values" in purple are on general settings tab. If this checkbox is On, the plugin will separate items with different attributes and values.

[youtube http://www.youtube.com/watch?v=Z7uh5enmCv4]

= Automation | Combined Orders | Trashed Orders =

[youtube http://www.youtube.com/watch?v=WT7iKybHld8]

= Split / Combine - Gravity Forms =

[youtube http://www.youtube.com/watch?v=NlM72V458L4]


== Changelog ==
= 1.8.7 =
* Fix: Action hooks added and listed under the documentation tab. [16/05/2024][Thanks Silin Goh / Robert]
= 1.8.6 =
* Fix: Analyze process improved with the posted value of order status selected saved and loaded as default. [14/05/2024][Thanks Benjamin Belaga]
= 1.8.5 =
* Fix: Analyze query improved with the order status input as an array instead of string. [14/05/2024][Thanks Benjamin Belaga]
= 1.8.4 =
* New: What to do with existing orders? Change Order Status option added for the manual combine section. [12/05/2024][Thanks Benjamin Belaga]
= 1.8.3 =
* New: What to do with existing orders? Change Order Status option added for the manual combine section. [12/05/2024][Thanks Benjamin Belaga]
= 1.8.2 =
* New: Email notification filter hook introduced and implemented. [08/05/2024][Thanks Benjamin Belaga]
= 1.8.1 =
* Fix: Cron Settings: Edit Order Page / Admin Panel. [26/04/2024][Thanks Philipp Kulka | kulkreate]
= 1.8.0 =
* Fix: Cron Settings: Orders List Page / Admin Panel. [26/04/2024][Thanks Philipp Kulka | kulkreate]
= 1.7.9 =
* Cron job auto refined. [25/04/2024][Thanks Benjamin Belaga & Philipp Kulka | kulkreate]
= 1.7.8 =
* Cron job controls refined. [23/04/2024][Thanks Nick Sotiropoulos]
= 1.7.7 =
* Cron job controls refined. [16/04/2024][Thanks Nick Sotiropoulos]
= 1.7.6 =
* Cron job set to unlimited orders picked for the combination instead of one at a time. [06/04/2024][Thanks Nick Sotiropoulos]
= 1.7.5 =
* Order object wrapped with the is_object function check. [12/03/2024][Thanks Tan Yongyi]
= 1.7.4 =
* Cron controls are improved. [08/02/2024][Thanks Nick Sotiropoulos]
= 1.7.3 =
* Cron controls are refined. [19/01/2024]
= 1.7.2 =
* New: Cron controls are added. [18/01/2024][Thanks Nick Sotiropoulos]
= 1.7.1 =
* Fix: Empty values won't be considered for the comparison in meta keys and values cases. [06/01/2023][Thanks Mehar Usman]
= 1.7.0 =
* Fix: Undefined variable original_order in functions-plus.php line 812, fixed. [23/11/2023][Thanks Ivo Protrkic]
= 1.6.9 =
* Fix: Fatal error: Uncaught Error: Call to a member function get_items() on bool while getting order object. [16/11/2023][Thanks @elramos]
= 1.6.8 =
* New: Clock based cron job and sort order items by product categories. [08/11/2023][Thanks Giovanni Frino]
* New: Custom Meta Key Column Sorting option added as a new feature. [11/11/2023][Thanks Nick Sotiropoulos]
= 1.6.7 =
* New: Time based cron job. Meta key based combining rule added and meta key/value based filter added. [01/11/2023][Thanks Nick Sotiropoulos]
= 1.6.6 =
* Fix: Order status for combined orders improved. [01/11/2023][Thanks Niklas Eriksson]
= 1.6.5 =
* Fix: Order status for combined orders revised. [26/10/2023][Thanks Niklas Eriksson]
= 1.6.4 =
* Fix: meta_key _paid_date replaced with the date_query. [14/09/2023][Thanks Arminas Adomaitis & Mehar Usman]
= 1.6.3 =
* Fix: PHP Fatal error:  Uncaught TypeError: Illegal offset type for trim(). [07/09/2023][Thanks Arminas Adomaitis]
= 1.6.2 =
* Fix: Restoration of merged orders improved. [18/05/2023][Thanks Tan Yongyi]
= 1.6.1 =
* New: Order Status (for Combined Orders) - Target feature added under automation tab. [16/05/2023][Thanks Niklas Eriksson]
= 1.6.0 =
* Fix: Merged orders appearance under combined orders tab revisited. [16/05/2023][Thanks Tan Yongyi]
= 1.5.9 =
* Fix: Merged orders all existing meta keys maintianed in a shape of array with prefix of a double underscore "__" ensured. [15/05/2023][Thanks Niklas Eriksson]
= 1.5.8 =
* New: Auto combination by payment method and multiple automation layers introduced. [29/11/2022][Thanks Giovanni Frino & Ivo Protrkic]
* New: Auto combination by shipping added. [06/12/2022][Thanks Ivo Protrkic]
* New: Auto combination bracket value provided under the cron job tab as a variable. [20/03/2023][Thanks to Marlon Franosch]
= 1.5.7 =
* Fix: PHP implode() related array variable check. [29/10/2022][Thanks Thomas Holtermans]
= 1.5.6 =
* Fix: PHP Warning: Undefined variable $order_id. [31/08/2022][Thanks Tom Reisinger]
= 1.5.5 =
* Fix: Merged orders with zero priced item as a conflict among identical order items. [23/08/2022][Thanks Niklas Eriksson]
= 1.5.4 =
* Fix: Billing and Shipping address will be considered without including email addresses. [14/06/2022][Thanks Niklas Eriksson]
= 1.5.3 =
* New: Clone order and customer notes options added. [11/06/2022][Thanks Niklas Eriksson]
* New: Clone shipping option added. [11/06/2022][Thanks Niklas Eriksson]
= 1.5.2 =
* Fix: After merge, empty white screen related issue. [10/06/2022][Thanks Niklas Eriksson]
= 1.5.1 =
* Fix: Exception: Invalid order. [15/05/2022][Thanks reznik123]
= 1.5.0 =
* Fix: Stock reduction related issue resolved on edit-order items action trigger. [08/05/2022][Thanks reznik123]
= 1.4.9 =
* Fix: Pro version combined orders tab refinement. [30/04/2022][Thanks Diana Vlastuin]
= 1.4.8 =
* Fix: Auto Combine functionality revised and debug system improved. [28/04/2022][Thanks Markus Kauppinen]
= 1.4.7 =
* Fix: Combine by same customer user with analyze functionality improved. [28/04/2022][Thanks Markus Kauppinen]
= 1.4.6 =
* Issue: Refunded items were being included in combining process - Fixed. [16/02/2022][Thanks Tom Reisinger]
* New: Instead of moving existing orders to trash, order status can be update for them optionally. [18/03/2022][Thanks Tin Fan Chung]
= 1.4.5 =
* Issue: order combine not working at all - Fixed. [01/02/2022][Thanks qtfish & hillviewhp]
= 1.4.4 =
* Newly added essentials file related tweaks. [25/12/2021][Thanks to po64]
* CRITICAL Uncaught TypeError: Unsupported operand types: string * float, Fixed. [25/12/2021][Thanks to Tom Reisinger]
* Free shipping or any other shipping without method_id will be automatically removed from the combined order. [14/01/2022][Thanks to Russ]
* Compatibility added for another WordPress Plugin Woocommerce Store Credit. [13/01/2022][Thanks to Russ]
= 1.4.3 =
* WP Doing Ajax added in return condition. [20/12/2021]
= 1.4.2 =
* Peformance optimization revised. [18/12/2021]
= 1.4.1 =
* Links updated. [18/12/2021]
= 1.4.0 =
* Peformance optimization. [18/12/2021]
= 1.3.9 =
* WooCommerce reports are tested after combine action. [17/12/2021][Thanks to hortense85]
= 1.3.8 =
* Auto combine function improved. [08/12/2021][Thanks to Joseph Djemal]
= 1.3.7 =
* Auto combine function improved with a few new options including Gravity Forms compatibility. [30/11/2021][Thanks to Joseph Djemal]
* Highest shipping cost option added in shipping dropdown. [04/12/2021][Thanks to a0394 / baldivive.it]
* Auto combine function tested and ensured the working as expected. [29/11/2021][Thanks to Kim Chee]
= 1.3.6 =
* Combine settings revised. [11/05/2021][Thanks to Klynt Maston]
= 1.3.5 =
* New feature added, Combine Settings: Keep Order Items Separate using Attributes and Values (Off/On). [04/05/2021][Thanks to Abu Usman & po64]
= 1.3.4 =
* Item meta data and order meta data cloning functions refined. [29/04/2021][Thanks to Carly Rawiri / Hatched Designs / Popping Candy]
= 1.3.3 =
* Improved version with move to trash option for bulk actions combine selected orders. [14/04/2021][Thanks to Jeffrey - CardTree]
= 1.3.2 =
* Meta data clone to combinded order, functionality revisited. [06/04/2021][Thanks to Carly Rawiri]
* Send email notifications to admin, new section added. [06/04/2021][Thanks to Klynt Maston]
= 1.3.1 =
* Improved version with a few more tweaks. [03/03/2021][Thanks to Klynt Maston]
= 1.3.0 =
* Improved version with a few tweaks. [24/01/2021]
= 1.2.9 =
* Improved version with a few tweaks. [12/12/2020]
= 1.2.8 =
* Undefined variable: wuoc_all_plugins fixed. [02/12/2020]
= 1.2.8 =
* Multi-site environment compatibility added. [19/11/2020][Thanks to Gonzalo Chirinos]
= 1.2.7 =
* Merged order items history option added as a metabox (optional). [02/10/2020][Thanks to Team Ibulb Work & Carly Rawiri from Hatched Designs]
= 1.2.6 =
* _wuoc_merged_orders added as a meta_key to trace merged order ids. [24/10/2020][Thanks to Olga & Team Ibulb Work]
= 1.2.5 =
* German, Spanish and French languages are added. [20/10/2020][Thanks to Rainer Wittmann & Abu Usman]
= 1.2.4 =
* Warning : trim() expects parameter 1 to be string, array given. Fixed. [10/10/2020]
= 1.2.3 =
* Merged orders meta data retention in form of an array. [09/10/2020][Thanks to Rainer Wittmann & Team Ibulb Work]
= 1.2.2 =
* Multi-site compatibility added. [11/09/2020]
= 1.2.1 =
* Another round of user-friendliness performed. [08/09/2020]
= 1.2.0 =
* Remove original orders option added. [05/09/2020][Thanks to Team Ibulb Work & Xavier Deysine]
= 1.1.9 =
* is_type() replaced with get_type(). [25/07/2020][Thanks to Dr. Rod Aziz]
= 1.1.8 =
* Orders with variations tested again improved. [09/04/2020][Thanks to Dr. Rod Aziz]
= 1.1.7 =
* Merge order bulk options revised with screenshot video tutorial. [09/04/2020][Thanks to Rais Sufyan]
= 1.1.6 =
* Merge order bulk options revised. [09/04/2020][Thanks to Chris McCreery]
= 1.1.5 =
* Merged order total was not updated as expected. Fixed it. [06/04/2020][Thanks to Chris McCreery / chrismccreery]
= 1.1.4 =
* Consider parent column and meta_keys based columns functionality added. [01/01/2020]
= 1.1.3 =
* User First Name related search issue after combine action, resolved. [01/01/2020]
= 1.1.2 =
* "Combine by" options enhanced. [31/12/2019]
= 1.1.1 =
* "Combine by" options refined. [28/12/2019]
= 1.1 =
* "Combine by" options introduced. [25/12/2019]
= 1.0 =
* First release. [Developed by WordPress Mechanic in conjunction /assitance with RNR Digital Media Grp ;)]

== Upgrade Notice ==
= 1.8.7 =
Fix: Action hooks added and listed under the documentation tab.
= 1.8.6 =
Fix: Analyze process improved with the posted value of order status selected saved and loaded as default.
= 1.8.5 =
Fix: Analyze query improved with the order status input as an array instead of string.
= 1.8.4 =
New: What to do with existing orders? Change Order Status option added for the manual combine section.
= 1.8.3 =
 New: What to do with existing orders? Change Order Status option added for the manual combine section.
= 1.8.2 =
New: Email notification filter hook introduced and implemented.
= 1.8.1 =
Fix: Cron Settings: Edit Order Page / Admin Panel.
= 1.8.0 =
Fix: Cron Settings: Orders List Page / Admin Panel.
= 1.7.9 =
Cron job auto refined.
= 1.7.8 =
Cron job controls refined.
= 1.7.6 =
Cron job set to unlimited orders picked for the combination instead of one at a time.
= 1.7.5 =
Order object wrapped with the is_object function check.
= 1.7.4 =
Cron controls are improved.
= 1.7.3 =
Cron controls are refined.
= 1.7.2 =
New: Cron controls are added.
= 1.7.1 =
Fix: Empty values won't be considered for the comparison in meta keys and values cases.
= 1.7.0 =
Fix: Undefined variable original_order in functions-plus.php line 812, fixed.
= 1.6.9 =
Fix: Fatal error: Uncaught Error: Call to a member function get_items() on bool while getting order object.
= 1.6.8 =
New: Clock based cron job and sort order items by product categories.
= 1.6.7 =
New: Clock based cron job. Meta key based combining rule added and meta key/value based filter added.
= 1.6.6 =
Fix: Order status for combined orders improved.
= 1.6.5 =
Fix: Order status for combined orders revised.
= 1.6.4 =
Fix: meta_key _paid_date replaced with the date_query.
= 1.6.3 =
Fix: PHP Fatal error:  Uncaught TypeError: Illegal offset type for trim().
= 1.6.2 =
Fix: Restoration of merged orders improved.
= 1.6.1 =
New: Order Status (for Combined Orders) - Target feature added under automation tab.
= 1.6.0 =
Fix: Merged orders appearance under combined orders tab revisited.
= 1.5.9 =
Fix: Merged orders all existing meta keys maintianed in a shape of array with prefix of a double underscore "__" ensured.
= 1.5.8 =
New: Auto combination by payment method and multiple automation layers introduced.
= 1.5.7 =
Fix: PHP implode() related array variable check.
= 1.5.6 =
Fix: PHP Warning: Undefined variable $order_id.
= 1.5.5 =
Merged orders with zero priced item as a conflict among identical order items.
= 1.5.4 =
Fix: Billing and Shipping address will be considered without including email addresses and phone number.
= 1.5.3 =
New: Clone shipping option added.
= 1.5.2 =
Fix: After merge, empty white screen related issue.
= 1.5.1 =
Exception: Invalid order.
= 1.5.0 =
Stock reduction related issue resolved on edit-order items action trigger.
= 1.4.9 =
Pro version combined orders tab refinement.
= 1.4.8 =
Auto Combine functionality revised and debug system improved.
= 1.4.7 =
Combine by same customer user with analyze functionality improved.
= 1.4.6 =
Issue: Refunded items were being included in combining process - Fixed.
= 1.4.5 =
Issue: order combine not working at all - Fixed.
= 1.4.4 =
Newly added essentials file related tweaks.
= 1.4.3 =
WP Doing Ajax added in return condition.
= 1.4.2 =
Peformance optimization revised.
= 1.4.1 =
Links updated.
= 1.4.0 =
Peformance optimization.
= 1.3.9 =
WooCommerce reports are tested after combine action.
= 1.3.8 =
Auto combine function improved.
= 1.3.7 =
Auto combine function improved with a few new options including Gravity Forms compatibility.
= 1.3.6 =
Combine settings revised.
= 1.3.5 =
New feature added, Combine Settings: Keep Order Items Separate using Attributes and Values (Off/On).
= 1.3.4 =
Item meta data and order meta data cloning functions refined.
= 1.3.3 =
Improved version with move to trash option for bulk actions combine selected orders.
= 1.3.2 =
Meta data clone to combinded order, functionality revisited.
= 1.3.1 =
Improved version with a few more tweaks.
= 1.3.0 =
Improved version with a few tweaks.
= 1.2.9 =
Improved version with a few tweaks.
= 1.2.8 =
Undefined variable: wuoc_all_plugins fixed.
= 1.2.8 =
Multi-site environment compatibility added.
= 1.2.7 =
Merged order items history option added as a metabox (optional).
= 1.2.6 =
_wuoc_merged_orders added as a meta_key to trace merged order ids.
= 1.2.5 =
German, Spanish and French languages are added.
= 1.2.4 =
Warning : trim() expects parameter 1 to be string, array given. Fixed.
= 1.2.3 =
Merged orders meta data retention in form of an array.
= 1.2.2 =
Multi-site compatibility added.
= 1.2.1 =
Another round of user-friendliness performed.
= 1.2.0 =
Remove original orders option added.
= 1.1.9 =
is_type() replaced with get_type().
= 1.1.8 =
Orders with variations tested again improved.
= 1.1.7 =
Merge order bulk options revised with screenshot video tutorial.
= 1.1.6 =
Merge order bulk options revised.
= 1.1.5 =
Merged order total was not updated as expected. Fixed it.
= 1.1.4 =
Consider parent column and meta_keys based columns functionality added.
= 1.1.3 =
User First Name related search issue after combine action, resolved.
= 1.1.2 =
"Combine by" options enhanced.
= 1.1.1 =
"Combine by" options refined.
= 1.1 =
"Combine by" options introduced.
= 1.0 =
* First release.


== License ==
This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This WordPress plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.