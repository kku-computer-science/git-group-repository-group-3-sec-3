*** Settings ***
Documentation    UAT: Home -> Researcher -> ResearcherProfile
Library          SeleniumLibrary
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${HOME_URL}    http://127.0.0.1:8000/
${WAIT_TIME}  3s

# ตัวแปรของเมนูและ dropdown
${RESEARCHER_MENU}    xpath=//a[@id='navbarDropdown']
${DROPDOWN_MENU}    xpath=//ul[contains(@class, 'dropdown-menu show')]
${COMPUTER_SCIENCE}    xpath=//ul[contains(@class, 'dropdown-menu show')]//a[contains(@href, '/researchers/1')]

# เปลี่ยนภาษา
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

# Detail Page Link (สมมุติว่าลิงก์ไปหน้า Profile ด้วย /detail/xxx)
# เปลี่ยน XPATH ให้ตรงกับลิงก์ที่แท้จริง เช่น ...
${RESEARCHER_DETAIL}    xpath=//a[contains(@href, '/detail/') and .//*[contains(text(), 'Punyaphol')]]    

# ตรวจสอบองค์ประกอบบนหน้า Profile
*** Variables ***

@{EXPECTED_PROFILE_TH}
...    ค้นหา
...    คำค้นหาที่สนใจ
...    ลำดับ
...    ปี
...    ชื่องานวิจัย
...    ชื่อผู้แต่ง
...    ประเภทงานวิจัย
...    หมายเลขหน้า
...    ตีพิมพ์ที่
...    จำนวนการอ้างอิง
...    Doi
...    แหล่งที่มา
...    สรุป
...    หนังสือ
...    ผลงานวิชาการด้านอื่น ๆ
...    อีเมล
...    ลำดับ
...    ชื่อ
...    ตีพิมพ์
...    ส่งออกไฟล์ exel
...    ชื่อหนังสือ
...    ตีพิมพ์ที่
...    ประเภท
...    วันที่จดทะเบียน
...    หมายเลขจดทะเบียน

@{EXPECTED_PROFILE_EN}
...    Search
...    Research interests
...    No.
...    Year
...    Paper Name
...    Author
...    Document Type
...    Page
...    Journals/Transactions
...    Citations
...    Doi
...    Source
...    Summary
...    Book
...    Other Academic Works
...    email
...    Number
...    Name
...    publication        <-- (ค่าจาก 'pub' ใน dictionary ภาษาอังกฤษอาจเป็นการกรอกผิด หรือยังไม่อัปเดต) -->
...    Export To Exel
...    Name          <-- (ค่าจาก 'bookName' ที่เป็น '์Name' อาจต้องตรวจสอบอีกครั้ง) -->
...    Place of Publication
...    Type
...    Registration Date
...    Registration Number
    
@{EXPECTED_PROFILE_CN}      
...    搜索      
...    研究兴趣    
...    编号    
...    年份    
...    论文名称    
...    作者    
...    文献类型    
...    页数    
...    期刊/交易    
...    引用次数    
...    数字对象标识符 (DOI)    
...    来源    
...    摘要    
...    书籍    
...    其他学术作品    
...    电子邮件    
...    数量    
...    名称    
...    出版    
...    导出到Excel    
...    书名    
...    出版地点    
...    类型    
...    注册日期    
...    注册编号


*** Keywords ***
Open Browser To Home Page
    Open Browser    ${HOME_URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Navigate To Researcher Profile
    # 1) กด Researcher Menu
    Click Element    ${RESEARCHER_MENU}
    Wait Until Element Is Visible    ${DROPDOWN_MENU}    3s

    # 2) เลือกสาขา Computer Science
    Click Element    ${COMPUTER_SCIENCE}

    # 3) รอจนกว่าหน้ารายชื่อนักวิจัยจะแสดง
    Wait Until Page Contains    นักวิจัย    10s

    # 4) คลิกลิงก์เข้าไปที่หน้า Researcher Profile
    Click Element    ${RESEARCHER_DETAIL}
    Wait Until Page Contains    Summary    10s    # สมมติว่าหน้า Profile มีคำว่า Summary

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

Switch Language
    [Arguments]    ${lang_button}
    Click Element    ${lang_button}
    Sleep    2s  # รอหน้าอัปเดต

*** Test Cases ***
# --- 1) ตรวจสอบหน้า Profile ภาษาที่ตั้งต้นเป็นไทย ---
Test Researcher Profile In Thai
    Open Browser To Home Page
    Navigate To Researcher Profile
    # ตรวจสอบเนื้อหาหน้า (ไม่ต้องกดปุ่มไทย เพราะเป็น default)
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_TH}
    Close Browser

# --- 2) เปลี่ยนเป็นภาษาอังกฤษและตรวจสอบ ---
Test Researcher Profile In English
    Open Browser To Home Page
    Navigate To Researcher Profile
    Switch Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_EN}
    Close Browser

# --- 3) เปลี่ยนเป็นภาษาจีนและตรวจสอบ ---
Test Researcher Profile In Chinese
    Open Browser To Home Page
    Navigate To Researcher Profile
    Switch Language    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_CN}
    Close Browser
