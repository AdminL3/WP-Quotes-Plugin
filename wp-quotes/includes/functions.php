<?php

function display_daily_quote_sqlite() {
    $stored_name = get_option('wp_quotes_db_name', '');
    $db_path = "wp-content/uploads/$stored_name";
    $db = new SQLite3($db_path);

    if (!$db) {
        return 'Datenbank konnte nicht geöffnet werden.';
    }

    $day_of_year = date('z') + 1;  // Returns the day of the year (1-365)
    $count_stmt = $db->prepare('SELECT COUNT(*) as total_quotes FROM quotes');
    $count_result = $count_stmt->execute();
    $total_quotes = $count_result->fetchArray(SQLITE3_ASSOC)['total_quotes'];

    $quote_id = $day_of_year % $total_quotes;
    if ($quote_id == 0) {
        $quote_id = $total_quotes;
    }

    // Prepare query to fetch both content and author
    $stmt = $db->prepare('SELECT content, author FROM quotes WHERE id = :id');
    if (!$stmt) {
        return 'SQL-Abfrage konnte nicht vorbereitet werden.';
    }

    $stmt->bindValue(':id', $quote_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if (!$result) {
        return 'SQL-Abfrage konnte nicht ausgeführt werden.';
    }

    $quote = $result->fetchArray(SQLITE3_ASSOC);
    if (!$quote) {
        return 'Kein Zitat für heute gefunden.';
    }

    // Display the quote and the author
    return "<div class='daily-quote'>
                <p>{$quote['content']}  <strong>~ {$quote['author']}</strong></p>
            </div>";
}

add_shortcode('daily_quote', 'display_daily_quote_sqlite');

