from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from bs4 import BeautifulSoup
import time
import re
import pymysql

# --- ตั้งค่า ChromeDriver ---
chrome_options = Options()
# chrome_options.add_argument("--headless")  # uncomment หากต้องการรันแบบไม่มี GUI
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=chrome_options)

# --- URL สำหรับหน้า Search ---
url_check = "https://search.tci-thailand.org/advance_search.html"

try:
    driver.get(url_check)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "keyword[]")))
    print("✅ Successfully loaded the website:", driver.current_url)
except Exception as e:
    print("❌ Failed to load the website:", e)
    driver.quit()
    exit()

# --- เชื่อมต่อกับฐานข้อมูล MySQL เพื่อดึงรายชื่ออาจารย์ ---
DB_HOST = 'localhost'
DB_USER = 'root'
DB_PASSWORD = ''  # ใส่รหัสผ่านที่ถูกต้อง
DB_NAME = 'myapp'

try:
    db_connection = pymysql.connect(host=DB_HOST, user=DB_USER, password=DB_PASSWORD, 
                                    database=DB_NAME, charset='utf8mb4')
    cursor = db_connection.cursor()
    print("✅ Connected to MySQL database:", DB_NAME)
except Exception as e:
    print("❌ Failed to connect to MySQL:", e)
    driver.quit()
    exit()

# ดึงรายชื่ออาจารย์จากฐานข้อมูล
retrieve = "SELECT fname_en, lname_en FROM users"
cursor.execute(retrieve)
teachers = cursor.fetchall()
cursor.close()
db_connection.close()
print(f"📌 Retrieved {len(teachers)} authors from database.")

wait = WebDriverWait(driver, 10)
metadata_list = []

