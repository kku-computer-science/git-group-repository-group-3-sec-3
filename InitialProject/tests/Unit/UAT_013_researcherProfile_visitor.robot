*** Settings ***
Documentation    UAT: Home -> Researcher -> ResearcherProfile
Library          SeleniumLibrary
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${HOME_URL}   https://cssoften0368.cpkkuhost.com
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
${RESEARCHER_DETAIL}    xpath=//a[contains(@href, '/detail/') and .//*[contains(text(), 'Chitsutha Soomlek')]]   
${EXPECTED_RESEARCHER_INTEREST_TH}    ความเชี่ยวชาญ
${EXPECTED_RESEARCHER_INTEREST_EN}    Research interests
${EXPECTED_RESEARCHER_INTEREST_CN}    技能 

# ตรวจสอบข้อมูลของนักวิจัย
@{EXPECTED_RESEARCHER_CN}    Asst.    Prof.    Chitsutha Soomlek    Ph.D.   
@{EXPECTED_RESEARCHER_NAME_EN}    Asst.    Prof.    Chitsutha Soomlek    Ph.D. 
@{EXPECTED_RESEARCHER_NAME_TH}    
...    ผศ.ดร.    
...    ชิตสุธา สุ่มเล็ก

# ตรวจสอบข้อความทั่วไปของหน้า Researcher
@{EXPECTED_RESEARCHER_TH}    
...    นักวิจัย    
...    ค้นหา    
...    คำค้นหาที่สนใจ    
...    สาขาวิชาวิทยาการคอมพิวเตอร์    
@{EXPECTED_CN}    
...    研究人员    
...    搜索    
...    计算机科学
...    研究兴趣        
@{EXPECTED_EN}    
...    Researchers    
...    Search    
...    Research interests    
...    Computer Science

@{EXPECTED_RESEARCHER_EXPERTISES_TH}    
...    คุณภาพของโค้ดและซอฟต์แวร์  
...    ความปลอดภัยของเครือข่ายคอมพิวเตอร์    
...    ตัวแทนเคลื่อนที่และระบบหลายเอเยนต์    
...    วิศวกรรมซอฟต์แวร์ที่ปลอดภัย    

@{EXPECTED_RESEARCHER_EXPERTISES_EN}
...     Code Smells and Software Quality   
...     Computer Network Security    
...     Mobile Agent and Multi-Agent Systems    
...     Secure Software Engineering
...     Software Engineering
...     Software Process 
@{EXPECTED_RESEARCHER_EXPERTISES_CN}    
...     代码气味和软件质量    
...     计算机网络安全    
...     移动代理和多智能体系统    
...     安全软件工程

# ตรวจสอบองค์ประกอบบนหน้า Profile
@{EXPECTED_PROFILE_TH}
...    ค้นหา    
...    การศึกษา    2547     วศ.บ.    (วิศวกรรมคอมพิวเตอร์)    สถาบันเทคโนโลยีพระจอมเกล้า    เจ้าคุณทหารลาดกระบัง
...    ลำดับ
...    ปี    2565   
...    ชื่องานวิจัย
...    ชื่อผู้แต่ง    สิรภัทร เชี่ยวชาญวัฒนา    ชิตสุธา สุ่มเล็ก    คำรณ สุนัติ
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
...    Year    2022
...    Paper Name
...    Author    Florentina Yuni Arini    Sirapat Chiewchanwattana    Chitsutha Soomlek
...    Document Type    Journal
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
...    Publications        
...    Export To Exel
...    Name          
...    Place of Publication
...    Type
...    Registration Date
...    Registration Number    
...    2004    B.Eng.    (Computer Engineering)    King Mongkut's    University    of    Technology    Thonburi    
...    2006    MA.Sc.    (Electronic Systems Engineering)    University    of    Regina    Canada    
...    2013    Ph.D.    (Electronic Systems Engineering)    University    of    Regina    Canada
    
