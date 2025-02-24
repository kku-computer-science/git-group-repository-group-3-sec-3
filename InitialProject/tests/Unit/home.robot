*** Settings ***
Documentation    Test suite for verifying language switching functionality on the report page
Library    SeleniumLibrary
Library    String

*** Variables ***
${BROWSER}    chrome
${URL}    http://127.0.0.1:8000/
@{EXPECTED_THAI_TEXTS}    อ้างอิง    แหล่งอ้างอิง    ปิด    สรุป    รายงานจำนวนบทความทั้งหมด (สะสมตลอด 5 ปี)    ปี    จำนวนบทความ    ก่อนปี
@{EXPECTED_ENGLISH_TEXTS}    ref    Reference    Close    SUMMARY    Report the total number of articles ( 5 years : cumulative)    year    Number    Before 
@{EXPECTED_Year_th}    2567
@{EXPECTED_Year_en}    2024

${LANG_TO_THAI}    xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]

*** Keywords ***
Open Browser To Report Page
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Verify Page Contains Texts    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${word}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${word}
    END

*** Test Cases ***
Switch Language And Verify Summary In HTML
    Open Browser To Report Page
    Sleep    3s

    # ถ้าหน้าปัจจุบันเป็นภาษาไทยอยู่แล้ว ไม่ต้องกด
    ${is_thai_visible}=    Run Keyword And Return Status    Element Should Be Visible    ${LANG_TO_THAI}
    Run Keyword If    ${is_thai_visible}    Wait And Click    ${LANG_TO_THAI}

    Sleep    3s
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}    and    @{EXPECTED_Year_th}

    # เปลี่ยนกลับเป็นอังกฤษ
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    3s
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}    and    @{EXPECTED_Year_en}