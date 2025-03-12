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
${Paper_MENU}    xpath=//div[@id='ManagePublications']//a[contains(@class,'nav-link') and contains(text(),'Published research')]


# ปุ่ม 
${ADD_Paper_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/papers/create')]
${VIEW_Paper_BUTTON}    xpath=//td//a[contains(@title, 'View') or .//i[contains(@class, 'mdi-eye')]]
${EDIT_Pub_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]


@{EXPECTED_Paper_PAGE_TH}    
...    งานวิจัยที่ตีพิมพ์    
...    ลำดับที่    
...    ชื่อเรื่อง    
...    ประเภท    	วารสาร    	บทความจากการประชุม    
...    	ปี    2564    
...    เพิ่ม      
...    	การกระทำ

@{EXPECTED_Paper_PAGE_EN}    
...    Published research    
...    No.    
...    Title    
...    Type    
...    Journal    
...    Add    
...    Year    2021    
...    Action    
@{EXPECTED_RPG_PAGE_CN}    
...    已发表研究
...    序号    
...    标题	    
...    类型    
...    年份    
...    期刊    
...    2022    
...    操作    


@{EXPECTED_ADD_Paper_PAGE_EN}    
...    Add Published Work
...    Fill in Research Details  
...    Research Publication Source      
...    Research Title    Nothing selected    ***Each keyword must be separated by a semicolon (;) followed by a space   
...    Abstract   
...    Keyword  
...    Document Type (Type) 
...    Document Subtype (Subtype)   
...    Publication  
...    Journal Name   
...    Year of Publication    
...    Volume   
...    Issue Number    
...    Citation    
...    Page    
...    Funding    
...    Author Name (Internal)    
...    Author Name (External)               
...    Submit    
...    Cancel         
@{EXPECTED_ADD_Paper_PAGE_CN}    
...    添加已发表作品    填写研究详情
...    研究出版来源    研究标题    摘要    关键词    *** 每个关键词后必须以分号 (;) 及空格分隔   
...    文档类型 (类型)      
...    文档子类型 (子类型)   
...    出版物  
...    期刊名称   
...    出版年份  
...    卷号   
...    期号 
...    引用 
...    页数   
...    资金   
...    作者姓名（内部）    作者姓名（外部）    提交    取消     

@{EXPECTED_Detail_EN}    
...    Journal Details
...    Journal Information    
...    Paper Title    
...    Abstract    Journal Type    Journal    Article    
...    Document Subtype (Subtype)    
...    Publication   
...    Author        
...    Corresponding Author    Pusadee Seresangtakul    
...    Journal Source Title    
...    Year of Publication    2021     
...    Volume    Issue Number    Page      
...    Back

@{EXPECTED_Detail_TH}    
...    รายละเอียดวารสาร
...    ข้อมูลวารสาร   
...    ชื่อบทความ   
...    บทคัดย่อ    คำค้น    #วารสาร    บทความ    
...    ประเภทวารสาร    
...    ประเภทย่อยของเอกสาร    
...    สื่อที่ตีพิมพ์   
...    ผู้จัดทำ        
...    ผู้ติดต่อ    พุธษดี ศิริแสงตระกูล   
...    ชื่องานวารสาร    
...    ปีที่ตีพิมพ์    2564    
...    เล่ม    ฉบับที่    ฉบับที่    
...    ย้อนกลับ

@{EXPECTED_EDIT_EN}    
...    Edit Published Work
...    Fill in Research Details  
...    Research Publication Source    Research Title    
...    Abstract    Journal Name    Keyword    Journal    Article    
...    Document Subtype (Subtype)    
...    Publication   
...    Author        
...    Corresponding Author    Citation       
...    Year of Publication    2021     
...    Volume    Issue Number    Page    
...    Author Name (Internal)    Author Name (External)  
...    Submit   Cancel

@{EXPECTED_EDIT_TH}    
...    แก้ไขผลงานตีพิมพ์    
...    กรอกข้อมูลงานวิจัย    
...    แหล่งเผยแพร่งานวิจัย    ชื่องานวิจัย    บทคัดย่อ    คำค้น    ชื่อวารสาร    
...    ***แต่ละคำต้องคั่นด้วยเครื่องหมายเซมิโคลอน (;) และเว้นวรรคหนึ่งครั้ง    
...    ประเภทเอกสาร    
...    ประเภทย่อยของเอกสาร    
...    สื่อที่ตีพิมพ์    
...    ปีที่ตีพิมพ์    
...    เล่ม    
...    ฉบับที่    
...    การอ้างอิง    หน้า    ทุนสนับสนุน    ชื่อผู้เขียน (ภายใน)    ชื่อผู้เขียน (ภายนอก)

 

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

Go To Pub
    Wait Until Element Is Visible    ${Paper_MENU}    timeout=10s
    Click Element    ${Paper_MENU}

Go To EditPub
    Wait Until Element Is Visible    ${EDIT_Pub_BUTTON}    timeout=10s
    Click Element    ${EDIT_Pub_BUTTON}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Paper
    Scroll Element Into View    ${ADD_Paper_BUTTON}
    Wait Until Element Is Visible    ${ADD_Paper_BUTTON}    timeout=15s
    Log    ${ADD_Paper_BUTTON}
    Log Source
    Click Element    ${ADD_Paper_BUTTON}
    Sleep    2s

Go To VIEW Paper
    Scroll Element Into View    ${VIEW_Paper_BUTTON}
    Wait Until Element Is Visible    ${VIEW_Paper_BUTTON}    timeout=30s
    Log    ${VIEW_Paper_BUTTON}
    Log Source
    Click Element    ${VIEW_Paper_BUTTON}
    Sleep    2s

Go To EDIT Paper
    Scroll Element Into View    ${EDIT_Pub_BUTTON}
    Wait Until Element Is Visible    ${EDIT_Pub_BUTTON}    timeout=15s
    Log    ${EDIT_Pub_BUTTON}
    Log Source
    Click Element    ${EDIT_Pub_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_Paper_BUTTON}

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
Test Teacher PAPER In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To ManagePub
    Go To Pub
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Paper_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Change Language    ${LANG_TO_THAI}
    Verify Page Contains Multiple Texts    @{EXPECTED_Paper_PAGE_TH}
    Sleep    1s
    Logout

Test Teacher Add PAPER In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To ManagePub
    Go To Pub
    Go To Add Paper
    Sleep    2s
    Change Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_Paper_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher View PAPER detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To ManagePub
    Go To Pub
    Go To VIEW Paper
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Detail_EN}
    Change Language    ${LANG_TO_THAI}   
    Scroll Page Down
    Verify Page Contains Multiple Texts     @{EXPECTED_Detail_TH} 
    
    Logout

Test Teacher Edit Paper
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    
    Go To ManagePub
    Go To Pub
    Go To EDIT Paper
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_EN}
    Scroll Page Down
    Sleep    2s
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_TH}
    Scroll Page Down
    Sleep    2s




