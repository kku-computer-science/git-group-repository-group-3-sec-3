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

${Manage_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Manage Publications') or contains(text(),'จัดการผลงานตีพิมพ์') or contains(text(),'管理出版物'))]]
${WORKS_MENU}    xpath=//div[@id='ManagePublications']//a[contains(@class,'nav-link') and contains(text(),'Other academic works')]


# ปุ่ม 
${ADD_WORKS_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/patents/create')]
${VIEW_WORKS_BUTTON}    xpath=//td//a[contains(@title, 'View') or .//i[contains(@class, 'mdi-eye')]]
${EDIT_WORKS_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_WORKS_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(@class, 'btn-light') and contains(@class, 'mt-5') and normalize-space(text())='ยกเลิก']




@{EXPECTED_WORKS_PAGE_EN}    
...    No.    
...    Title    Type    Registration Date    Registration Number    Author    	Action	    
...    2009    Pusadee Seresangtakul   
...    Copyright    2015    326096    Pusadee Seresangtakul   
...    ADD           
...    Action  
  
@{EXPECTED_ACADEMICWORK_PAGE_CN}    
...    专利和版权
...    序号    
...    标题    
...    类型   
...    注册日期  
...    注册号        
...    作者    	操作    书籍   
...    

@{EXPECTED_ADD_WORKS_PAGE_EN}    
...    Add Other Academic Works
...    Enter details for other academic works (Patents, Utility Models, Copyright)  
...    Title (Patent, Utility Model, Copyright)      
...    Type  
...    Date of Registration
...    Registration Number 
...    Internal Faculty 
...    External Faculty                  
...    Submit    
...    Cancel      
   
@{EXPECTED_ADD_WORKS_PAGE_CN}    
...    添加其他学术作品
...    填写其他学术作品详情（专利、实用新型、版权）  
...    标题（专利、实用新型、版权）      
...    类型   
...    注册日期
...    注册号  
...    内部教师 
...    外部教师   
...    提交    
...    取消    



@{EXPECTED_VIEW_WORKS_PAGE_EN}    
...    Journal Details    Registration Number    Internal Faculty    Co-Author    Pusadee Seresangtakul    Number :
...    Journal Information
...    Paper Title     
...    Type   
...    Registration Date             
        
@{EXPECTED_VIEW_WORKS_PAGE_CN}    
...    期刊详情    注册号    内部教师    合著者    编号 :
...    期刊信息 
...    论文标题  
...    类型
...    注册日期 
    
   
    
@{EXPECTED_EDIT_WORKS_PAGE_EN}    
...    Edit Patent Detail    Registration Number    Internal Faculty
...    Enter Patent Information    External Faculty 
...    Title      
...    Type 
...    Registration Date               
...    Submit    
...    Cancel         
@{EXPECTED_EDIT_WORKS_PAGE_CN}    
...    编辑专利详情    填写专利信息
...    标题    类型    注册日期
...    注册号    内部教师    外部教师    
...    提交    
...    取消   

@{EXPECTED_DELETE_WORKS_TH}    คุณแน่ใจหรือไม่?    คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!    ยกเลิก    ตกลง
@{EXPECTED_DELETE_WORKS_EN}    Are you sure?    You will not be able to recover this record!    Cancel    OK
 

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

Go To ManagePub
    Wait Until Element Is Visible    ${Manage_MENU}    timeout=10s
    Click Element    ${Manage_MENU}

Go To WORKS
    Wait Until Element Is Visible    ${WORKS_MENU}    timeout=10s
    Click Element    ${WORKS_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add WORKS
    Scroll Element Into View    ${ADD_WORKS_BUTTON}
    Wait Until Element Is Visible    ${ADD_WORKS_BUTTON}    timeout=15s
    Log    ${ADD_WORKS_BUTTON}
    Log Source
    Click Element    ${ADD_WORKS_BUTTON}
    Sleep    2s

Go To VIEW WORKS
    Scroll Element Into View    ${VIEW_WORKS_BUTTON}
    Wait Until Element Is Visible    ${VIEW_WORKS_BUTTON}    timeout=30s
    Log    ${VIEW_WORKS_BUTTON}
    Log Source
    Click Element    ${VIEW_WORKS_BUTTON}
    Sleep    2s

Go To EDIT WORKS
    Scroll Element Into View    ${EDIT_WORKS_BUTTON}
    Wait Until Element Is Visible    ${EDIT_WORKS_BUTTON}    timeout=15s
    Log    ${EDIT_WORKS_BUTTON}
    Log Source
    Click Element    ${EDIT_WORKS_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_WORKS_BUTTON}
    Wait Until Element Is Visible    ${DELETE_WORKS_BUTTON}    timeout=10s
    Click Element    ${DELETE_WORKS_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_WORKS_BUTTON}

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
Test Teacher ACAWORKS In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Change Language    ${LANG_TO_ENGLISH}
    Go To ManagePub
    Go To WORKS
    Verify Page Contains Multiple Texts    @{EXPECTED_WORKS_PAGE_EN}
    Scroll Page Down
    Sleep    2s
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    Sleep    1s
    Logout

Test Teacher Add ACAWORKS In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Change Language    ${LANG_TO_ENGLISH}
    Go To ManagePub
    Go To WORKS
    Go To Add WORKS
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_WORKS_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher View ACAWORKS Detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Change Language    ${LANG_TO_ENGLISH}
    Go To ManagePub
    Go To WORKS
    Go To VIEW WORKS
    Verify Page Contains Multiple Texts    @{EXPECTED_VIEW_WORKS_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout



Test Teacher Edit ACAWORKS Detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Change Language    ${LANG_TO_ENGLISH}
    Go To ManagePub
    Go To WORKS
    Go To EDIT WORKS
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_WORKS_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    4s
    Change Language    ${LANG_TO_THAI}
    Scroll Page Down
    Sleep    2s
    Back button

    Wait Until Page Contains    สิทธิบัตรและลิขสิทธิ์    timeout=10s
    Sleep    2s

    Delete button

    Wait Until Element Is Visible   xpath=//div[contains(@class,'swal-modal')]//div[contains(@class,'swal-title')]    timeout=10s
    Sleep   2s
    Verify Page Contains Multiple Texts    @{EXPECTED_DELETE_WORKS_TH}
    
