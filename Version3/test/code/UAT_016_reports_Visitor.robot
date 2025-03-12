*** Settings ***
Documentation    Test suite for verifying language switching functionality on the report page
Library    SeleniumLibrary
Library    String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${URL}        https://cssoften0368.cpkkuhost.com
${WAIT_TIME}  3s

${REPORT_MENU}    xpath=//nav//a[contains(@class, 'nav-link') and contains(text(), 'รายงาน')]

@{EXPECTED_THAI_TEXTS}    
...    สถิติจำนวนบทความทั้งหมด 5 ปี    
...    สถิติจำนวนบทความที่ได้รับการอ้างอิง    
...    แหล่งที่มา
@{EXPECTED_ENGLISH_TEXTS}    
...    Total number of articles statistics for 5 years    
...    Statistics on the number of articles cited    
...    Source
@{EXPECTED_CHINESE_TEXTS}    
...    5年文章总数统计  
...    引用文章数量统计    
...    来源
@{EXPECTED_Year_th}    2567
@{EXPECTED_Year_en}    2024

${LANG_TO_THAI}    xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}      xpath=//a[contains(text(), '中文')]

*** Keywords ***
Open Browser To Home Page
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Scroll Page Down Slightly
    Execute JavaScript    window.scrollBy(0, 800)
    Sleep    2s
Scroll Up
    Execute JavaScript    window.scrollTo(0, 0) 
    Sleep    2s
   

Verify Page Contains Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Thai To English
    [Documentation]    Starting from default Thai, switch to English and verify.
    Open Browser To Home Page
    Wait And Click    ${REPORT_MENU}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}

    Scroll Page Down Slightly
    Scroll Up
    
    
Thai To Chinese
        [Documentation]    Starting from default Thai, switch to English and verify.
    Open Browser To Home Page
    Wait And Click    ${REPORT_MENU}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}

    Scroll Page Down Slightly
    Scroll Up
    