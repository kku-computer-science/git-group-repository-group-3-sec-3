
*** Settings ***
Documentation    Test suite for verifying language switching functionality on the report page
Library    SeleniumLibrary
Library    String

*** Variables ***
${BROWSER}    chrome
${URL}    http://127.0.0.1:8000/researchproject  
@{EXPECTED_THAI_TEXTS}    โครงการบริการวิชาการ    หัวหน้าห้องปฏิบัติการ    รายละเอียดเพิ่มเติม
@{EXPECTED_ENGLISH_TEXTS}    Research Group    Laboratory Supervisor    More details

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
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}    and    @{EXPECTED_data_th}

    # เปลี่ยนกลับเป็นอังกฤษ
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    3s
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}    and    @{EXPECTED_data_en}