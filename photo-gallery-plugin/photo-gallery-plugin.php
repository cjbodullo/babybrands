<?php
/**
 * Plugin Name: Photo Gallery Plugin
 * Description: Winner photo gallery with admin approval workflow.
 * Version: 1.1.0
 * Author: BabyBrands
 * Text Domain: photo-gallery-plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PGP_VERSION', '1.1.0');
define('PGP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PGP_PLUGIN_PATH', plugin_dir_path(__FILE__));

function pgp_get_winner_table_name()
{
    global $wpdb;

    $tableCandidates = [
        $wpdb->prefix . 'winner_photo_release_submissions',
        'wp_winner_photo_release_submissions',
        'winner_photo_release_submissions',
    ];

    foreach ($tableCandidates as $candidate) {
        $exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $candidate));
        if ($exists === $candidate) {
            return $candidate;
        }
    }

    return '';
}

function pgp_ensure_winner_table_schema()
{
    global $wpdb;

    $tableName = pgp_get_winner_table_name();
    if ($tableName === '') {
        return;
    }

    $statusColumn = $wpdb->get_var("SHOW COLUMNS FROM `{$tableName}` LIKE 'status'");
    if ($statusColumn === null) {
        $wpdb->query(
            "ALTER TABLE `{$tableName}`
             ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending' AFTER winner_photo_path"
        );
    }

    $statusIndex = $wpdb->get_var("SHOW INDEX FROM `{$tableName}` WHERE Key_name = 'idx_status'");
    if ($statusIndex === null) {
        $wpdb->query("ALTER TABLE `{$tableName}` ADD INDEX idx_status (status)");
    }
}
add_action('init', 'pgp_ensure_winner_table_schema');

function pgp_enqueue_assets()
{
    wp_enqueue_style(
        'pgp-style',
        PGP_PLUGIN_URL . 'assets/css/photo-gallery-plugin.css',
        [],
        PGP_VERSION
    );

    wp_enqueue_style(
        'glightbox-css',
        'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css'
    );

    wp_enqueue_script(
        'glightbox-js',
        'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js',
        [],
        null,
        true
    );

    wp_add_inline_script('glightbox-js', "
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                zoomable: true,
            });
        });
    ");

}
add_action('wp_enqueue_scripts', 'pgp_enqueue_assets');

/**
 * Shortcode: [photo_gallery_plugin ids="1,2,3"] (legacy/manual mode)
 */
