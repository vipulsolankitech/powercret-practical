# Powercret Practical Skills Assessment

A WordPress project developed as part of the **Powercret WordPress Developer Practical Skills Assessment**.

---

## Requirements

| Requirement    | Version               |
| -------------- | --------------------- |
| PHP (Tested)   | 8.2                   |
| PHP Compatible | 7.4+                  |
| WordPress      | 7.0+                  |
| WooCommerce    | Latest (Free Version) |

---

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/vipulsolankitech/powercret-practical.git
```

### 2. Place the Project in Your Local Server

Example:

```text
xampp/htdocs/powercret-practical/
```

### 3. Create a Database

Create a MySQL database and import the provided SQL file (if included).

### 4. Configure WordPress

Copy `wp-config-sample.php` to `wp-config.php` and update your database credentials:

```php
define( 'DB_NAME', 'your_database_name' );
define( 'DB_USER', 'your_database_user' );
define( 'DB_PASSWORD', 'your_database_password' );
define( 'DB_HOST', 'localhost' );
```

> **Note:** `wp-config.php` is excluded from the repository for security reasons and must be created manually.

---

## Dependencies

* WordPress 7.0 or higher
* WooCommerce (Free Version) — required for Task 2 (Gift Wrap Add-on)
* PHP 7.4 or higher
* MySQL 5.7 or higher

---

## Tools Used During the Practical Assessment

* Sublime Text
* ChatGPT
* Claude Code
* InfinityFree Hosting

---

## Theme Information

| Theme                           | Version |
| ------------------------------- | ------- |
| Understrap                      | 1.2.4   |
| Understrap Child Theme (Active) | 1.2.0   |

---

## Project Links

### Live Website

* Frontend: https://prctical-powercret.infinityfree.io

### GitHub Repository

* https://github.com/vipulsolankitech/powercret-practical

> Some useful links have been added to the site header for quick access to the relevant pages.

---

# Task Details

---

## Task 1: Custom Post Type – Testimonials

### Overview

A custom plugin named **Testimonial** was created to manage testimonials.

### Implementation Details

* Created a custom plugin with a proper plugin header.
* Added an `ABSPATH` check to prevent direct access.
* Registered the `testimonial` custom post type using `register_post_type()` and the `init` action.
* Created a separate `custom_fields.php` file and included it in the main plugin file.
* Added three custom fields using `add_meta_box()` and the `add_meta_boxes` action.
* Saved custom field values using the `save_post` action.
* Added custom columns to the testimonial listing page.
* Enabled sorting using:

  * `manage_edit-testimonial_sortable_columns`
  * `pre_get_posts`
* Created a shortcode using `add_shortcode()`.
* Retrieved testimonial data using `WP_Query`.
* Created the page:

```text
/testimonial-front
```

and used the shortcode to display testimonials.

---

## Task 2: WooCommerce Gift Wrap Add-on

### Overview

A custom plugin named **Gift Wrap for WooCommerce** was developed to provide an optional gift wrapping feature.

### Implementation Details

* Installed and configured WooCommerce with Cash on Delivery and demo products.
* Created a custom plugin with a proper plugin header.
* Added an `ABSPATH` check to prevent direct access.
* Displayed a Gift Wrap checkbox on the cart page using `woocommerce_after_cart_table`.
* Added the gift wrap fee using `add_fee()`.
* Ensured that the fee is carried through checkout and stored in the order.
* Enqueued scripts using `wp_enqueue_scripts`.
* Passed AJAX variables including:

  * Nonce
  * `admin-ajax.php` URL
* Handled AJAX requests using `wp_ajax_*`.
* Managed the Gift Wrap option using WooCommerce sessions.
* Recalculated cart totals dynamically.
* Reset the Gift Wrap option when the cart becomes empty using `woocommerce_cart_emptied`.

### Result

The Gift Wrap charge is displayed on:

* Cart page
* Checkout page
* Order summary
* WooCommerce admin order screen

---

## Task 3: Security Improvements

### Overview

The provided vulnerable code was reviewed and secured.

### Security Enhancements

* Added `isset()` checks before accessing `user_id`.
* Sanitized user input.
* Used prepared statements to prevent SQL injection.
* Escaped output to prevent unwanted script execution.
* Considered adding capability checks, but the original purpose of the function was unclear.
* Debugged the code during page load using the `init` action.
* Stored the improved code in:

```text
wp-content/plugins/Testimonial/code_debug.php
```

---

## Task 4: AI Integration – Post Summarizer

### Overview

A plugin named **Blog Summary** was created to generate summaries for WordPress posts.

### Implementation Details

* Added an `ABSPATH` check to prevent direct access.
* Enqueued scripts using `wp_enqueue_scripts`.
* Localized AJAX variables including:

  * Nonce
  * `admin-ajax.php` URL
* Created the `blog_button_after_content()` function and attached it to the `the_content` filter.
* Added the **Summarize Blog** button below post content.
* Created `get_summary_result.php` to handle AJAX requests.
* Triggered AJAX requests when the button is clicked.
* Created the `get_blog_summary()` function and registered it using `wp_ajax_*`.
* Implemented a dummy response containing the dynamic post title.
* Added a sample API implementation using `wp_remote_post()` (commented out because no API key was available).
* Displayed a simple loading indicator while the request is being processed.
* Stored the API key in a configuration file instead of exposing it directly inside plugin files.

---

## Developed By

**Vipul Solanki**
