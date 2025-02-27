*** Settings ***
Documentation    Test suite for verifying language switching and dynamic data on the research project page.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}            chrome
${URL}                https://cssoften0368.cpkkuhost.com/researchproject
${WAIT_TIME}          3s

# ✅ Expected Data (Dynamic จากฐานข้อมูล)
${EXPECTED_PROJECT_YEAR_EN}    2020
${EXPECTED_PROJECT_YEAR_TH}    2563

@{EXPECTED_DURATION_TH}    1 สิงหาคม 2563 ถึง 19 สิงหาคม 2563
@{EXPECTED_DURATION_EN}    1 August 2020 to 19 August 2020
@{EXPECTED_DURATION_CN}    2020年8月1日 至 2020年8月19日

@{EXPECTED_RESEARCHTYPE_TH}    ทุนภายนอก
@{EXPECTED_RESEARCHTYPE_EN}    External funding
@{EXPECTED_RESEARCHTYPE_CN}    外部资本

@{EXPECTED_FUNDAGENCY_TH}    สำนักงานปลัดกระทรวงอุดมศึกษา วิทยาศาสตร์ วิจัยและนวัตกรรม
@{EXPECTED_FUNDAGENCY_EN}    Office of the Permanent Secretary (OPS) , MHESI Thailand
@{EXPECTED_FUNDAGENCY_CN}    Office of the Permanent Secretary (OPS) , MHESI Thailand

@{EXPECTED_RESPONSIBLE_TH}    สาขาวิชาวิทยาการคอมพิวเตอร์
@{EXPECTED_RESPONSIBLE_EN}    Computer Science
@{EXPECTED_RESPONSIBLE_CN}    计算机科学系

@{EXPECTED_PSUPERVISOR_TH}    
...    อ. ธนพล ตั้งชูพงศ์    
...    อ.ดร. วรัญญา วรรณศรี
@{EXPECTED_PSUPERVISOR_EN}    
...    Thanaphon Tangchoopong    
...    Warunya Wunnasri, Ph.D.
@{EXPECTED_PSUPERVISOR_CN}
...    Thanaphon Tangchoopong    
...    Warunya Wunnasri, Ph.D.

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

Verify Page Contains Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${word}    IN    @{expected_texts}
        Should Match Regexp    ${html_source}    .*${word}.*
    END

Verify First Row Data
    [Arguments]    ${expected_year}    @{expected_texts}
    ${year_text}=    Get Text    xpath=//table[@id='example1']/tbody/tr[1]/td[2]
    Should Be Equal    ${year_text}    ${expected_year}
    FOR    ${text}    IN    @{expected_texts}
        Page Should Contain    ${text}
    END

*** Test Cases ***

#TC1: ทดสอบการเปลี่ยนภาษา
Switch Language And Verify Static Texts
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}

#TC2: ทดสอบระยะเวลาโครงการ
Verify Project Duration Changes
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}

    # ตรวจสอบภาษาไทย
    Wait Until Element Is Visible    xpath=//td[contains(normalize-space(.), '1 สิงหาคม') and contains(normalize-space(.), 'ถึง 19 สิงหาคม')]    timeout=10s
    ${project_duration_th}=    Get Text    xpath=//td[contains(normalize-space(.), '1 สิงหาคม') and contains(normalize-space(.), 'ถึง 19 สิงหาคม')]
    ${project_duration_th}=    Convert To String    ${project_duration_th}
    Log    Found Duration (TH): ${project_duration_th}
    Should Contain    ${project_duration_th}    1 สิงหาคม 2563 ถึง 19 สิงหาคม 2563

    # เปลี่ยนเป็นภาษาอังกฤษ
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Wait Until Element Is Visible    xpath=//td[contains(normalize-space(.), '1 August') and contains(normalize-space(.), 'to 19 August')]    timeout=10s
    ${project_duration_en}=    Get Text    xpath=//td[contains(normalize-space(.), '1 August') and contains(normalize-space(.), 'to 19 August')]
    ${project_duration_en}=    Convert To String    ${project_duration_en}
    Log    Found Duration (EN): ${project_duration_en}
    Should Contain    ${project_duration_en}    1 August 2020 to 19 August 2020

    # เปลี่ยนเป็นภาษาจีน
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Wait Until Element Is Visible    xpath=//td[contains(normalize-space(.), '2020年8月1日') and contains(normalize-space(.), '至 2020年8月19日')]    timeout=10s
    ${project_duration_cn}=    Get Text    xpath=//td[contains(normalize-space(.), '2020年8月1日') and contains(normalize-space(.), '至 2020年8月19日')]
    ${project_duration_cn}=    Convert To String    ${project_duration_cn}
    Log    Found Duration (CN): ${project_duration_cn}
    Should Contain    ${project_duration_cn}    2020年8月1日 至 2020年8月19日

# ✅ TC3: ทดสอบประเภทของทุนวิจัย
Verify Research Type Changes
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_TH}    @{EXPECTED_RESEARCHTYPE_TH}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_RESEARCHTYPE_EN}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_RESEARCHTYPE_CN}


#TC5: ทดสอบหน่วยงานที่รับผิดชอบ
Verify Responsible Agency Changes
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_TH}    @{EXPECTED_RESPONSIBLE_TH}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_RESPONSIBLE_EN}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_RESPONSIBLE_CN}

#TC6: ทดสอบผู้รับผิดชอบโครงการเปลี่ยนตามภาษา
Verify Project Supervisor Changes
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_TH}    @{EXPECTED_PSUPERVISOR_TH}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_PSUPERVISOR_EN}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_PSUPERVISOR_EN}  # ภาษาอังกฤษในภาษาจีน

# ✅ TC4: ทดสอบหน่วยงานที่สนับสนุนทุน
Verify Funding Agency Changes
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_TH}    @{EXPECTED_FUNDAGENCY_TH}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify First Row Data    ${EXPECTED_PROJECT_YEAR_EN}    @{EXPECTED_FUNDAGENCY_EN}
