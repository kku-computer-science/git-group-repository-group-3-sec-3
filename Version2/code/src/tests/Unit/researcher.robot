*** Settings ***
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

# ตรวจสอบข้อความทั่วไปของหน้า Researcher
@{EXPECTED_TH}    
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

# ตรวจสอบข้อมูลของนักวิจัย
@{EXPECTED_RESEARCHER_CN}    
...    Punyaphol Horata    
...    Assoc. Prof. Dr.  
${EXPECTED_RESEARCHER_EN}    Punyaphol Horata, Ph.D. 
@{EXPECTED_RESEARCHER_TH}    
...    รศ.ดร.    
...    ปัญญาพล หอระตะ


@{EXPECTED_RESEARCHER_EXPERTISES_TH}    
...    การเรียนรู้ของเครื่องและระบบอัจฉริยะ    
...    ภาษาการเขียนโปรแกรมเชิงวัตถุ    
...    ซอฟท์คอมพิวเตอร์    
...    วิศวกรรมซอฟต์แวร์    

@{EXPECTED_RESEARCHER_EXPERTISES_EN}
...    Machine Learning and Intelligent Systems    
...    Object-Oriented Programming Languages    
...    Soft computing    
...    Software Engineering
@{EXPECTED_RESEARCHER_EXPERTISES_CN}    
...    机器学习和智能系统    
...    面向对象的编程语言    
...    软计算    
...    软件工程

${EXPECTED_RESEARCHER_INTEREST_TH}    ความเชี่ยวชาญ
${EXPECTED_RESEARCHER_INTEREST_EN}    Research interests
${EXPECTED_RESEARCHER_INTEREST_CN}    技能

*** Keywords ***

Open Browser To Home Page
    Open Browser    ${HOME_URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Navigate To Researcher Page
    Click Element    ${RESEARCHER_MENU}
    Wait Until Element Is Visible    ${DROPDOWN_MENU}    3s
    Click Element    ${COMPUTER_SCIENCE}
    Wait Until Page Contains    นักวิจัย    10s

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

Switch Language And Verify
    [Arguments]    ${lang_button}    @{expected_texts}
    Click Element    ${lang_button}
    Sleep    3s  
    Verify Page Contains Multiple Texts    @{expected_texts}

*** Test Cases ***

# ✅ ทดสอบการเปลี่ยนภาษา
Navigate To Researcher Page And Switch To Chinese
    Open Browser To Home Page
    Navigate To Researcher Page
    Switch Language And Verify    ${LANG_TO_CHINESE}    @{EXPECTED_CN}
    Close Browser

Navigate To Researcher Page And Switch To English
    Open Browser To Home Page
    Navigate To Researcher Page
    Switch Language And Verify    ${LANG_TO_ENGLISH}    @{EXPECTED_EN}
    Close Browser

# ✅ ทดสอบว่าชื่อผู้วิจัยเปลี่ยนตามภาษา
Test Researcher Name In Chinese
    Open Browser To Home Page
    Navigate To Researcher Page
    Wait And Click    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_CN}
    Close Browser


Test Researcher Name In English
    Open Browser To Home Page
    Navigate To Researcher Page
    Wait And Click    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    ${EXPECTED_RESEARCHER_EN}
    Close Browser

Test Researcher Name In Thai
    Open Browser To Home Page
    Navigate To Researcher Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_TH}
    Close Browser

# ✅ ทดสอบว่าความเชี่ยวชาญของผู้วิจัยแสดงถูกต้อง
Test Researcher Expertise In Chinese
    Open Browser To Home Page
    Navigate To Researcher Page
    Wait And Click    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_EXPERTISES_CN}
    Close Browser

Test Researcher Expertise In English
    Open Browser To Home Page
    Navigate To Researcher Page
    Wait And Click    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_EXPERTISES_EN}
    Close Browser

Test Researcher Expertise In Thai
    Open Browser To Home Page
    Navigate To Researcher Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Multiple Texts    @{EXPECTED_RESEARCHER_EXPERTISES_TH}
    Close Browser
