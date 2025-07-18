<?php
/**
 * Plugin Name:       Markdown to HTML API
 * Description:       Provides a REST API endpoint to convert Markdown to HTML.
 * Tested up to:      6.8.2
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Version:           0.9.1
 * Author:            Stingray82
 * Author URI:        https://reallyusefulplugins.com
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       markdown-html-api
 * Website:           https://reallyusefulplugins.com
 */

add_action('rest_api_init', function () {
    register_rest_route('md/v1', '/convert', [
        'methods'  => 'POST',
        'callback' => 'md_api_convert_markdown',
        'args'     => [
            'markdown' => [
                'required' => true,
                'type'     => 'string',
            ],
        ],
        'permission_callback' => '__return_true',
    ]);
});

function md_api_convert_markdown($request) {
    $markdown = trim($request->get_param('markdown'));

    if (!class_exists('ParsedownExtra')) {
        require_once plugin_dir_path(__FILE__) . 'Parsedown.php';
        require_once plugin_dir_path(__FILE__) . 'ParsedownExtra.php';
    }

    $Parsedown = new ParsedownExtra();
    $Parsedown->setSafeMode(true); // Prevent raw HTML injection

    $html = $Parsedown->text($markdown);

    // Convert task list checkboxes ([x], [ ])
    $html = preg_replace_callback('/<li>\s*\[( |x)\]\s*(.*?)<\/li>/i', function ($matches) {
        $checked = strtolower($matches[1]) === 'x' ? 'checked' : '';
        return '<li><input type="checkbox" ' . $checked . ' disabled> ' . $matches[2] . '</li>';
    }, $html);

    // Escape code blocks: ensures &lt;?php ... ?&gt; displays
    $html = preg_replace_callback(
        '/<pre>\s*<code[^>]*class="language-(.*?)"[^>]*>(.*?)<\/code>\s*<\/pre>/is',
        function ($matches) {
            $lang = $matches[1];
            $code = trim(htmlspecialchars_decode($matches[2]));
            $escaped = htmlspecialchars($code, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            return '<pre><code class="language-' . $lang . '">' . $escaped . '</code></pre>';
        },
        $html
    );

    return rest_ensure_response([
        'html' => $html,
    ]);
}

// Define our plugin version
if ( ! defined( 'MARKDOWN_HTML_API_VERSION' ) ) {
    define('MARKDOWN_HTML_API_VERSION', '0.9.1');
}

// ──────────────────────────────────────────────────────────────────────────
//  Updater bootstrap (plugins_loaded priority 1):
// ──────────────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', function() {
    // 1) Load our universal drop-in. Because that file begins with "namespace UUPD\V1;",
    //    both the class and the helper live under UUPD\V1.
    require_once __DIR__ . '/inc/updater.php';

    // 2) Build a single $updater_config array:
    $updater_config = [
        'plugin_file' => plugin_basename( __FILE__ ),             // e.g. "simply-static-export-notify/simply-static-export-notify.php"
        'slug'        => 'markdown-html-api',           // must match your updater‐server slug
        'name'        => 'Markdown to Html API',         // human‐readable plugin name
        'version'     => MARKDOWN_HTML_API_VERSION, // same as the VERSION constant above
        'key'         => 'testkey123',                 // your secret key for private updater
        'server'      => 'https://raw.githubusercontent.com/stingray82/markdown-html-api/main/uupd/index.json',
        //'server'      => 'https://updater.reallyusefulplugins.com/u/',
        // 'textdomain' is omitted, so the helper will automatically use 'slug'
        
    ];

    // 3) Call the helper in the UUPD\V1 namespace:
    \RUP\Updater\Updater_V1::register( $updater_config );
}, 1 );


?>
