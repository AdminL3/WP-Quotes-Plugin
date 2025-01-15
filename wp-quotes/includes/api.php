<?php

add_action('rest_api_init', function () {
    register_rest_route('quote', '/daily', array(
        'methods' => 'GET',
        'callback' => 'get_daily',
        'permission_callback' => function () {
            return true;
        }
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('quote', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_all',
        'permission_callback' => function () {
            return true;
        }
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('quote', '/random', array(
        'methods' => 'GET',
        'callback' => 'get_random',
        'permission_callback' => function () {
            return true;
        }
    ));
});

function get_daily() {
	$db_path = plugin_dir_path(__FILE__) . 'quotes.db';
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
	return $quote;
}


function get_all() {
	$db_path = plugin_dir_path(__FILE__) . 'quotes.db';
    $db = new SQLite3($db_path);
    if (!$db) {
        return 'Datenbank konnte nicht geöffnet werden.';
    }
    $stmt = $db->prepare('SELECT content, author FROM quotes');
    if (!$stmt) {
        return 'SQL-Abfrage konnte nicht vorbereitet werden.';
    }

    $result = $stmt->execute();

    if (!$result) {
        return 'SQL-Abfrage konnte nicht ausgeführt werden.';
    }
	$quotes = [];
	while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
		$quotes[] = $row;
	}
	if (empty($quotes)) {
		return 'Keine Zitate gefunden.';
	}
	return $quotes;
}


function get_random() {
	$db_path = plugin_dir_path(__FILE__) . 'quotes.db';
    $db = new SQLite3($db_path);
    if (!$db) {
        return 'Datenbank konnte nicht geöffnet werden.';
    }
    $stmt = $db->prepare('SELECT content, author FROM quotes ORDER BY RANDOM() LIMIT 1');
    if (!$stmt) {
        return 'SQL-Abfrage konnte nicht vorbereitet werden.';
    }

    $result = $stmt->execute();

    if (!$result) {
        return 'SQL-Abfrage konnte nicht ausgeführt werden.';
    }
	
	
    $quote = $result->fetchArray(SQLITE3_ASSOC);
	if (!$quote) {
		return 'Keine Zitate gefunden.';
	}
	return $quote;
}