function pgp_render_gallery_shortcode($atts)
{
    $atts = shortcode_atts(
        [
            'ids' => '',
            'columns' => 3,
        ],
        $atts,
        'photo_gallery_plugin'
    );

    $ids = array_filter(array_map('absint', explode(',', (string) $atts['ids'])));
    $columns = max(1, min(6, absint($atts['columns'])));

    if (empty($ids)) {
        return '<p>No images selected.</p>';
    }

    $output = '<div class="pgp-gallery pgp-cols-' . esc_attr((string) $columns) . '">';

    foreach ($ids as $id) {
        $full = wp_get_attachment_image_url($id, 'large');
        $thumb = wp_get_attachment_image_url($id, 'medium');

        if (!$full || !$thumb) {
            continue;
        }

        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $output .= '<a class="pgp-item" href="' . esc_url($full) . '" target="_blank" rel="noopener">';
        $output .= '<img src="' . esc_url($thumb) . '" alt="' . esc_attr($alt) . '">';
        $output .= '</a>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('photo_gallery_plugin', 'pgp_render_gallery_shortcode');

function pgp_build_photo_url($rawPath)
{
    $rawPath = trim((string) $rawPath);
    if ($rawPath === '') {
        return '';
    }

    $uploadInfo = wp_upload_dir();
    $baseUrl = trailingslashit($uploadInfo['baseurl']);

    if (preg_match('#^https?://#i', $rawPath)) {
        return $rawPath;
    }
    if (strpos($rawPath, 'wp-content/uploads/') === 0) {
        // Supports stored path format: wp-content/uploads/YYYY/MM/filename.jpg
        return site_url('/' . ltrim($rawPath, '/'));
    }
    if (strpos($rawPath, 'uploads/') === 0) {
        // Supports stored path format: uploads/YYYY/MM/filename.jpg
        // Primary assumption: path is under wp-content/uploads
        return site_url('/wp-content/' . ltrim($rawPath, '/'));
    }
    if (strpos($rawPath, '/') === 0) {
        return $baseUrl . ltrim($rawPath, '/');
    }

    // Saved as "YYYY/MM/filename.jpg"
    if (preg_match('#^\d{4}/\d{2}/#', $rawPath)) {
        return $baseUrl . '/' . ltrim($rawPath, '/');
    }

    return $baseUrl . '/' . ltrim($rawPath, '/');
}

/**
 * Shortcode: [winner_photo_gallery limit="12" columns="4"]
 * Renders only approved winner submissions.
 */
function pgp_render_winner_photo_gallery($atts)
{
    global $wpdb;
    pgp_ensure_winner_table_schema();

    $atts = shortcode_atts(
        [
            'limit' => 12,
            'columns' => 4,
            'order' => 'DESC',
        ],
        $atts,
        'winner_photo_gallery'
    );

    $limit = max(1, min(60, absint($atts['limit'])));
    $columns = max(1, min(6, absint($atts['columns'])));
    $order = strtoupper((string) $atts['order']) === 'ASC' ? 'ASC' : 'DESC';

    $tableName = pgp_get_winner_table_name();
    if ($tableName === '') {
        return '<p>Winner submissions table not found.</p>';
    }

    $query = "SELECT id, first_name, city, province, winner_photo_path, created_at
              FROM `{$tableName}`
              WHERE winner_photo_path IS NOT NULL
                AND winner_photo_path <> ''
                AND status = 'approved'
              ORDER BY created_at {$order}
              LIMIT %d";

    $rows = $wpdb->get_results($wpdb->prepare($query, $limit));
    if (empty($rows)) {
    return '<div class="pgp-border-warning">
                <span class="pgp-warning-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 9v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.29 3.86l-8.3 14.29A2 2 0 0 0 3.7 21h16.6a2 2 0 0 0 1.71-2.85l-8.3-14.29a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </span>
                <span>Winner photos are currently under review.</span>
            </div>';
	}

    $output = '<div class="pgp-winners pgp-winners-cols-' . esc_attr((string) $columns) . '">';

    foreach ($rows as $row) {
        $firstName = isset($row->first_name) ? trim((string) $row->first_name) : '';
        $city = isset($row->city) ? trim((string) $row->city) : '';
        $province = isset($row->province) ? trim((string) $row->province) : '';
        $rawPath = isset($row->winner_photo_path) ? trim((string) $row->winner_photo_path) : '';

        if ($rawPath === '') {
            continue;
        }

        $photoUrl = pgp_build_photo_url($rawPath);
        if ($photoUrl === '') {
            continue;
        }

        $location = trim($city . (strlen($province) ? ', ' . $province : ''));

        $altText = $firstName . ' Winner Photo';

        // GLIGHTBOX WRAPPER START
        $output .= '<article class="pgp-winner-card">';

        $output .= '<a href="' . esc_url($photoUrl) . '" 
                        class="glightbox pgp-winner-link"
                        data-gallery="pgp-winners"
                        data-type="image">';

        $output .= '<div class="pgp-winner-image-wrap">';
        $output .= '<img class="pgp-winner-image" src="' . esc_url($photoUrl) . '" alt="' . esc_attr($altText) . '">';
        $output .= '</div>';

        $output .= '</a>';

        $output .= '<div class="pgp-winner-meta">';
        $output .= '<h3 class="pgp-winner-name">' . esc_html($firstName) . '</h3>';
        $output .= '<p class="pgp-winner-location">' . esc_html($location) . '</p>';
        $output .= '</div>';

        $output .= '</article>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('winner_photo_gallery', 'pgp_render_winner_photo_gallery');

function pgp_register_admin_menu()
{
    add_menu_page(
        'Winners Gallery',
        'Winners Gallery',
        'manage_options',
        'pgp-winner-gallery',
        'pgp_render_admin_page',
        'dashicons-format-gallery',
        58
    );
}
add_action('admin_menu', 'pgp_register_admin_menu');

function pgp_handle_admin_actions()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    if (!isset($_GET['page']) || $_GET['page'] !== 'pgp-winner-gallery') {
        return;
    }

    if (!isset($_GET['pgp_action']) || !isset($_GET['submission_id']) || !isset($_GET['_wpnonce'])) {
        return;
    }

    $action = sanitize_key((string) $_GET['pgp_action']);
    $submissionId = absint($_GET['submission_id']);

    if (!wp_verify_nonce((string) $_GET['_wpnonce'], 'pgp_update_status_' . $submissionId)) {
        wp_die('Security check failed.');
    }

    if (!in_array($action, ['approve', 'reject', 'pending','delete'], true)) {
        wp_die('Invalid action.');
    }

    global $wpdb;
    pgp_ensure_winner_table_schema();
    $tableName = pgp_get_winner_table_name();
    if ($tableName === '') {
        wp_die('Winner submissions table not found.');
    }

    if ($action === 'delete'){
         $wpdb->delete(
            $tableName,
            ['id' => $submissionId],
            ['%d']
        );
        wp_safe_redirect(admin_url('admin.php?page=pgp-winner-gallery&updated=1'));
        exit;
    }

    $status = $action === 'approve' ? 'approved' : ($action === 'reject' ? 'rejected' : 'pending');
    $wpdb->update(
        $tableName,
        ['status' => $status],
        ['id' => $submissionId],
        ['%s'],
        ['%d']
    );

    wp_safe_redirect(admin_url('admin.php?page=pgp-winner-gallery&updated=1'));
    exit;
}
add_action('admin_init', 'pgp_handle_admin_actions');


function pgp_update_photo()
{
    global $wpdb;

    $table = pgp_get_winner_table_name();

    if (empty($_FILES['photo']['name'])) {
        wp_send_json_error();
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');

    $uploaded = wp_handle_upload($_FILES['photo'], ['test_form' => false]);

    if (isset($uploaded['error'])) {
        wp_send_json_error();
    }

    $file_url = $uploaded['url'];

    $wpdb->update(
        $table,
        ['winner_photo_path' => $file_url],
        ['id' => intval($_POST['submission_id'])]
    );

    wp_send_json_success(['url' => $file_url]);
}
add_action('wp_ajax_pgp_update_photo', 'pgp_update_photo');

function pgp_render_admin_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    pgp_ensure_winner_table_schema();
    $tableName = pgp_get_winner_table_name();

    echo '<div class="wrap"><h1>Winners Gallery Approvals</h1>';
    echo '<p>Approve or reject winner photo.</p>';

    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Status updated.</p></div>';
    }

    if ($tableName === '') {
        echo '<div class="notice notice-error"><p>Winner submissions table not found.</p></div></div>';
        return;
    }

    // --- Pagination setup ---
    $per_page = 10; // submissions per page
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Get total rows
    $total_rows = $wpdb->get_var("SELECT COUNT(*) FROM `{$tableName}`");
    $total_pages = ceil($total_rows / $per_page);

    // Fetch current page rows
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM `{$tableName}` ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );

    if (empty($rows)) {
        echo '<p>No submissions found.</p></div>';
        return;
    }

    echo '<table class="widefat striped">';
    echo '<thead><tr>';
    echo '<th>ID</th><th>Photo</th><th>Name</th><th>Location</th><th>Status</th><th>Submitted</th><th>Actions</th>';
    echo '</tr></thead><tbody>';
    $hiddenDetailsHtml = '';

    $fieldNames = [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone Number',
        'address_1' => 'Address 1',
        'address_2' => 'Address 2',
        'city' => 'City',
        'province' => 'Province',
        'postal_code' => 'Postal Code',
        'winner_photo_path' => 'Photo File',
        'status' => 'Status',
        'agreed_terms' => 'Agree Terms',
        'created_at' => 'Created At',
    ];

    foreach ($rows as $key=>$row) {
        $photoUrl = pgp_build_photo_url($row->winner_photo_path ?? '');
        $name = trim((string) ($row->first_name ?? '') . ' ' . (string) ($row->last_name ?? ''));
        $location = trim((string) ($row->city ?? '') . ', ' . (string) ($row->province ?? ''), ' ,');
        $status = (string) ($row->status ?? 'pending');
        $id = (int) $row->id;

        $approveUrl = wp_nonce_url(admin_url('admin.php?page=pgp-winner-gallery&pgp_action=approve&submission_id=' . $id), 'pgp_update_status_' . $id);
        $rejectUrl  = wp_nonce_url(admin_url('admin.php?page=pgp-winner-gallery&pgp_action=reject&submission_id=' . $id), 'pgp_update_status_' . $id);
        $pendingUrl = wp_nonce_url(admin_url('admin.php?page=pgp-winner-gallery&pgp_action=pending&submission_id=' . $id), 'pgp_update_status_' . $id);
        $deleteUrl  = wp_nonce_url(admin_url('admin.php?page=pgp-winner-gallery&pgp_action=delete&submission_id=' . $id), 'pgp_update_status_' . $id);

        $detailRowsHtml = '';
        foreach ((array) $row as $field => $value) {
            if($field === 'agreed_terms') $value = $value==1?'Agree':'Disagree';
            if(isset($fieldNames[$field])){
                $detailRowsHtml .= '<tr>';
                $detailRowsHtml .= '<th style="width:220px;">' . esc_html((string) $fieldNames[$field]) . '</th>';
                $detailRowsHtml .= '<td>' . esc_html((string) $value) . '</td>';
                $detailRowsHtml .= '</tr>';
            }
        }

        echo '<tr>';
        echo '<td style="vertical-align: middle;">' . esc_html((string) $key+1) . '</td>';
        echo '<td style="vertical-align: middle;">';
        if ($photoUrl) {
            echo '<img src="' . esc_url($photoUrl) . '" alt="" style="width:70px;height:70px;object-fit:cover;border-radius:8px;">';
        } else {
            echo 'N/A';
        }
        echo '</td>';
        echo '<td style="vertical-align: middle;">' . esc_html($name) . '</td>';
        echo '<td style="vertical-align: middle;">' . esc_html($location) . '</td>';
        echo '<td style="vertical-align: middle;"><strong>' . esc_html(ucfirst($status)) . '</strong></td>';
        echo '<td style="vertical-align: middle;">' . esc_html((string) $row->created_at) . '</td>';
        echo '<td style="vertical-align: middle;">';
        echo '<button type="button" class="button pgp-view-details-btn" data-target="pgp-detail-' . esc_attr((string) $id) . '">View Details</button> ';
        echo '<button type="button" 
        class="button button-secondary pgp-edit-btn" 
        data-id="' . esc_attr($id) . '" 
        data-photo="' . esc_url($photoUrl) . '">
        Edit
      </button> ';
        echo '<a class="button button-primary" href="' . esc_url($approveUrl) . '">Approve</a> ';
        echo '<a class="button" href="' . esc_url($rejectUrl) . '">Reject</a> ';
        echo '<a class="button" href="' . esc_url($pendingUrl) . '">Set Pending</a> ';
        echo '<a class="button button-secondary" href="' . esc_url($deleteUrl) . '">Delete</a>';
        echo '</td>';
        echo '</tr>';

        $hiddenDetailsHtml .= '<div id="pgp-detail-' . esc_attr((string) $id) . '" style="display:none;">';
        $hiddenDetailsHtml .= '<h2 style="margin-top:0;">Submission Details #' . esc_html((string) $id) . '</h2>';
        if ($photoUrl) {
            $hiddenDetailsHtml .= '<p style="margin-bottom:14px;">';
            $hiddenDetailsHtml .= '<img src="' . esc_url($photoUrl) . '" alt="" style="max-width:240px;height:auto;border-radius:10px;display:block;">';
            $hiddenDetailsHtml .= '</p>';
        }
        $hiddenDetailsHtml .= '<table class="widefat striped" style="margin-top:0;"><tbody>' . $detailRowsHtml . '</tbody></table>';
        $hiddenDetailsHtml .= '</div>';
    }

    echo '</tbody></table>';

        // --- Default WP pagination ---
    if ($total_pages > 1) {
        $pagination = paginate_links([
            'base'      => add_query_arg('paged','%#%'),
            'format'    => '',
            'current'   => $current_page,
            'total'     => $total_pages,
            'prev_text' => __('&laquo; Prev'),
            'next_text' => __('Next &raquo;'),
            'type'      => 'list',
        ]);

        echo '<div class="tablenav"><div class="tablenav-pages">';
        echo $pagination;
        echo '</div></div>';
    }

    echo $hiddenDetailsHtml;
    echo '</div>';

    // --- Modal JS ---
    echo '<div id="pgp-admin-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.48);z-index:99999;padding:24px;overflow:auto;">';
    echo '<div style="max-width:900px;margin:32px auto;background:#fff;border-radius:12px;padding:20px;position:relative;">';
    echo '<button type="button" id="pgp-admin-modal-close" class="button" style="position:absolute;right:14px;top:14px;">Close</button>';
    echo '<div id="pgp-admin-modal-content"></div>';
    echo '</div></div>';
    echo '<div id="pgp-edit-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99999;">
            <div style="max-width:500px;margin:60px auto;background:#fff;padding:20px;border-radius:12px;position:relative;">
                <button type="button" id="pgp-edit-close" class="button" style="position:absolute;top:10px;right:10px;">Close</button>

                <h2>Edit Photo</h2>

                <div style="text-align:center;margin-bottom:15px;">
                    <img id="pgp-edit-preview" src="" style="max-width:200px;border-radius:8px;">
                </div>

                <input type="file" id="pgp-edit-file" accept="image/*">

                <div style="margin-top:15px;">
                    <a id="pgp-download-btn" class="button" href="#" download>Download</a>
                    <button id="pgp-save-btn" class="button button-primary">Save</button>
                </div>

                <input type="hidden" id="pgp-edit-id">
            </div>
        </div>';
    echo '<script>
    (function () {
        var modal = document.getElementById("pgp-admin-modal");
        var closeBtn = document.getElementById("pgp-admin-modal-close");
        var content = document.getElementById("pgp-admin-modal-content");
        if (!modal || !closeBtn || !content) return;

        function closeModal() {
            modal.style.display = "none";
            content.innerHTML = "";
        }

        document.addEventListener("click", function (event) {
            var trigger = event.target.closest(".pgp-view-details-btn");
            if (!trigger) return;
            var targetId = trigger.getAttribute("data-target");
            var target = targetId ? document.getElementById(targetId) : null;
            if (!target) return;
            content.innerHTML = target.innerHTML;
            modal.style.display = "block";
        });

        closeBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", function (event) {
            if (event.target === modal) closeModal();
        });
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") closeModal();
        });
    })();

    (function () {

    const editModal = document.getElementById("pgp-edit-modal");
    const preview = document.getElementById("pgp-edit-preview");
    const fileInput = document.getElementById("pgp-edit-file");
    const saveBtn = document.getElementById("pgp-save-btn");
    const closeBtn = document.getElementById("pgp-edit-close");
    const downloadBtn = document.getElementById("pgp-download-btn");
    const idInput = document.getElementById("pgp-edit-id");

    // OPEN MODAL
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".pgp-edit-btn");
        if (!btn) return;

        const id = btn.dataset.id;
        const photo = btn.dataset.photo;

        idInput.value = id;
        preview.src = photo;
        downloadBtn.href = photo;

        editModal.style.display = "block";
    });

    // PREVIEW NEW IMAGE
    fileInput.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;

        preview.src = URL.createObjectURL(file);
    });

    // SAVE VIA AJAX
    saveBtn.addEventListener("click", function () {
        const file = fileInput.files[0];
        const id = idInput.value;

        if (!file) {
            alert("Please select an image.");
            return;
        }

        const formData = new FormData();
        formData.append("action", "pgp_update_photo");
        formData.append("submission_id", id);
        formData.append("photo", file);

        fetch(ajaxurl, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert("Updated successfully!");
                location.reload();
            } else {
                alert("Error updating.");
            }
        });
    });

    // CLOSE
    closeBtn.onclick = () => editModal.style.display = "none";
    editModal.onclick = (e) => {
        if (e.target === editModal) editModal.style.display = "none";
    };

    })();
    </script>';
}