*** Settings ***
Library  SeleniumLibrary

*** Variables ***
${BROWSER}    Chrome
${URL}        http://localhost:8000

*** Test Cases ***
User Can Switch To English
    Open Browser    ${URL}    ${BROWSER}
    Click Button    id=language-switch
    Click Element    xpath=//button[text()='English']
    Wait Until Page Contains    Welcome
    Close Browser

User Can Switch To Thai
    Open Browser    ${URL}    ${BROWSER}
    Click Button    id=language-switch
    Click Element    xpath=//button[text()='ไทย']
    Wait Until Page Contains    ยินดีต้อนรับ
    Close Browser

Language Persists After Refresh
    Open Browser    ${URL}    ${BROWSER}
    Click Button    id=language-switch
    Click Element    xpath=//button[text()='English']
    Wait Until Page Contains    Welcome
    Reload Page
    Wait Until Page Contains    Welcome
    Close Browser

UI Updates Correctly After Language Change
    Open Browser    ${URL}    ${BROWSER}
    Click Button    id=language-switch
    Click Element    xpath=//button[text()='ไทย']
    Wait Until Page Contains    ยินดีต้อนรับ
    Click Button    id=language-switch
    Click Element    xpath=//button[text()='English']
    Wait Until Page Contains    Welcome
    Close Browser
