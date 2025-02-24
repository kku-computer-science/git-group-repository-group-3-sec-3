#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import sys
sys.stdout.reconfigure(encoding='utf-8')  # กำหนดให้ stdout ใช้ UTF-8

import json
import time
import re
import logging
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from bs4 import BeautifulSoup

# ตั้งค่า logging ให้บันทึกลงในไฟล์
logging.basicConfig(filename='scraper.log', level=logging.INFO,
                    format='%(asctime)s %(levelname)s: %(message)s')

# ตรวจสอบว่ามีพารามิเตอร์ที่ต้องการครบหรือไม่ (4 ตัว: ชื่อ-นามสกุลอังกฤษ และ ชื่อ-นามสกุลไทย)
if len(sys.argv) < 5:
    print(json.dumps({"error": "Missing teacher name parameters. Usage: python web_scraper.py <fname_eng> <lname_eng> <fname_th> <lname_th>"}))
    sys.exit(1)

teacher_fname_eng = sys.argv[1]
teacher_lname_eng = sys.argv[2]
teacher_fname_th = sys.argv[3]
teacher_lname_th = sys.argv[4]

tname_eng = f"{teacher_fname_eng} {teacher_lname_eng}"
tname_th = f"{teacher_fname_th} {teacher_lname_th}"

logging.info(f"Searching for author (English): {tname_eng}")
logging.info(f"Searching for author (Thai): {tname_th}")

# --- ตั้งค่า ChromeDriver ---
chrome_options = Options()
chrome_options.add_argument("--headless")  # รันแบบไม่มี GUI
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=chrome_options)

# URL สำหรับหน้า Advanced Search ของ TCI
url_check = "https://search.tci-thailand.org/advance_search.html"