for teacher in teachers:
    # ตรวจสอบว่าชื่อไม่เป็น None
    if teacher[0] is None or teacher[1] is None:
        print("⚠️ Skipping teacher with missing name:", teacher)
        continue

    tname = f"{teacher[0]} {teacher[1]}"
    print(f"\n🔎 Searching for author: {tname}")

    driver.get(url_check)
    try:
        # เลือก dropdown ให้เป็นค้นหาแบบ "author"
        select_element = wait.until(EC.presence_of_element_located((By.NAME, "criteria[]")))
        select = Select(select_element)
        select.select_by_value('author')
        
        # กรอกชื่ออาจารย์ในช่องค้นหา
        search_box = wait.until(EC.presence_of_element_located((By.NAME, "keyword[]")))
        search_box.clear()
        search_box.send_keys(tname)
        
        # คลิกปุ่มค้นหาแทนการกด Enter
        search_button = wait.until(EC.element_to_be_clickable((By.ID, "searchBtn")))
        search_button.click()
        
        # หน่วงเวลาเพื่อรอให้ผลลัพธ์โหลด
        time.sleep(3)
        
        # รอให้ element ที่แสดงจำนวนผลมีข้อความ "Search results found"
        wait.until(lambda d: "Search results found" in d.find_element(By.ID, "search_result").text)
        print(f"✅ Results loaded for {tname}")
    except Exception as e:
        print(f"❌ Error finding search elements for {tname}: {e}")
        continue

    try:
        # ดึงข้อความใน element ที่มี id "search_result"
        search_result_text = driver.find_element(By.ID, "search_result").text
        # ตัวอย่างข้อความ: "Search results found (1 item(s))"
        m = re.search(r"\((\d+)", search_result_text)
        if m:
            num_results = int(m.group(1))
        else:
            num_results = 0
        print(f"🔎 Found {num_results} results for {tname}")
    except Exception as e:
        print(f"❌ Error retrieving search result count for {tname}: {e}")
        num_results = 0

    if num_results == 0:
        continue

    try:
        results = driver.find_elements(By.CSS_SELECTOR, "div#data-article div.card")
    except Exception as e:
        print(f"❌ Error retrieving search result cards for {tname}: {e}")
        continue

    for i in range(len(results)):
        try:
            # หลังกลับมาจากหน้า article DOM อาจรีเฟรช จึงดึงผลใหม่อีกครั้ง
            results = driver.find_elements(By.CSS_SELECTOR, "div#data-article div.card")
            result = results[i]
            article_link = result.find_element(By.CSS_SELECTOR, "p a")
            article_url = article_link.get_attribute("href")
            if not article_url.startswith("http"):
                article_url = "https://search.tci-thailand.org/" + article_url
            print(f"🔗 Clicking link: {article_url}")

            current_window = driver.current_window_handle
            article_link.click()
            time.sleep(1)  # รอให้แท็บใหม่เปิดขึ้น

            all_windows = driver.window_handles
            if len(all_windows) > 1:
                new_window = [w for w in all_windows if w != current_window][0]
                driver.switch_to.window(new_window)
                wait.until(EC.presence_of_element_located((By.TAG_NAME, "h4")))
            else:
                wait.until(EC.presence_of_element_located((By.TAG_NAME, "h4")))

            article_soup = BeautifulSoup(driver.page_source, 'lxml')

            journal = article_soup.find("span", class_="journal-name").text.strip() if article_soup.find("span", class_="journal-name") else ""
            volume = article_soup.find("span", class_="volume").text.strip() if article_soup.find("span", class_="volume") else ""
            issue = article_soup.find("span", class_="issue").text.strip() if article_soup.find("span", class_="issue") else ""
            pages = article_soup.find("span", class_="pages").text.strip() if article_soup.find("span", class_="pages") else ""
            year = article_soup.find("span", class_="year").text.strip() if article_soup.find("span", class_="year") else ""
            doi = article_soup.find("span", class_="doi").text.strip() if article_soup.find("span", class_="doi") else ""
            citation_count = article_soup.find("span", class_="citation-count").text.strip() if article_soup.find("span", class_="citation-count") else ""
            research_type = article_soup.find("span", class_="research-type").text.strip() if article_soup.find("span", class_="research-type") else ""
            
            # ดึง Abstract (ภาษาอังกฤษเท่านั้น)
            abstract_elem = article_soup.find("span", id="abstract_english")
            abstract = abstract_elem.text.strip() if abstract_elem else ""
            
            # ดึง Keywords (ภาษาอังกฤษเท่านั้น)
            keyword_elem = article_soup.find("span", id="keyword_english")
            keywords = keyword_elem.text.strip() if keyword_elem else ""
            
            # ดึง title ภาษาอังกฤษเท่านั้น
            title_eng = article_soup.find("h4", {"id": "article_name_eng"})
            title = title_eng.text.strip() if title_eng else ""
            
            authors = [a.text.strip() for a in article_soup.find_all("span", class_="author_name")]

            metadata_list.append((title, article_url, journal, volume, issue, pages, year, doi,
                                  citation_count, research_type, abstract, keywords, ', '.join(authors)))
            print(f"✅ Retrieved article: {title}")

            if len(driver.window_handles) > 1:
                driver.close()  # ปิดแท็บใหม่
                driver.switch_to.window(current_window)
            else:
                driver.back()
                wait.until(EC.presence_of_element_located((By.ID, "data-article")))
        except Exception as e:
            print(f"❌ Error processing article: {e}")
            try:
                if len(driver.window_handles) > 1:
                    driver.close()
                    driver.switch_to.window(current_window)
                else:
                    driver.back()
                    wait.until(EC.presence_of_element_located((By.ID, "data-article")))
            except Exception as ex:
                print(f"❌ Error returning to search results: {ex}")
            continue

driver.quit()
print(f"\n✅ Web Scraping Completed. Retrieved {len(metadata_list)} articles.")

# --- Insert scraped data into MySQL database table tci_research_papers ---
try:
    db_connection = pymysql.connect(host=DB_HOST, user=DB_USER, password=DB_PASSWORD, 
                                    database=DB_NAME, charset='utf8mb4')
    cursor = db_connection.cursor()
    
    insert_sql = """
    INSERT INTO tci_research_papers 
    (title, article_url, journal, volume, issue, pages, year, doi, citation_count, research_type, abstract, keywords, authors)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    
    for article in metadata_list:
        cursor.execute(insert_sql, article)
    
    db_connection.commit()
    print("✅ Data inserted into tci_research_papers table successfully.")
except Exception as e:
    print("❌ Error inserting data into database:", e)
finally:
    cursor.close()
    db_connection.close()
