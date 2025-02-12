#!/usr/bin/env python3
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

# ตั้งค่า logging ให้บันทึกลงในไฟล์ (คุณสามารถเปลี่ยน path หรือระดับ log ได้ตามต้องการ)
logging.basicConfig(filename='scraper.log', level=logging.INFO,
                    format='%(asctime)s %(levelname)s: %(message)s')

# ตรวจสอบว่ามีพารามิเตอร์ของชื่ออาจารย์ถูกส่งเข้ามาหรือไม่
if len(sys.argv) < 3:
    print(json.dumps({"error": "Missing teacher first name or last name"}))
    sys.exit(1)

teacher_fname = sys.argv[1]
teacher_lname = sys.argv[2]
tname = f"{teacher_fname} {teacher_lname}"

# บันทึกข้อความ debug ลงใน log (ไม่ส่งออก stdout)
logging.info(f"Searching for author: {tname}")

# --- ตั้งค่า ChromeDriver ---
chrome_options = Options()
# หากต้องการรันแบบไม่มี GUI ให้เปิดบรรทัดด้านล่าง
chrome_options.add_argument("--headless")
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=chrome_options)

# --- URL สำหรับหน้า Search ของ TCI ---
url_check = "https://search.tci-thailand.org/advance_search.html"

try:
    driver.get(url_check)
    wait = WebDriverWait(driver, 10)
    wait.until(EC.presence_of_element_located((By.NAME, "keyword[]")))
except Exception as e:
    print(json.dumps({"error": f"Failed to load website: {str(e)}"}))
    driver.quit()
    sys.exit(1)

# ตั้งค่าให้ค้นหาแบบ "author"
try:
    select_element = wait.until(EC.presence_of_element_located((By.NAME, "criteria[]")))
    select = Select(select_element)
    select.select_by_value('author')
    search_box = wait.until(EC.presence_of_element_located((By.NAME, "keyword[]")))
    search_box.clear()
    search_box.send_keys(tname)
    search_button = wait.until(EC.element_to_be_clickable((By.ID, "searchBtn")))
    search_button.click()
    # รอให้ผลการค้นหาโหลดขึ้น
    time.sleep(3)
    wait.until(lambda d: "Search results found" in d.find_element(By.ID, "search_result").text)
except Exception as e:
    # หากไม่พบผลการค้นหา ส่งออกเป็น JSON array ว่าง
    print(json.dumps([]))
    driver.quit()
    sys.exit(0)

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
    print(json.dumps([]))
    driver.quit()
    sys.exit(0)

metadata_list = []

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

        # พยายามดึงข้อมูลจาก element ที่มี id "volume_issue"
        vol_issue_span = article_soup.find("span", id="volume_issue")
        if vol_issue_span:
            vol_issue_text = vol_issue_span.text.strip()
            # ตัวอย่างข้อความ: "Volume 24, Issue 2, 2017, pp.239-245"
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
            # fallback หากไม่พบ element "volume_issue"
            volume = article_soup.find("span", class_="volume").text.strip() if article_soup.find("span", class_="volume") else ""
            issue = article_soup.find("span", class_="issue").text.strip() if article_soup.find("span", class_="issue") else ""
            year = article_soup.find("span", class_="year").text.strip() if article_soup.find("span", class_="year") else ""
            pages = article_soup.find("span", class_="pages").text.strip() if article_soup.find("span", class_="pages") else ""

        doi = article_soup.find("span", class_="doi").text.strip() if article_soup.find("span", class_="doi") else ""
        citation_count = article_soup.find("span", class_="citation-count").text.strip() if article_soup.find("span", class_="citation-count") else ""
        research_type = article_soup.find("span", class_="research-type").text.strip() if article_soup.find("span", class_="research-type") else ""
        abstract_elem = article_soup.find("span", id="abstract_english")
        abstract = abstract_elem.text.strip() if abstract_elem else ""
        keyword_elem = article_soup.find("span", id="keyword_english")
        keywords = keyword_elem.text.strip() if keyword_elem else ""
        title_eng = article_soup.find("h4", {"id": "article_name_eng"})
        title = title_eng.text.strip() if title_eng else ""

        # ดึงข้อมูลรายชื่อสมาชิก (authors) ของโครงงาน
        # เราจะใช้เฉพาะ element แรกที่พบ และแบ่งด้วย comma
        author_elems = article_soup.find_all("span", class_="author_name")
        authors = []
        if author_elems:
            # ใช้ element แรกเท่านั้น เพื่อหลีกเลี่ยง duplicate
            author_text = author_elems[0].get_text(strip=True)
            # สมมุติว่ารายชื่อถูกคั่นด้วย comma
            raw_authors = author_text.split(',')
            for raw in raw_authors:
                # ลบ "No" หรือ "*" ตามด้วยตัวเลขออก (เช่น "No1" หรือ "*1")
                processed = re.sub(r'(No|\*)\d+', '', raw)
                # ลบอักขระที่ไม่ใช่ตัวอักษรภาษาอังกฤษและช่องว่างออก
                processed = re.sub(r'[^A-Za-z\s]', '', processed)
                processed = processed.strip()
                if processed and processed not in authors:
                    authors.append(processed)
        else:
            authors = []

        metadata_list.append({
            "title": title,
            "article_url": article_url,
            "journal": journal,
            "volume": volume,
            "issue": issue,
            "pages": pages,
            "year": year,
            "doi": doi,
            "citation_count": citation_count,
            "research_type": research_type,
            "abstract": abstract,
            "keywords": keywords,
            "authors": authors  # รายชื่อสมาชิกที่ทำโครงงาน
        })

        if len(driver.window_handles) > 1:
            driver.close()
            driver.switch_to.window(current_window)
        else:
            driver.back()
            wait.until(EC.presence_of_element_located((By.ID, "data-article")))
    except Exception as e:
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

driver.quit()
# ส่งออกผลลัพธ์เป็น JSON โดยจัดรูปแบบให้อ่านง่าย (มี indent)
print(json.dumps(metadata_list, ensure_ascii=False, indent=4))
