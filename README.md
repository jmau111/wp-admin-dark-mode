# WP Admin Dark Mode 😈

Forces dark [force] mode on admin. **Use at your own risks**.

## Why

Dark mode is better for both humans and machines (battery life).

## Be careful

The plugin forces dark mode for admin, not frontend. It's an experiment based on a pretty **inaccessible** line of CSS code using filter `invert`.

I don't recommend using this plugin in production at all, especially if you have registered users.

I made it for me because existing dark mode plugins add too much things I don't need.

## How to use

1. Install the plugin as any other WordPress plugin
2. Activate it through the interface `/wp-admin/plugins.php` or any other way you can
3. Go to your user profile and check the option "enable dark mode" then save profile options

## How it works

No js.

The user can enable/disable it on user profile. The dark mode is disabled by default.

To change it, go to your user profile `/wp-admin/profile.php`. At the end of the page you should see a new checkbox to activate dark mode for the back office.
