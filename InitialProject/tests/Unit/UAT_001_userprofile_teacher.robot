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
${LOGIN_BUTTON}         xpath=//button[contains(text(), 'Login') or contains(text(), 'เข้าสู่ระบบ') or contains(text(), '登录')]

# Dashboard Elements
${DASHBOARD_HEADER}    xpath=//h1[contains(text(), 'Research Information Management System') or contains(text(), 'ระบบจัดการข้อมูลการวิจัย') or contains(text(), '研究信息管理系统')]
${LOGOUT_BUTTON}       xpath=//a[contains(text(), 'Logout') or contains(text(), 'ออกจากระบบ') or contains(text(), '注销')]

${UPDATE_BUTTON}    xpath=//button[contains(@class, 'btn-primary') and contains(text(), 'Update')]

${ACCOUNT_TITLE}       xpath=//h3[contains(text(), 'Profile Settings')]
     
# User Profile Menu
${USER_PROFILE_MENU}   xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'User Profile') or contains(text(),'โปรไฟล์ผู้ใช้') or contains(text(),'用户配置文件'))]]
${ACCOUNT_TAB}         xpath=//a[@id='account-tab']
${PASSWORD_TAB}        xpath=//a[@id='password-tab']
${EXPERTISE_TAB}       xpath=//a[@id='expertise-tab']
${EDUCATION_TAB}       xpath=//a[@id='education-tab']

# Language Buttons
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

@{EXPECTED_Account_EN}    
...    Profile Settings    
...    Name title    
...    Name(English)    Last Name (English)      
...    First Name (Thai)    Last Name (Thai)    Email    Academic Ranks (English)    Academic Ranks (Thai)    For those without a doctorate, please specify.   

@{EXPECTED_Password_EN}    
...    Password Settings    
...    Old Password    New Password    Confirm Password  

@{EXPECTED_EXPECTED_TH}    
...    ความเชี่ยวชาญ    วิศวกรรมซอฟต์แวร์    กระบวนการพัฒนาซอฟต์แวร์    คุณภาพของโค้ดและซอฟต์แวร์    วิศวกรรมซอฟต์แวร์ที่ปลอดภัย    ความปลอดภัยของเครือข่ายคอมพิวเตอร์    ตัวแทนเคลื่อนที่และระบบหลายเอเยนต์   

@{EXPECTED_EXPECTED_EN}    
...    Expertise    Software Engineering    Software Process    Code Smells and Software Quality    Secure Software Engineering    Computer Network Security    Mobile Agent and Multi-Agent Systems    

@{EXPECTED_EDUCATION_EN}    
...    Education History    Bachelor degree    Name of university    Degree Name    Year of graduation
...    Master degree    PhD

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

Wait Until Account Tab Is Loaded
    Click Element    ${ACCOUNT_TAB}
    Sleep    3s  # รอให้เนื้อหาโหลดเต็มที่
    Wait Until Element Is Visible    xpath=//div[@id='account' and contains(@class, 'show active')]    timeout=10s
    Wait Until Element Is Visible    ${ACCOUNT_TITLE}    timeout=10s


Change Language
    [Arguments]    ${language_button}
    Click Element    ${language_button}
    Sleep    3s

Go To User Profile
    Click Element    ${USER_PROFILE_MENU}

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
Test Teacher Account Tab
    Open Browser To Login Page
    Login As Teacher    chitsutha@kku.ac.th    123456789
    Go To User Profile    
    Click Element    ${ACCOUNT_TAB}
    Sleep    2s
    Click Element    ${ACCOUNT_TAB}  
    Sleep    2s
    Change Language    ${LANG_TO_ENGLISH}
    Click Element    ${ACCOUNT_TAB}
    Sleep    2s
    Click Element    ${ACCOUNT_TAB}  
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Account_EN}
    Execute JavaScript    window.scrollTo(0,1000)
    Sleep    2s
    Scroll Up
    Sleep    2s
    Logout

Test Teacher Password Tab
    Open Browser To Login Page
    Login As Teacher    chitsutha@kku.ac.th    123456789
    Go To User Profile    
    Click Element    ${PASSWORD_TAB} 
    Sleep    2s
    Click Element    ${PASSWORD_TAB}   
    Sleep    2s
    Change Language    ${LANG_TO_ENGLISH}
    Click Element    ${PASSWORD_TAB} 
    Sleep    2s
    Click Element    ${PASSWORD_TAB}   
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Password_EN} 
    Sleep    2s
    Logout

Test Teacher Expertise Tab
    Open Browser To Login Page
    Login As Teacher    chitsutha@kku.ac.th    123456789
    Go To User Profile    
    Click Element    ${EXPERTISE_TAB} 
    Sleep    2s
    Click Element    ${EXPERTISE_TAB}   
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EXPECTED_TH} 
    Execute JavaScript    window.scrollTo(0,1000) 
    Sleep    1s
    Execute JavaScript    window.scrollTo(0,0) 
    Sleep    1s
    Change Language    ${LANG_TO_ENGLISH}
    Click Element    ${EXPERTISE_TAB} 
    Sleep    2s
    Click Element    ${EXPERTISE_TAB}   
    Sleep    2s
    Execute JavaScript    window.scrollTo(0,1000)    
    Sleep    2s
    Scroll Up
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EXPECTED_EN}   
    Logout



Test Teacher Education Tab
    Open Browser To Login Page
    Login As Teacher    chitsutha@kku.ac.th    123456789
    Go To User Profile    
    Click Element    ${EDUCATION_TAB} 
    Sleep    2s
    Click Element    ${EDUCATION_TAB}    
    Sleep    2s
    Change Language    ${LANG_TO_ENGLISH}
    Click Element    ${EDUCATION_TAB} 
    Sleep    2s
    Click Element    ${EDUCATION_TAB}    
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_EDUCATION_EN}  
    Sleep    2s
    Logout
