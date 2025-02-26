*** Settings ***
Library          SeleniumLibrary
Test Teardown    Close Browser

*** Variables ***
${BROWSER}         chrome
${HOME_URL}        http://127.0.0.1:8000/

# ปุ่มเข้าสู่ระบบที่หน้า Home
${HOME_TO_LOGIN_BUTTON}    xpath=//a[contains(text(), 'เข้าสู่ระบบ')]

# Login Page Elements
${LOGIN_PAGE_HEADER}    xpath=//h1[contains(text(), 'เข้าสู่ระบบบัญชี') or contains(text(), 'Account Login') or contains(text(), '账户登录')]
${USERNAME_FIELD}       xpath=//input[@id='username']
${PASSWORD_FIELD}       xpath=//input[@id='password']
${LOGIN_BUTTON}         xpath=//button[contains(text(), 'เข้าสู่ระบบบัญชี') or contains(text(), 'Account Login') or contains(text(), '账户登录')]

# ปุ่มเปลี่ยนภาษาในหน้า Login
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

# Dashboard Elements
${DASHBOARD_HEADER}    xpath=//h1[contains(text(), 'ระบบจัดการข้อมูลการวิจัย') or contains(text(), 'Research Information Management System') or contains(text(), '研究信息管理系统')]
${LOGOUT_BUTTON}       xpath=//a[contains(text(), 'Logout') or contains(text(), 'ออกจากระบบ') or contains(text(), '登出')]

# Expected Texts
@{EXPECTED_LOGIN_EN}    
...    Account Login    
...    Username    
...    Password    
...    Remember Me    
...    Use KKU-Mail to log in.    
...    For first-time student login, use student ID.    
...    Your user ID or password is incorrect     
...    If you forgot your password, please contact the administrator.

@{EXPECTED_DASHBOARD_EN}    
...    Research Information Management System    
...    Hello    
...    Welcome

*** Keywords ***
Open Browser To Home Page
    Open Browser    ${HOME_URL}    ${BROWSER}
    Maximize Browser Window

Navigate To Login Page
    Click Element    ${HOME_TO_LOGIN_BUTTON}
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=15s

Change Language To English
    Click Element    ${LANG_TO_ENGLISH}
    Sleep    3s
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=10s
    Verify Page Contains Multiple Texts    @{EXPECTED_LOGIN_EN}

Login With Credentials
    [Arguments]    ${username}    ${password}
    Input Text    ${USERNAME_FIELD}    ${username}
    Input Text    ${PASSWORD_FIELD}    ${password}
    Click Element    ${LOGIN_BUTTON}
    Wait Until Element Is Visible    ${DASHBOARD_HEADER}    timeout=15s

Verify Dashboard Page
    Verify Page Contains Multiple Texts    @{EXPECTED_DASHBOARD_EN}

Logout And Verify Login Page
    Click Element    ${LOGOUT_BUTTON}
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=15s

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Test Single Flow: Home -> Login (English) -> Dashboard -> Logout
    Open Browser To Home Page
    Navigate To Login Page
    Change Language To English
    Login With Credentials    admin@gmail.com    12345678
    Verify Dashboard Page
    Logout And Verify Login Page
