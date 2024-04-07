# [Safe Redirect] [![Listed in Awesome YOURLS!](https://img.shields.io/badge/Awesome-YOURLS-C5A3BE)](https://github.com/YOURLS/awesome-yourls/)

<!-- Once you have committed code, get your plugin listed in Awesome YOURLS ! See https://github.com/YOURLS/awesome-yourls -->

A security reminder is displayed before redirecting long links, and custom ads can be inserted on the reminder page.

Requires [YOURLS](https://yourls.org) `1.9.2` and above.

## Usage

You can set whether to jump to the security reminder page based on the set User-Agent keyword to prevent errors in certain API short links.

You can set up HTML code to be inserted into the security reminder page, which can be marketing ads or other content. Some marketing ads must be valid under a specific domain name, and you can set a jump page relay domain name.

You can set a waiting time for automatic redirection.

## Installation

1. In `/user/plugins`, create a new folder named `safe-redirect`.
2. Drop these files in that directory.
3. Go to the Plugins administration page (eg. `http://sho.rt/admin/plugins.php`) and activate the plugin.
4. Have fun!

## License

This package is licensed under the [MIT License](LICENSE).
