<?php

function wp_quotes_settings_page() {
    $db_name = get_option('wp_quotes_db_name', '');
    ?>
    <h1>WP Quotes Settings</h1>
    <p>Welcome to the WP Quotes plugin settings page.</p>
    <p>Upload the database with the quotes:</p>

    <form method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field('wp_quotes_file_upload', 'wp_quotes_nonce'); ?>
        <label for="db_file">Upload Database File:</label>
        <input type="file" id="db_file" name="db_file" /><br>
        <input type="submit" name="upload_file" value="Upload File" />
    </form>
    <br>
    <form method="post" action="">
        <label for="db_name">Set Database Name:</label>
        <input type="text" id="db_name" name="name_input" value="<?php echo esc_attr($db_name); ?>" /><br>
        <input type="submit" name="update_name" value="Enter Database Name" />
    </form>
    <br>
    <form method="post" action="">
        <label for="db_name">Delete Database:</label>
        <input type="text" id="db_name" name="name_input" value="/2025/01/quotes.db" /><br>
        <input type="submit" name="delete_db" value="Delete" />
    </form>

    <?php

    if (isset($_POST['upload_file']) && check_admin_referer('wp_quotes_file_upload', 'wp_quotes_nonce')) {
        if (!empty($_FILES['db_file']['name'])) {
            $uploaded_file = $_FILES['db_file'];
            $upload_overrides = array('test_form' => false);

            $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $url = $movefile['url'];

                $path = parse_url($url, PHP_URL_PATH);
                if (preg_match('#\d{4}/\d{2}/[^/]+$#', $path, $matches)) {
                    $relative_path = $matches[0];
                } else {
                    echo "No matching year/path found.";
                    $relative_path = '';
                }

                update_option('wp_quotes_db_name', $relative_path);
                echo 'File uploaded successfully: ', $relative_path;
            } else {
                echo 'Error uploading file: ' . esc_html($movefile['error']);
            }
        } else {
            echo 'No file selected for upload.';
        }
    }
    if (isset($_POST['update_name'])) {
        $new_name = sanitize_text_field($_POST['name_input']);
        update_option('wp_quotes_db_name', $new_name);

        echo '<meta http-equiv="refresh" content="0">';
    }
    if (isset($_POST['delete_db'])) {
        $db_name = sanitize_text_field($_POST['name_input']);
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . $db_name;
        if (file_exists($file_path)) {
            unlink($file_path);
            echo 'File deleted successfully.';
        } else {
            echo 'File not found.';
        }
    }
}
