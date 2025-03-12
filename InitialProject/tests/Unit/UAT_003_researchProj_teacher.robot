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

${Research_Project_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Research Project') or contains(text(),'โครงการวิจัย') or contains(text(),'研究项目'))]]

# ปุ่ม 
${ADD_REARCH_PROJECT_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/researchProjects/create')]
${VIEW_REARCH_PROJECT_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_REARCH_PROJECT_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_PROJECT_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]


@{EXPECTED_RPJ_PAGE_TH}    
...    โครงการวิจัย   
...    เพิ่ม    
...    ปี       
...    ชื่อโครงการ    
...    หัวหน้า    	พุธษดี   
...    สมาชิก    
...    การกระทำ


@{EXPECTED_RPJ_PAGE_EN}    
...    Research Project    
...    Research Information Management System
...    Add    
...    Year        
...    Project Name	    
...    Head    Pusadee    
...    Member    
...    Action    
@{EXPECTED_RPJ_PAGE_CN}    
...    研究项目
...    年份    
...    项目名称	    
...    负责人    
...    成员    


@{EXPECTED_ADD_RESEARCH_PROJECT_PAGE_EN}    
...    Add Research Project
...    Fill in the research project details   
...    Project Name      
...    Start Date   
...    End Date   
...    Select a scholarship   
...    Year of submission (A.D.)  
...    Budget   
...    Responsible agency   
...    Project details   
...    Status    
...    Project Manager    
...    Project Manager (Co-Internal)    
...    Project Manager (Co-External)          
...    Submit    
...    Cancel         
@{EXPECTED_ADDRPJ_PAGE_CN}    
...    添加研究项目
...    填写研究项目详情   
...    项目名称      
...    开始日期   
...    结束日期   
...    选择奖学金   
...    提交年份（公元）  
...    预算   
...    负责机构  
...    项目详情   
...    状态    
...    项目经理   
...    项目经理（内部协作）    
...    项目经理（外部协作）    
...    职位或头衔    
...    名字   
...    姓氏   

@{EXPECTED_Detail_EN}    
...    Research Projects Detail
...    Project Information   
...    Project Name      
...    Project Start Date   
...    Project End Date   
...    Research Fund Source  
...    Amount     
...    Project Details   
...    Project Status    
...    Closed    
...    Project Lead    
...    Asst. Prof. Dr. Pusadee Seresangtakul    
...    Project Members    
...    Back

@{EXPECTED_Detail_TH}    
...    รายละเอียดโครงการวิจัย
...    ข้อมูลโครงการวิจัย 
...    ชื่อโครงการ     
...    วันที่เริ่มโครงการ   
...    วันที่สิ้นสุดโครงการ  
...    แหล่งทุนวิจัย 
...    จำนวนเงิน    
...    รายละเอียดโครงการ   
...    สถานะโครงการ    ปิดโครงการ    
...    หัวหน้าโครงการ    
...    ผศ.ดร. พุธษดี ศิริแสงตระกูล    
...    สมาชิกโครงการ    
...    ย้อนกลับ

@{EXPECTED_EDIT_EN}    
...    Edit Research Project
...    Fill in the edited project details  
...    Project Name      
...    Project Start Date   
...    Project End Date   
...    Select Fund    
...    Year of submission (A.D.) 
...    Budget     
...    Project Details   
...    Responsible Department    
...    Status   
...    Closed    
...    Project Lead    
# ...    Asst. Prof. Dr. Pusadee Seresangtakul    
...    Project Lead (Co-Internal)     
...    Submit    
...    Back

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

Login As Teacher
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

Go To Research Project
    Wait Until Element Is Visible    ${Research_Project_MENU}    timeout=10s
    Click Element    ${Research_Project_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Research Project
    Scroll Element Into View    ${ADD_REARCH_PROJECT_BUTTON}
    Wait Until Element Is Visible    ${ADD_REARCH_PROJECT_BUTTON}    timeout=15s
    Log    ${ADD_REARCH_PROJECT_BUTTON}
    Log Source
    Click Element    ${ADD_REARCH_PROJECT_BUTTON}
    Sleep    2s

Go To VIEW Research Project
    Scroll Element Into View    ${VIEW_REARCH_PROJECT_BUTTON}
    Wait Until Element Is Visible    ${VIEW_REARCH_PROJECT_BUTTON}    timeout=15s
    Log    ${VIEW_REARCH_PROJECT_BUTTON}
    Log Source
    Click Element    ${VIEW_REARCH_PROJECT_BUTTON}
    Sleep    2s

Go To EDIT Research Project
    Scroll Element Into View    ${EDIT_REARCH_PROJECT_BUTTON}
    Wait Until Element Is Visible    ${EDIT_REARCH_PROJECT_BUTTON}    timeout=15s
    Log    ${EDIT_REARCH_PROJECT_BUTTON}
    Log Source
    Click Element    ${EDIT_REARCH_PROJECT_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_PROJECT_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_PROJECT_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_PROJECT_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_REARCH_PROJECT_BUTTON}

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
Test Teacher Research Project 
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To Research Project
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_RPJ_PAGE_EN}
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    Verify Page Contains Multiple Texts    @{EXPECTED_RPJ_PAGE_TH}
    Logout

Test Teacher Add Research Project In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To Research Project
    Go To Add Research Project
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_RESEARCH_PROJECT_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher View Research Project detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To Research Project
    Go To VIEW Research Project
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Detail_EN}
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher Edit And Delete Research Project detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To Research Project
    Go To EDIT Research Project
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_EN}
    
    Scroll Page Down
    Back button


    Wait Until Page Contains    Research Project    timeout=10s
    Sleep    2s

    Delete button

    Wait Until Element Is Visible   xpath=//div[contains(@class,'swal-modal')]//div[contains(@class,'swal-title')]    timeout=10s
    Sleep   2s
    Verify Page Contains Multiple Texts    @{EXPECTED_DELETE_EN}
