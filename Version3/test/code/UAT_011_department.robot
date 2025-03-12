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

${DEPARTMENT_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Department') or contains(text(),'แผนก') or contains(text(),'权限'))]]

# ปุ่ม 
${ADD_DEPARTMENT_BUTTON}    xpath=//div[contains(@class,'card-header')]//a[contains(@href, '/departments/create')]
${VIEW_DEPARTMENT_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_DEPARTMENT_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]



@{EXPECTED_DEPARTMENT_PAGE_EN}    
...    Department   
...    Name    Department of Computer Science    
...    New Department          
...    Action


@{EXPECTED_DEPARTMENT_PAGE_TH}    
...    แผนก    แผนกใหม่    ชื่อ    สาขาวิชาวิทยาการคอมพิวเตอร์     
...    การกระทำ    
@{EXPECTED_DEPARTMENT_PAGE_CN}    
...    部门
...    名字  
...    操作	           


@{EXPECTED_ADD_DEPARTMENT_PAGE_EN}    
...    Create Department
...    Department Name (Thai):    Department Name (English): 
...    Department                     
...    Submit            
@{EXPECTED_ADD_DEPARTMENT_PAGE_CN}    
...    创建角色    名字    允许:
...    提交        

@{EXPECTED_Detail_EN}    
...    Department Name (Thai):    สาขาวิชาวิทยาการคอมพิวเตอร์    
...    Department Name (English):    Department of Computer Science  
...    Back
   

@{EXPECTED_Detail_CN}    
...    部门名称（泰语）:    部门名称（英语）:  
...    返回

@{EXPECTED_EDIT_EN}    
...    Department Name (Thai):       
...    Department Name (English):                   
...    Submit    

@{EXPECTED_DELETE_EN}    
...    Are you sure?
...    If you delete this, it will be gone forever.   
...    Cancel      
...    OK   

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

Scroll Page Down
    ${total_height}=    Execute JavaScript    return document.body.scrollHeight
    ${scroll_position}=    Set Variable    0

    WHILE    ${scroll_position} < ${total_height}
        ${scroll_position}=    Evaluate    ${scroll_position} + 300
        Execute JavaScript    window.scrollTo(0, ${scroll_position})
        Sleep    0.5s
    END

Scroll Up
    Execute JavaScript    window.scrollTo(0, 0)

Change Language
    [Arguments]    ${language_button}
    Click Element    ${language_button}
    Sleep    3s

Go To Department
    Wait Until Element Is Visible    ${DEPARTMENT_MENU}    timeout=10s
    Click Element    ${DEPARTMENT_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Department
    Scroll Element Into View    ${ADD_DEPARTMENT_BUTTON}
    Wait Until Element Is Visible    ${ADD_DEPARTMENT_BUTTON}    timeout=15s
    Log    ${ADD_DEPARTMENT_BUTTON}
    Log Source
    Click Element    ${ADD_DEPARTMENT_BUTTON}
    Sleep    2s

Go To VIEW Department
    Scroll Element Into View    ${VIEW_DEPARTMENT_BUTTON}
    Wait Until Element Is Visible    ${VIEW_DEPARTMENT_BUTTON}    timeout=15s
    Log    ${VIEW_DEPARTMENT_BUTTON}
    Log Source
    Click Element    ${VIEW_DEPARTMENT_BUTTON}
    Sleep    2s

Go To EDIT Department
    Scroll Element Into View    ${EDIT_DEPARTMENT_BUTTON}
    Wait Until Element Is Visible    ${EDIT_DEPARTMENT_BUTTON}    timeout=15s
    Log    ${EDIT_DEPARTMENT_BUTTON}
    Log Source
    Click Element    ${EDIT_DEPARTMENT_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_DEPARTMENT_BUTTON}

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
Test Admin Department In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    
    Scroll Page Down
    Go To Department
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_DEPARTMENT_PAGE_EN}
    Change Language    ${LANG_TO_THAI}
    Sleep    2s
    Change Language    ${LANG_TO_CHINESE}
    Sleep    2s

    Logout

Test Admin Add Department In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Scroll Page Down
    Go To Department
    Go To Add Department
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_DEPARTMENT_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    Sleep    1s
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_CHINESE}
    Sleep    1s

    Logout

 Test Admin View Department detail In English
     Open Browser To Login Page
     Login As Admin    admin@gmail.com    12345678
     
     Scroll Page Down
     Go To Department
     Go To VIEW Department
     Change Language    ${LANG_TO_ENGLISH}
     Sleep    2s
     Verify Page Contains Multiple Texts    @{EXPECTED_Detail_EN}
    
    Logout

    