# ฟังก์ชันสำหรับสแครปบทความจากการค้นหาด้วยชื่อผู้แต่งที่กำหนด
def scrape_articles(teacher_name):
    articles = []
    try:
        driver.get(url_check)
        wait = WebDriverWait(driver, 10)
        wait.until(EC.presence_of_element_located((By.NAME, "keyword[]")))
    except Exception as e:
        logging.error(f"Failed to load search page: {e}")
        return articles

    # ตั้งค่าให้ค้นหาแบบ "author"
    try:
        select_element = wait.until(EC.presence_of_element_located((By.NAME, "criteria[]")))
        select = Select(select_element)
        select.select_by_value('author')
        search_box = wait.until(EC.presence_of_element_located((By.NAME, "keyword[]")))
        search_box.clear()
        search_box.send_keys(teacher_name)
        search_button = wait.until(EC.element_to_be_clickable((By.ID, "searchBtn")))
        search_button.click()
        # รอให้ผลการค้นหาโหลดขึ้น
        time.sleep(3)
        wait.until(lambda d: "Search results found" in d.find_element(By.ID, "search_result").text)
    except Exception as e:
        logging.error(f"No search results for {teacher_name}: {e}")
        return articles

    # ตรวจสอบจำนวนผลการค้นหา
    try:
        search_result_text = driver.find_element(By.ID, "search_result").text
        m = re.search(r"\((\d+)", search_result_text)
        if m:
            num_results = int(m.group(1))
        else:
            num_results = 0
    except Exception as e:
        num_results = 0

    if num_results == 0:
        return articles

    # ดึงผลลัพธ์การค้นหา (article cards)
    try:
        results = driver.find_elements(By.CSS_SELECTOR, "div#data-article div.card")
    except Exception as e:
        results = []

    for i in range(len(results)):
        try:
            # อัพเดทผลการค้นหาใหม่ทุกครั้ง (กรณี DOM เปลี่ยนแปลง)
            results = driver.find_elements(By.CSS_SELECTOR, "div#data-article div.card")
            result = results[i]
            article_link = result.find_element(By.CSS_SELECTOR, "p a")
            article_url = article_link.get_attribute("href")
            if not article_url.startswith("http"):
                article_url = "https://search.tci-thailand.org/" + article_url

            current_window = driver.current_window_handle
            article_link.click()
            time.sleep(1)

            all_windows = driver.window_handles
            if len(all_windows) > 1:
                new_window = [w for w in all_windows if w != current_window][0]
                driver.switch_to.window(new_window)
                wait.until(EC.presence_of_element_located((By.TAG_NAME, "h4")))
            else:
                wait.until(EC.presence_of_element_located((By.TAG_NAME, "h4")))

            article_soup = BeautifulSoup(driver.page_source, 'lxml')

            # ดึงข้อมูลจากหน้า article
            journal = article_soup.find("span", class_="journal-name").text.strip() if article_soup.find("span", class_="journal-name") else ""

            # ดึงข้อมูล Volume, Issue, Year และ Pages
            vol_issue_span = article_soup.find("span", id="volume_issue")
            if vol_issue_span:
                vol_issue_text = vol_issue_span.text.strip()
                match = re.search(r'Volume\s*(\d+),\s*Issue\s*(\d+),\s*(\d{4}),\s*pp\.?\s*([\d\-]+)', vol_issue_text, re.IGNORECASE)
                if match:
                    volume = match.group(1)
                    issue = match.group(2)
                    year = match.group(3)
                    pages = match.group(4)
                else:
                    volume = ""
                    issue = ""
                    year = ""
                    pages = ""
            else:
                volume = article_soup.find("span", class_="volume").text.strip() if article_soup.find("span", class_="volume") else ""
                issue = article_soup.find("span", class_="issue").text.strip() if article_soup.find("span", class_="issue") else ""
                year = article_soup.find("span", class_="year").text.strip() if article_soup.find("span", class_="year") else ""
                pages = article_soup.find("span", class_="pages").text.strip() if article_soup.find("span", class_="pages") else ""

            doi = article_soup.find("span", class_="doi").text.strip() if article_soup.find("span", class_="doi") else ""
            citation_count = article_soup.find("span", class_="citation-count").text.strip() if article_soup.find("span", class_="citation-count") else ""
            research_type = article_soup.find("span", class_="research-type").text.strip() if article_soup.find("span", class_="research-type") else ""

            # ดึงข้อมูล Title, Abstract และ Keywords ในทั้งสองภาษา
            title_eng_elem = article_soup.find("h4", {"id": "article_name_eng"})
            title_eng = title_eng_elem.text.strip() if title_eng_elem else ""
            title_th_elem = article_soup.find("h4", {"id": "article_name_th"})
            title_th = title_th_elem.text.strip() if title_th_elem else ""

            abstract_eng_elem = article_soup.find("span", id="abstract_english")
            abstract_eng = abstract_eng_elem.text.strip() if abstract_eng_elem else ""
            abstract_th_elem = article_soup.find("span", id="abstract_thai")
            abstract_th = abstract_th_elem.text.strip() if abstract_th_elem else ""

            keyword_eng_elem = article_soup.find("span", id="keyword_english")
            keywords_eng = keyword_eng_elem.text.strip() if keyword_eng_elem else ""
            keyword_th_elem = article_soup.find("span", id="keyword_thai")
            keywords_th = keyword_th_elem.text.strip() if keyword_th_elem else ""

            # ดึงรายชื่อผู้แต่ง (authors)
            author_elems = article_soup.find_all("span", class_="author_name")
            authors = []
            if author_elems:
                # สมมุติว่า element แรกมีรายชื่อทั้งหมด (คั่นด้วย comma)
                author_text = author_elems[0].get_text(strip=True)
                raw_authors = author_text.split(',')
                for raw in raw_authors:
                    # ลบ "No" หรือ "*" ตามด้วยตัวเลขออก
                    processed = re.sub(r'(No|\*)\d+', '', raw)
                    # อนุญาตให้มีอักขระภาษาอังกฤษและภาษาไทย (Unicode \u0E00-\u0E7F)
                    processed = re.sub(r'[^A-Za-z\u0E00-\u0E7F\s]', '', processed)
                    processed = processed.strip()
                    if processed and processed not in authors:
                        authors.append(processed)
            else:
                authors = []

            articles.append({
                "title_eng": title_eng,
                "title_th": title_th,
                "article_url": article_url,
                "journal": journal,
                "volume": volume,
                "issue": issue,
                "pages": pages,
                "year": year,
                "doi": doi,
                "citation_count": citation_count,
                "research_type": research_type,
                "abstract_eng": abstract_eng,
                "abstract_th": abstract_th,
                "keywords_eng": keywords_eng,
                "keywords_th": keywords_th,
                "authors": authors
            })

            # ปิดหน้าต่างใหม่ (ถ้ามี) แล้วกลับไปยังผลการค้นหา
            if len(driver.window_handles) > 1:
                driver.close()
                driver.switch_to.window(current_window)
            else:
                driver.back()
                wait.until(EC.presence_of_element_located((By.ID, "data-article")))
        except Exception as e:
            logging.error(f"Error scraping an article: {e}")
            try:
                if len(driver.window_handles) > 1:
                    driver.close()
                    driver.switch_to.window(current_window)
                else:
                    driver.back()
                    wait.until(EC.presence_of_element_located((By.ID, "data-article")))
            except Exception as ex:
                pass
            continue
    return articles

# สแครปข้อมูลสำหรับชื่อภาษาอังกฤษและภาษาไทย
metadata_list_eng = scrape_articles(tname_eng)
metadata_list_th = scrape_articles(tname_th)

# รวมผลลัพธ์และลบรายการที่ซ้ำกัน (โดยใช้ article_url เป็น key)
combined = {}
for item in metadata_list_eng + metadata_list_th:
    combined[item["article_url"]] = item

final_list = list(combined.values())

driver.quit()

# ส่งออกผลลัพธ์เป็น JSON โดยจัดรูปแบบให้อ่านง่าย (indent 4)
print(json.dumps(final_list, ensure_ascii=False, indent=4))
