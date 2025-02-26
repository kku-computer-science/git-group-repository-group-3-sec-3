*** Settings ***
Library          SeleniumLibrary
Test Teardown    Close Browser

*** Variables ***
${BROWSER}         chrome
${LOGIN_URL}       http://127.0.0.1:8000/login

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

# Expected Texts สำหรับหน้า Dashboard (แต่ละภาษา)
@{EXPECTED_DASHBOARD_TH}    
...    ระบบจัดการข้อมูลการวิจัย
...    ยินดีต้อนรับ
...    สวัสดี

@{EXPECTED_DASHBOARD_EN}    
...    Research Information Management System
...    Hello
...    Welcome

@{EXPECTED_DASHBOARD_CN}    
...    研究信息管理系统
...    欢迎
...    你好

@{EXPECTED_LOGIN_EN}    
...    Account Login
...    Username
...    Password
...    Remember Me
...    Use KKU-Mail to log in.
...    For first-time student login, use student ID.
...    If you forgot your password, please contact the administrator.

@{EXPECTED_LOGIN_CN}    
...    账户登录
...    用户名
...    密码
...    记住我
...    如果忘记密码，请联系管理员。
...    使用 KKU-Mail 登录。
...    首次登录的学生，请使用学号。
...    登录失败：您的用户 ID 或密码不正确

# Expected Texts สำหรับหน้า Login (แต่ละภาษา)
@{EXPECTED_LOGIN_TH}    
...    เข้าสู่ระบบบัญชี
...    ชื่อผู้ใช้
...    รหัสผ่าน
...    จดจำฉัน
...    หากลืมรหัสผ่าน ให้ติดต่อผู้ดูแลระบบ
...    สำหรับ Username ใช้ KKU-Mail ในการเข้าสู่ระบบ
...    สำหรับนักศึกษาที่เข้าระบบเป็นครั้งแรกให้เข้าสู่ระบบด้วยรหัสนักศึกษา
...    เข้าสู่ระบบล้มเหลว: รหัสผู้ใช้หรือรหัสผ่านของคุณไม่ถูกต้อง

*** Keywords ***
Open Browser To Login Page
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Maximize Browser Window
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=15s

Change Language To English
    Click Element    ${LANG_TO_ENGLISH}
    Sleep    3s
    Verify Page Contains Multiple Texts    @{EXPECTED_LOGIN_EN}

Login With Credentials
    [Arguments]    ${username}    ${password}
    Input Text    ${USERNAME_FIELD}    ${username}
    Input Text    ${PASSWORD_FIELD}    ${password}
    Click Element    ${LOGIN_BUTTON}
    Wait Until Element Is Visible    ${DASHBOARD_HEADER}    timeout=15s

Verify Dashboard Page
    Verify Page Contains Multiple Texts    @{EXPECTED_DASHBOARD_EN}

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Test Single Flow: Login (English) -> Dashboard -> Logout
    Open Browser To Login Page
    Change Language To English
    Login With Credentials    admin@gmail.com    12345678
    Verify Dashboard Page
    
