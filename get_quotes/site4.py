import requests
from bs4 import BeautifulSoup
import sqlite3

conn = sqlite3.connect("quotes/quotes.db")
cursor = conn.cursor()

url = "https://quotefancy.com/basketball-quotes"

response = requests.get(url)
soup = BeautifulSoup(response.text, "html.parser")
quotes = soup.find_all("div", class_="q-container")
for quote in quotes:
    content = quote.find("a", class_="quote-a").text
    authors = quote.find_all("a")
    if len(authors) > 1:  # Check if there are at least two 'a' tags
        author = authors[1].text
        # print(author)
    else:
        print("Author not found")
        continue

    cursor.execute(
        "INSERT INTO quotes (content, author) VALUES (?, ?)", (content, author))
conn.commit()
