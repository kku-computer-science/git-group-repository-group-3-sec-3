*** Settings ***
Documentation    Test suite for verifying language switching and dynamic data on the research project page.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}            chrome
${URL}                https://cssoften0368.cpkkuhost.com
${WAIT_TIME}          3s
  

${RESEARCH_PROJECT_MENU}    xpath=//nav//a[contains(@class, 'nav-link') and contains(text(), 'โครงการวิจัย')]

@{EXPECTED_PROJECT_DETAIL_EN}    
...    Thanaphon Tangchoopong    Warunya Wunnasri, Ph.D.    
...    2020    
...    1   August    2020    to    19    August    2020    
...    External funding    
...    Office    of    the    Permanent    Secretary    (OPS)    MHESI Thailand    
...    Computer Science

@{EXPECTED_PROJECT_DETAIL_TH}    
...    อ. ธนพล ตั้งชูพงศ์    อ.ดร. วรัญญา วรรณศรี   
...    2563    
...    1    สิงหาคม    2563    ถึง    19    สิงหาคม    2563   
...    ทุนภายนอก   
...    สำนักงานปลัดกระทรวงอุดมศึกษา    วิทยาศาสตร์    วิจัยและนวัตกรรม   
...    สาขาวิชาวิทยาการคอมพิวเตอร์

@{EXPECTED_PROJECT_DETAIL_CN}    
...    Thanaphon Tangchoopong    Warunya Wunnasri, Ph.D.   
...    2020     
...    2020    年    8    月    1    日    至    2020    年    8    月    19    日   
...    外部资本   
...    高等教育、科研与创新部常务秘书办公室   
...    计算机科学系
   

# ✅ Expected Static Texts
@{EXPECTED_THAI_TEXTS}    
...    โครงการบริการวิชาการ/ โครงการวิจัย    
...    ผู้รับผิดชอบโครงการ    
...    รายละเอียด   
...    ลำดับ    
...    ปี    
...    ชื่อโครงการ    
...    สถานะ    
...    ระยะเวลาโครงการ    
...    ประเภทของทุนวิจัย    
...    หน่วยงานที่สนันสนุนทุน    
...    หน่วยงานที่รับผิดชอบ    
...    งบประมาณที่ได้รับจัดสรร    
...    บาท    
...    ปิดโครงการ    
...    ถึง

@{EXPECTED_ENGLISH_TEXTS}    
...    Academic Service Project / Research Project    
...    Order    
...    Year    
...    Project Name    
...    Detail    
...    Project Supervisor    
...    Status    
...    Duration    
...    Research Type    
...    Funding Agency    
...    Responsible Agency    
...    Project Budget    
...    Baht    
...    Completed

@{EXPECTED_CHINESE_TEXTS}    
...    学术服务项目 / 研究项目    
...    订单    
...    年份    
...    项目名称    
...    详情    
...    项目负责人    
...    状态    
...    持续时间   
...    研究类型    
...    资助机构    
...    责任机构    
...    项目预算    
...    泰铢    
...    已完成


# ✅ Language Switchers
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

*** Keywords ***
Open Browser To Report Page
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

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

Go To Research Project
    Wait Until Element Is Visible    ${RESEARCH_PROJECT_MENU}    timeout=10s
    Scroll Element Into View         ${RESEARCH_PROJECT_MENU}
    Sleep    1s
    Click Element                    ${RESEARCH_PROJECT_MENU}
    Sleep    2s

Scroll Page Down Slightly
    Execute JavaScript    window.scrollBy(0, 500)
    Sleep    0.5s

Scroll Up
    Execute JavaScript    window.scrollTo(0, 0)

Switch Language
    [Arguments]    ${lang_button}
    Click Element    ${lang_button}
    Sleep    1s  # รอหน้าอัปเดต    

*** Test Cases ***

Test Research Project from Thai to English
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Go To Research Project
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROJECT_DETAIL_TH}
    Sleep    0.5s
    Switch Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_ENGLISH_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROJECT_DETAIL_EN}
    Scroll Page Down
    Scroll Up
    Sleep    1s
    Close Browser

Test Research Project from Thai to Chinese
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Go To Research Project
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROJECT_DETAIL_TH}
    Sleep    0.5s
    Switch Language    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_CHINESE_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_PROJECT_DETAIL_CN}
    Scroll Page Down
    Scroll Up
    Sleep    1s
    Close Browser


    
