# Changelog

## 1.1.2 – 10-October-2022

* Fix: Check for `wc_get_current_admin_url()` before using it
* Fix: Accept `null` in `plugin_action_links_{}` function
* Update project structure

## 1.1.1 – 13-May-2022

* Improve translating strings
* Add sensible defaults
* Set "Requires PHP" header (7.4)
* Add links to logs from settings page
* Update Rate Limit library
* Improved test coverage and documentation

## 1.1.0

* Move Settings screen to Payments tab
* Move PSR16RateLimiter to nikolaposa/rate-limit library
* Log error when cache fails to write (bypasses rate limiter when there is a cache error - NB)
* Use WC_Logger 

## 1.0.3

* Minor dev changes

## 1.0.2

* Fixed PHP warning when loading settings page first time due to empty values.

## 1.0.1 
* Fixed bug with IPv6 where : was a disallowed character in the cache key.
* Changed the HTML "step" from 60 to 1 – I had thought it would step by 60 but still allow % values.


