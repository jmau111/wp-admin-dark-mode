<?php

/**
 * Plugin Name: WP Admin Dark Mode
 * Author: Julien Maury
 * Version: 1.0
 * License: GPL v3
 */
if (!function_exists("add_action")) {
    die("What are you trying?");
}

define("WP_ADMIN_DARKM_SLUG", "_is_admin_darkm_enabled");

class WP_ADMIN_DARKM
{
    static function add_user_fields($user)
    { ?>
        <h3><?php _e("Enable dark mode in the dashboard", "blank"); ?></h3>

        <table class="form-table">
            <tr class="show-admin-bar user-admin-bar-front-wrap">
                <th scope="row"><?php _e("Dark mode for admin", "wp-admin-darkm"); ?></th>
                <td>
                    <label for="admin_darkm">
                        <input name="admin_darkm" type="checkbox" id="admin_darkm" value="1" <?php checked(WP_ADMIN_DARKM::getOpt($user), 1); ?>>
                        <?php _e("Enable Admin Dark Mode", "wp-admin-darkm"); ?>
                    </label>
                </td>
            </tr>
        </table>
    <?php }

    static function save_user_fields($user_id)
    {
        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $user_id)) {
            return;
        }

        if (!current_user_can('edit_user', $user_id)) {
            return;
        }

        update_user_meta($user_id, WP_ADMIN_DARKM_SLUG, WP_ADMIN_DARKM::sanitize_field($_POST["admin_darkm"] ?? 0));
    }

    static function sanitize_field($val): int
    {
        if (1 === (int) sanitize_text_field($val)) {
            return 1;
        }
        return 0;
    }

    static function getOpt($user): int
    {
        if (!$user instanceof WP_USER) {
            $user = wp_get_current_user();
        }
        $opt = $user->ID > 0 ? get_user_meta($user->ID, WP_ADMIN_DARKM_SLUG, true) : 0;
        $opt = !is_null($opt) ? $opt : 0;
        return (int) $opt;
    }

    static function isDarkmEnabled($user = null): bool
    {
        return 1 === self::getOpt($user);
    }

    static function writeEvilCSS()
    {
        if (!self::isDarkmEnabled()) {
            return;
        }
    ?>
        <style>
            html {
                -webkit-filter: invert(1) hue-rotate(180deg);
                filter: invert(1) hue-rotate(180deg);
            }

            /* re-invert for incompatible elements */
            video,
            i,
            svg,
            object,
            embed,
            iframe,
            canvas,
            img {
                -webkit-filter: invert(1) hue-rotate(180deg);
                filter: invert(1) hue-rotate(180deg);
            }
        </style>
<?php
    }
}

add_action('show_user_profile', ['WP_ADMIN_DARKM', 'add_user_fields']);
add_action('edit_user_profile', ['WP_ADMIN_DARKM', 'add_user_fields']);
add_action('personal_options_update', ['WP_ADMIN_DARKM', 'save_user_fields']);
add_action('edit_user_profile_update', ['WP_ADMIN_DARKM', 'save_user_fields']);
add_action("admin_head", ['WP_ADMIN_DARKM', 'writeEvilCSS'], 11100);
