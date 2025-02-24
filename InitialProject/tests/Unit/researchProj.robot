*** Settings ***
Documentation    Test suite for verifying language switching on the research project page including dynamic data.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}            chrome
${URL}                http://127.0.0.1:8000/researchproject
${WAIT_TIME}          3s

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

${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]

# These expected values assume your test database has a known project record.
# For example, if the project_year stored is 2020 in English,
# then in Thai it should display as 2563 (2020 + 543).
${EXPECTED_PROJECT_YEAR_EN}    2020
${EXPECTED_PROJECT_YEAR_TH}    2563

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
        Should Contain    ${html_source}    ${word}
    END

Verify Table Contains Data
    [Documentation]    Verify that the research project table contains dynamic data (at least one row).
    Page Should Contain Element    xpath=//table[@id='example1']/tbody/tr

Verify First Row Year Is Correct
    [Arguments]    ${expected_year}
    ${year_text}=    Get Text    xpath=//table[@id='example1']/tbody/tr[1]/td[2]
    Log    Found Year: ${year_text}
    Should Be Equal    ${year_text}    ${expected_year}

*** Test Cases ***
Switch Language And Verify Data On Research Project Page
    [Documentation]    Verify that the research project page displays correct static texts and dynamic data.
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    
    # Verify default language (Thai) static texts and that the table has data.
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Verify Table Contains Data
    # Verify that the first row displays the Thai version of the project year.
    Verify First Row Year Is Correct    ${EXPECTED_PROJECT_YEAR_TH}

    # Switch to English and verify static texts, table data, and dynamic content.
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
    Verify Table Contains Data
    # Verify that the first row displays the English project year.
    Verify First Row Year Is Correct    ${EXPECTED_PROJECT_YEAR_EN}
