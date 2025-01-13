import requests
from bs4 import BeautifulSoup
import sqlite3

conn = sqlite3.connect("quotes/quotes.db")
cursor = conn.cursor()

url = "https://www.basketballmindsettraining.com/blog/mamba-mentality-quotes"

response = requests.get(url)
soup = BeautifulSoup(response.text, "html.parser")
elements = soup.find_all("p", attrs={"data-slot-rendered-content": "true"})

main = soup.find("div", class_="blog-post-body__content")
for element in main.find_all("p"):
    elements.append(element)
quotes = []
for element in elements:
    quotes.append(element.text)

for quote in quotes:
    if quote.strip() != "":
        try:
            int(quote[0])
        except:
            continue
        parts = quote.split("-")
        text = parts[0].split(".")
        text.pop(0)
        text.pop(-1)
        content = ".".join(text)
        content = content.strip()
        content = content[1:]
        if content.replace(" ", "") == "":
            continue
        author = "Kobe Bryant"
        cursor.execute(
            "INSERT INTO quotes (content, author) VALUES (?, ?)", (content, author))

conn.commit()
