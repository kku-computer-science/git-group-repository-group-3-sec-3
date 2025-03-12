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
${Book_MENU}    xpath=//div[@id='ManagePublications']//a[contains(@class,'nav-link') and contains(text(),'Book')]


# ปุ่ม 
${ADD_Book_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/books/create')]
${VIEW_BOOK_BUTTON}    xpath=//td//a[contains(@title, 'View') or .//i[contains(@class, 'mdi-eye')]]
${EDIT_BOOK_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]



@{EXPECTED_Book_PAGE_EN}    
...    Book    
...    No.    
...    Book Name	    
...    Source    
...    Page    
...    ADD    
...    Year        
...    Action    
@{EXPECTED_RPG_PAGE_CN}    
...    书籍
...    序号    
...    书名	    
...    年份    
...    来源    
...    页数	        
...    操作    


@{EXPECTED_ADD_Book_PAGE_EN}    
...    Add Book
...    Enter Book Details  
...    Book Name      
...    Place of Publication   
...    Year (A.D.)   
...    Page (Count)                
...    Submit    
...    Cancel         
@{EXPECTED_ADD_BOOK_PAGE_CN}    
...    添加书籍
...    填写书籍详情  
...    书名     
...    出版地  
...    年份（公元） 
...    页数   
...    提交    
...    取消 

@{EXPECTED_VIEW_Book_PAGE_EN}    
...    Book Detail
...    Book Information 
...    Book Name      
...    Year   
...    Source              
        
@{EXPECTED_VIEW_BOOK_PAGE_CN}    
...    书籍详情
...    书籍信息 
...    书名  
...    年份 
...    来源 
    
   
    
@{EXPECTED_EDIT_Book_PAGE_EN}    
...    Edit Book Detail
...    Enter Book Information 
...    Book Name      
...    Publication Year (B.E.)   
...    Page (Count)                
...    Submit    
...    Cancel         
@{EXPECTED_EDIT_BOOK_PAGE_CN}    
...    编辑书籍详情
...    填写书籍信息  
...    书名    
...    出版地 
...    出版年份（佛历） 
...    页数  
...    提交    
...    取消   

 

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

Go To Book
    Wait Until Element Is Visible    ${Book_MENU}    timeout=10s
    Click Element    ${Book_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Book
    Scroll Element Into View    ${ADD_Book_BUTTON}
    Wait Until Element Is Visible    ${ADD_Book_BUTTON}    timeout=15s
    Log    ${ADD_Book_BUTTON}
    Log Source
    Click Element    ${ADD_Book_BUTTON}
    Sleep    2s

Go To VIEW BOOK
    Scroll Element Into View    ${VIEW_BOOK_BUTTON}
    Wait Until Element Is Visible    ${VIEW_BOOK_BUTTON}    timeout=30s
    Log    ${VIEW_BOOK_BUTTON}
    Log Source
    Click Element    ${VIEW_BOOK_BUTTON}
    Sleep    2s

Go To EDIT Book
    Scroll Element Into View    ${EDIT_BOOK_BUTTON}
    Wait Until Element Is Visible    ${EDIT_BOOK_BUTTON}    timeout=15s
    Log    ${EDIT_BOOK_BUTTON}
    Log Source
    Click Element    ${EDIT_BOOK_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_Book_BUTTON}

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
Test Teacher Book In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    
    Go To ManagePub
    Go To Book
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Book_PAGE_EN}
    Scroll Page Down
    Sleep    2s
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    Sleep    1s
    Logout

Test Teacher Add Book In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    
    Go To ManagePub
    Go To Book
    Go To Add Book
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_ADD_Book_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher View Book Detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    
    Go To ManagePub
    Go To Book
    Go To VIEW BOOK
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_VIEW_Book_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout

Test Teacher Edit Book Detail In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    
    Go To ManagePub
    Go To Book
    Go To EDIT Book
    Change Language    ${LANG_TO_ENGLISH}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EDIT_Book_PAGE_EN}
    Scroll Page Down
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_THAI}
    
    Logout
