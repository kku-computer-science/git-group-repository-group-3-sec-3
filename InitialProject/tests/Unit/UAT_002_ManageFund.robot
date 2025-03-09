*** Settings ***
Library          SeleniumLibrary
Test Teardown    Close Browser

*** Variables ***
${BROWSER}         chrome
${LOGIN_URL}       https://cssoften0368.cpkkuhost.com/login

# Login Page Elements
${LOGIN_PAGE_HEADER}    xpath=//h1[contains(text(), 'Account Login') or contains(text(), 'เข้าสู่ระบบบัญชี') or contains(text(), '账户登录')]
${USERNAME_FIELD}       xpath=//input[@id='username']
${PASSWORD_FIELD}       xpath=//input[@id='password']
${LOGIN_BUTTON}         xpath=//button[contains(text(), 'Account Login') or contains(text(), 'เข้าสู่ระบบบัญชี') or contains(text(), '账户登录')]

# Language Buttons
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

# Dashboard Elements
${DASHBOARD_HEADER}    xpath=//h1[contains(text(), 'Research Information Management System') or contains(text(), 'ระบบจัดการข้อมูลการวิจัย') or contains(text(), '研究信息管理系统')]
${LOGOUT_BUTTON}       xpath=//a[contains(text(), 'Logout') or contains(text(), 'ออกจากระบบ') or contains(text(), '注销')]

# Manage Fund Elements

${MANAGE_FUND_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Manage Fund') or contains(text(),'จัดการทุนวิจัย') or contains(text(),'基金管理'))]]

# ปุ่ม Add
${ADD_FUND_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/funds/create')]

@{EXPECTED_FUND_PAGE_TH}    
...    จัดการทุนวิจัย
...    เพิ่ม    
...    ลำดับที่    
...    ชื่อกองทุน    
...    ประเภทกองทุน    
...    ระดับกองทุน
@{EXPECTED_FUND_PAGE_EN}    
...    Manage Fund
...    Add    
...    No        
...    Fund Name	    
...    Fund Type    
...    Fund Level
@{EXPECTED_FUND_PAGE_CN}    
...    管理资金
...    资金    
...    序号	    
...    基金名称    
...    基金类型    
...    基金级别

@{EXPECTED_ADDFUND_PAGE_TH}    
...    เพิ่มทุนวิจัย
...    กรอกข้อมูลทุนวิจัย    
...    ประเภททุน    
...    กรุณาระบุประเภททุน    
...    ระดับทุน    
...    กรุณาระบุระดับทุน    
...    ชื่อทุน    
...    หน่วยงาน/โครงการที่สนับสนุนงานวิจัย    
...    ส่ง    ยกเลิก
@{EXPECTED_ADDFUND_PAGE_EN}    
...    Add Fund
...    Fill in the research fund details    
...    Type Funds        
...    Please specify the type of capital    
...    Level Funds    
...    Please specify the level of fund    
...    Funds Name    
...    Supporting Agencies / Research Projects    
...    Submit    
...    Cancel
@{EXPECTED_ADDFUND_PAGE_CN}    
...    添加资金
...    填写研究资金详情    
...    资金类型    
...    请选择资金类型    
...    资金级别    
...    请选择资金级别    
...    资金名称    
...    支持机构 / 研究项目    
...    提交    
...    取消

*** Keywords ***
Open Browser To Login Page
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Maximize Browser Window
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=10s

Login As Admin
    [Arguments]    ${username}    ${password}
    Input Text    ${USERNAME_FIELD}    ${username}
    Input Text    ${PASSWORD_FIELD}    ${password}
    Click Element    ${LOGIN_BUTTON}
    Wait Until Element Is Visible    ${DASHBOARD_HEADER}    timeout=10s

Change Language
    [Arguments]    ${language_button}
    Click Element    ${language_button}
    Sleep    3s

Go To Manage Fund
    Wait Until Element Is Visible    ${MANAGE_FUND_MENU}    timeout=10s
    Click Element    ${MANAGE_FUND_MENU}

Go To Add Fund
    Scroll Element Into View    ${ADD_FUND_BUTTON}
    Wait Until Element Is Visible    ${ADD_FUND_BUTTON}    timeout=15s
    Log    ${ADD_FUND_BUTTON}
    Log Source
    Click Element    ${ADD_FUND_BUTTON}
    Sleep    2s



Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_FUND_BUTTON}

Logout
    Click Element    ${LOGOUT_BUTTON}
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=10s

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Test Admin Manage Fund In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Go To Manage Fund
    Change Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_PAGE_EN}
    Go To Add Fund
    Verify Page Contains Multiple Texts    @{EXPECTED_ADDFUND_PAGE_EN}
    
    Logout

Test Admin Manage Fund In Thai
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Go To Manage Fund
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_PAGE_TH}
    Go To Add Fund
    Verify Page Contains Multiple Texts    @{EXPECTED_ADDFUND_PAGE_TH}
    Logout

Test Admin Manage Fund In Chinese
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Go To Manage Fund
    Change Language    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_PAGE_CN}
    Go To Add Fund
    Verify Page Contains Multiple Texts    @{EXPECTED_ADDFUND_PAGE_CN}
    
    Logout