@{EXPECTED_PROFILE_CN}      
...    搜索      
...    编号 
...    教育   
...    年份    
...    论文名称    
...    作者    
...    文献类型    
...    页数    
...    期刊    交易    
...    引用次数    
...    数字对象标识符 (DOI)    
...    来源    
...    摘要    
...    书籍    
...    其他学术作品    
...    电子邮件    
...    数量    
...    名称    
...    出版物    
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

Navigate To Researcher Page
    # 1) กด Researcher Menu
    Click Element    ${RESEARCHER_MENU}
    Wait Until Element Is Visible    ${DROPDOWN_MENU}    3s

    # 2) เลือกสาขา Computer Science
    Click Element    ${COMPUTER_SCIENCE}

    # 3) รอจนกว่าหน้ารายชื่อนักวิจัยจะแสดง
    Wait Until Page Contains    นักวิจัย    10s

Navigate To Researcher Profile
    # 4) คลิกลิงก์เข้าไปที่หน้า Researcher Profile
    Click Element    ${RESEARCHER_DETAIL}
    #Wait Until Page Contains    Education    10s    # สมมติว่าหน้า Profile มีคำว่า Summary

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

Scroll Page Down
    ${total_height}=    Execute JavaScript    return document.body.scrollHeight
    ${scroll_position}=    Set Variable    0

    WHILE    ${scroll_position} < ${total_height}
        ${scroll_position}=    Evaluate    ${scroll_position} + 300
        Execute JavaScript    window.scrollTo(0, ${scroll_position})
        Sleep    0.5s
    END

Scroll Page Down Slightly
    Execute JavaScript    window.scrollBy(0, 500)
    Sleep    0.5s

Scroll Up
    Execute JavaScript    window.scrollTo(0, 0)    

Switch Language
    [Arguments]    ${lang_button}
    Click Element    ${lang_button}
    Sleep    2s  # รอหน้าอัปเดต

*** Test Cases ***
 # --- 1) ตรวจสอบหน้า Profile ภาษาที่ตั้งต้นเป็นไทย ---
Test Researcher Profile In English
    Open Browser To Home Page
    Navigate To Researcher Page
    Sleep   2s
    Switch Language    ${LANG_TO_ENGLISH} 
    Sleep   1s   
    Verify Page Contains Multiple Texts    @{EXPECTED_EN}
    Sleep   1s
    Scroll Page Down Slightly
    Sleep   1s
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_NAME_EN} 
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_EXPERTISES_EN} 
    Sleep    5s
    Navigate To Researcher Profile
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_EN}
    Sleep    0.5s
    Switch Language    ${LANG_TO_THAI}
    Scroll Page Down Slightly
    Sleep   1s
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_TH}
    Scroll Page Down
    Scroll Up

    Close Browser

Test Researcher Profile In Chinese
    Open Browser To Home Page
    Navigate To Researcher Page
    Sleep   2s
    Switch Language    ${LANG_TO_CHINESE} 
    Sleep   1s   
    Verify Page Contains Multiple Texts    @{EXPECTED_CN}
    Sleep   1s
    Scroll Page Down Slightly
    Sleep   1s
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_NAME_EN} 
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_EXPERTISES_CN} 
    Sleep    5s
    Navigate To Researcher Profile
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_CN}
    Sleep    0.5s
    Switch Language    ${LANG_TO_THAI}
    Scroll Page Down Slightly
    Sleep   1s
    Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_TH}
    Scroll Page Down
    Scroll Up

    Close Browser

# --- 2) เปลี่ยนเป็นภาษาอังกฤษและตรวจสอบ ---
# Test Researcher Profile In English
#     Open Browser To Home Page
#     Navigate To Researcher Profile
#     Switch Language    ${LANG_TO_ENGLISH}
#     Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_EN}
#     Close Browser

# --- 3) เปลี่ยนเป็นภาษาจีนและตรวจสอบ ---
# Test Researcher Profile In Chinese
#     Open Browser To Home Page
#     Navigate To Researcher Profile
#     Switch Language    ${LANG_TO_CHINESE}
#     Verify Page Contains Multiple Texts    @{EXPECTED_PROFILE_CN}
#     Close Browser
