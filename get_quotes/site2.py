import requests
from bs4 import BeautifulSoup
import sqlite3

conn = sqlite3.connect("quotes/quotes.db")
cursor = conn.cursor()

url = "https://www.basketballmindsettraining.com/blog/basketball-coach-quotes"

response = requests.get(url)
soup = BeautifulSoup(response.text, "html.parser")
quotes = []
elements = soup.find_all("p", class_="b-qt qt_380476")
for element in elements:
    if element.text.strip() != "":
        quotes.append(element.text)
quotes = quotes[:-2]
for quote in quotes:
    parts = quote.split("-")
    author = parts[-1].strip()
    content = parts[0]
    content = content.strip()
    content = content.replace("“", "")
    content = content.replace('"', "")
    content.strip()
    if content.replace(" ", "") == "":
        continue
    cursor.execute(
        "INSERT INTO quotes (content, author) VALUES (?, ?)", (content, author))

conn.commit()
conn.close()
