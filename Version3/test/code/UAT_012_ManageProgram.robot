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

${PROGRAM_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'PROGRAM') or contains(text(),'แผนก') or contains(text(),'权限'))]]

# ปุ่ม 
${ADD_PROGRAM_BUTTON}    xpath=//div[contains(@class,'card-header')]//a[contains(@href, '/PROGRAMs/create')]
${VIEW_PROGRAM_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_PROGRAM_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]



@{EXPECTED_PROGRAM_PAGE_EN}    
...    Curriculum    Add    ID    Name (Thai)    	Degree   
...    Bachelor of Science    Master of Science    Doctor of Philosophy         
...    Action


@{EXPECTED_PROGRAM_PAGE_TH}    
...    หลักสูตร    ชื่อ (ภาษาไทย)    ปริญญา    ไอดี    หลักสูตรวิทยาศาสตรบัณฑิต (วท.บ.)    หลักสูตรวิทยาศาสตรมหาบัณฑิต (วท.ม.)    หลักสูตรปรัชญาดุษฎีบัณฑิต (ปร.ด.)    
...    การกระทำ    เพิ่ม    
@{EXPECTED_PROGRAM_PAGE_CN}    
...    部门
...    名字  
...    操作	           


@{EXPECTED_ADD_PROGRAM_PAGE_EN}    
...    Create PROGRAM
...    PROGRAM Name (Thai):    PROGRAM Name (English): 
...    PROGRAM                     
...    Submit            
@{EXPECTED_ADD_PROGRAM_PAGE_CN}    
...    创建角色    名字    允许:
...    提交        

@{EXPECTED_Detail_EN}    
...    PROGRAM Name (Thai):    สาขาวิชาวิทยาการคอมพิวเตอร์    
...    PROGRAM Name (English):    PROGRAM of Computer Science  
...    Back
   

@{EXPECTED_Detail_CN}    
...    部门名称（泰语）:    部门名称（英语）:  
...    返回

@{EXPECTED_EDIT_EN}    
...    PROGRAM Name (Thai):       
...    PROGRAM Name (English):                   
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

Go To PROGRAM
    Wait Until Element Is Visible    ${PROGRAM_MENU}    timeout=10s
    Click Element    ${PROGRAM_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add PROGRAM
    Scroll Element Into View    ${ADD_PROGRAM_BUTTON}
    Wait Until Element Is Visible    ${ADD_PROGRAM_BUTTON}    timeout=15s
    Log    ${ADD_PROGRAM_BUTTON}
    Log Source
    Click Element    ${ADD_PROGRAM_BUTTON}
    Sleep    2s

Go To VIEW PROGRAM
    Scroll Element Into View    ${VIEW_PROGRAM_BUTTON}
    Wait Until Element Is Visible    ${VIEW_PROGRAM_BUTTON}    timeout=15s
    Log    ${VIEW_PROGRAM_BUTTON}
    Log Source
    Click Element    ${VIEW_PROGRAM_BUTTON}
    Sleep    2s

Go To EDIT PROGRAM
    Scroll Element Into View    ${EDIT_PROGRAM_BUTTON}
    Wait Until Element Is Visible    ${EDIT_PROGRAM_BUTTON}    timeout=15s
    Log    ${EDIT_PROGRAM_BUTTON}
    Log Source
    Click Element    ${EDIT_PROGRAM_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_PROGRAM_BUTTON}

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
Test Admin Program In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Change Language    ${LANG_TO_ENGLISH}
    Scroll Page Down
    Go To PROGRAM
    Verify Page Contains Multiple Texts    @{EXPECTED_PROGRAM_PAGE_EN}

    Change Language    ${LANG_TO_THAI}
    Sleep    2s
    Logout

Test Admin Add Program In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Change Language    ${LANG_TO_ENGLISH}
    Scroll Page Down
    Go To PROGRAM
    Go To Add PROGRAM
    # Verify Page Contains Multiple Texts    @{EXPECTED_ADD_PROGRAM_PAGE_EN}
    # Scroll Page Down
    # Scroll Up
    # Sleep    2s
    # Change Language    ${LANG_TO_THAI}
    
    # Logout

 Test Admin View Program detail In English
     Open Browser To Login Page
     Login As Admin    admin@gmail.com    12345678
     Change Language    ${LANG_TO_ENGLISH}
     Scroll Page Down
     Go To PROGRAM
     Go To VIEW PROGRAM
     Sleep    2s
    #  Verify Page Contains Multiple Texts    @{EXPECTED_Detail_EN}
    
    # Logout

    
