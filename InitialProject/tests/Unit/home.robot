*** Settings ***
Documentation    Test suite to verify translation on the home page.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${URL}        http://127.0.0.1:8000/
${WAIT_TIME}  3s

@{EXPECTED_THAI_TEXTS}    
...    แหล่งอ้างอิง    
...    ปิด    
...    สรุป    
...    รายงานจำนวนบทความทั้งหมด (สะสมตลอด 5 ปี)    
...    ปี    
...    จำนวนบทความ    
...    ก่อนปี

@{EXPECTED_ENGLISH_TEXTS}    
...    Reference    
...    Close    
...    SUMMARY    
...    Report the total number of articles (5 years : cumulative)    
...    year    
...    Number    
...    Before

@{EXPECTED_CHINESE_TEXTS}    
...    引用    
...    关闭    
...    总结    
...    报告总文章数 (5年累计)    
...    年    
...    文章数量    
...    一年前

${LANG_TO_THAI}         xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}      xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}      xpath=//a[contains(text(), '中文')]

*** Keywords ***
Open Browser To Home Page
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
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Thai To English
    [Documentation]    Starting from default Thai, switch to English and verify.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}

Thai To Chinese
    [Documentation]    Starting from default Thai, switch to Chinese and verify.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}

English To Chinese
    [Documentation]    Switch from Thai to English then to Chinese.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}

Chinese To Thai
    [Documentation]    Switch from Thai to Chinese then back to Thai.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}
    Wait And Click    ${LANG_TO_THAI}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}

English To Thai
    [Documentation]    Switch from Thai to English then back to Thai.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
    Wait And Click    ${LANG_TO_THAI}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}

Chinese To English
    [Documentation]    Switch from Thai to Chinese then to English.
    Open Browser To Home Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
