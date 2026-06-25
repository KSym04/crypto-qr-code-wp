=== Crypto QR Code WP ===
Contributors: ksym04
Tags: bitcoin, cryptocurrency, qr code, crypto donations, donations
Requires at least: 4.7
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Accept Bitcoin and cryptocurrency donations with a click to reveal QR code, generated in the browser with a wallet settings page.

== Description ==

**Crypto QR Code WP** lets you accept cryptocurrency donations and tips by displaying any wallet address on your site as a clean label and link that reveals a scannable QR code. It works with Bitcoin, Ethereum, Litecoin, Dogecoin, and any other coin: just paste in your public wallet address and the plugin handles the rest.

Save your wallets once on the settings page and reuse them anywhere with a shortcode, a widget, or the block editor. Each cryptocurrency QR code is generated entirely in your visitor's browser, so nothing is written to your server and there are no external or third party requests.

= Perfect for =

* Accepting Bitcoin and crypto donations on a blog, portfolio, or nonprofit site.
* Adding a tip jar or donate button with a scannable wallet QR code.
* Showing several cryptocurrency wallet addresses together on one page.

= Key features =

* A settings page where you manage a library of wallets, each with a ready made shortcode you can copy.
* A click to reveal QR code tooltip that keeps your page tidy.
* A widget and shortcode, so you can place a wallet in posts, pages, sidebars, or footers.
* QR codes generated in the browser. No server side files, no uploads folder, and no third party services.
* Adjustable QR size and full output escaping for safe, clean markup.

= How it works =

1. Open Crypto QR Code in your admin menu and add a wallet (label, address, and an optional tooltip heading).
2. Copy the generated shortcode, for example: `[cqcw_generator heading="Donate" label="BTC" address="bc1qexampleaddress"]`
3. Paste it into any post, page, or widget. Visitors click the address to reveal the QR code.

= Disclaimer =

Only ever use your public wallet address. Never share your private key. Any loss resulting from misuse of your keys is your own responsibility.

== Installation ==

1. In your WordPress admin, go to Plugins, then Add New.
2. Search for "Crypto QR Code WP".
3. Click Install Now, then Activate.
4. Open the Crypto QR Code menu, add a wallet, and copy its shortcode into your content.

Manual installation:

1. Download the plugin zip from WordPress.org.
2. Upload the `crypto-qr-code-wp` folder to `/wp-content/plugins/`.
3. Activate the plugin through the Plugins menu in WordPress.

== Frequently Asked Questions ==

= How do I add a wallet? =

Open the Crypto QR Code menu in your WordPress admin, click Add Wallet, enter a label (such as BTC) and your public wallet address, then save. Each wallet shows a shortcode you can copy.

= How do I use the shortcode? =

The shortcode `cqcw_generator` accepts three parameters: heading, label, and address. Example: `[cqcw_generator heading="Donate" label="BTC" address="bc1qexampleaddress"]`

= Is my wallet address safe to display? =

Yes. A public wallet address is meant to be shared so people can send you funds. Never enter your private key anywhere.

= Where is the QR code image stored? =

Nowhere. The QR code is drawn in the visitor's browser each time the page loads, so the plugin does not create files or folders on your server.

= Does it work with the block editor and widgets? =

Yes. You can use the shortcode in a block, or add the Crypto QR Code WP widget to any widget area.

== Screenshots ==

1. A wallet address with the click to reveal QR code tooltip.
2. The settings page where you manage your wallet library and copy shortcodes.

== Changelog ==

= 1.1.0 =
* New - QR codes are now generated entirely in the browser, so nothing is written to your server.
* New - Settings page to manage your wallet addresses and copy ready made shortcodes.
* Security - Full output escaping across the shortcode and widget, resolving a stored cross site scripting risk.
* Security - Removed the bundled phpqrcode library and the server side file generation it required.
* Tweak - Assets now load only on pages that actually use the QR code.
* Tweak - Confirmed compatibility with WordPress 7.0 and PHP 8.
* Fix - Corrected the widget display name and sanitized all saved widget fields.

= 1.0.2 =
* Fix - Widget issue
* Tweak - Improved CSS theme compatibility

= 1.0.1 =
* New - Added 'Crypto QR Code' widget
* Tweak - Enhanced file security

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.1.0 =
Security and feature update: QR codes now render in the browser (no server files), full output escaping, and a new wallet settings page. Recommended for all users.
