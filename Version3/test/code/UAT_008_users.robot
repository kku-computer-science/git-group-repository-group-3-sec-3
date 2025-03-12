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

${USERS_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Users') or contains(text(),'ผู้ใช้') or contains(text(),'研究小组'))]]

# ปุ่ม 
${ADD_USERS_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/users/create')]
${VIEW_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_USERS_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]



@{EXPECTED_USER_PAGE_EN}    
...    Users    
...    New User    
...    Name    Punyaphol Horata        
...    Department    Computer Science		    
...    Email    
...    Roles    teacher    headproject    
...    Action


@{EXPECTED_USER_PAGE_TH}    
...    ผู้ใช้    
...    ผู้ใช้ใหม่    
...    ชื่อ    Punyaphol Horata        
...    แผนก    Computer Science		    
...    อีเมล    
...    บทบาท    teacher    headproject    
...    การกระทำ    
@{EXPECTED_USER_PAGE_CN}    
...    用户
...    新用户   
...    名称	    
...    部门    计算机科学	    
...    电子邮件    角色	    
...    操作    


@{EXPECTED_ADD_USERS_PAGE_EN}    
...    Add User    Edit User Information
...    First Name (Thai)    Last Name (Thai)  
...    First Name (English)    Last Name (English)      
...    Email   
...    Password    Confirm Password   
...    Role    Department     Program                 
...    Submit    
...    Cancel         
@{EXPECTED_ADD_USER_PAGE_CN}    
...    添加用户    填写并修改用户信息
...    名字（泰语）    姓氏（泰语）   
...    名字（英语）    姓氏（英语）      
...    电子邮件    密码    确认密码   
...    角色    部门 *    项目 *  
...    提交   
...    返回     

@{EXPECTED_Detail_EN}    
...    Research Group Details
...    Research Group Information   
...    Research Group Name (Thai)      
...    Research Group Name (English)   
...    Research Group Description (Thai)   
...    Research Group Description (English)  
...    Research Group Details (Thai)     
...    Research Group Details (English)   
...    Research Group Members   
...    Asst. Prof. Dr. Pipat Reungsang    
...    Assoc. Prof. Dr. Chaiyapon Keeratikasikorn
...    ,    
...    Asst. Prof. Dr. Nagon Watanakij   
...    Back

@{EXPECTED_EDIT_EN}    
...    Edit User    Edit User Information
...    First Name (Thai)    Last Name (Thai)  
...    First Name (English)    Last Name (English)      
...    Email   
...    Password    Confirm Password   
...    Role    Department     Program     Status                
...    Submit    
...    Cancel 

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

Go To Research Group
    Wait Until Element Is Visible    ${USERS_MENU}    timeout=10s
    Click Element    ${USERS_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Research Group
    Scroll Element Into View    ${ADD_USERS_BUTTON}
    Wait Until Element Is Visible    ${ADD_USERS_BUTTON}    timeout=15s
    Log    ${ADD_USERS_BUTTON}
    Log Source
    Click Element    ${ADD_USERS_BUTTON}
    Sleep    2s

Go To VIEW Research Group
    Scroll Element Into View    ${VIEW_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${VIEW_REARCH_GROUP_BUTTON}    timeout=15s
    Log    ${VIEW_REARCH_GROUP_BUTTON}
    Log Source
    Click Element    ${VIEW_REARCH_GROUP_BUTTON}
    Sleep    2s

Go To EDIT Research Group
    Scroll Element Into View    ${EDIT_USERS_BUTTON}
    Wait Until Element Is Visible    ${EDIT_USERS_BUTTON}    timeout=15s
    Log    ${EDIT_USERS_BUTTON}
    Log Source
    Click Element    ${EDIT_USERS_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_USERS_BUTTON}

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
Test Admin Users In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Go To Research Group
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_USER_PAGE_EN}

    Change Language    ${LANG_TO_THAI}
    Sleep    2s
    Logout

Test Admin Add Users In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Go To Research Group
    Go To Add Research Group
    Change Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_USERS_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

# Test Admin View Research Group detail In English
#     Open Browser To Login Page
#     Login As Admin    admin@gmail.com    12345678
#     Change Language    ${LANG_TO_ENGLISH}
#     Go To Research Group
#     Go To VIEW Research Group
#     Sleep    2s
#     Verify Page Contains Multiple Texts    @{EXPECTED_Detail_EN}
    
#     Logout

Test Admin Edit Users In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Change Language    ${LANG_TO_ENGLISH}
    Go To Research Group
    Go To EDIT Research Group
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_EN}
    Change Language    ${LANG_TO_THAI}
    Scroll Page Down
    Scroll Up
    
