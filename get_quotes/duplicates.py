from collections import Counter
import sqlite3

# Connect to the database
conn = sqlite3.connect("quotes/quotes.db")
cursor = conn.cursor()

# Fetch all quotes by Kobe Bryant
rows = cursor.execute(
    "SELECT * FROM quotes WHERE author = 'Kobe Bryant'").fetchall()
quotes = [row[0] for row in rows]

# Count occurrences of each quote
counter = Counter(quotes)

# Find duplicates
duplicates = [item for item, count in counter.items() if count > 1]
print("Duplicates:", duplicates)

# Remove duplicates, keeping only the first occurrence
for duplicate in duplicates:
    # Find all rows matching the duplicate
    duplicate_rows = cursor.execute(
        "SELECT rowid FROM quotes WHERE author = 'Kobe Bryant' AND content = ?", (
            duplicate,)
    ).fetchall()

    # Remove all but the first occurrence
    for row in duplicate_rows[1:]:
        cursor.execute("DELETE FROM quotes WHERE rowid = ?", (row[0],))

# Commit changes and close connection
conn.commit()
conn.close()

print("Duplicates removed successfully.")
