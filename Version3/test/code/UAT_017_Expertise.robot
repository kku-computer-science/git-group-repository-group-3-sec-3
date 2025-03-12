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

${Expertise_MENU}    xpath=//a[contains(@class,'nav-link') and .//span[contains(@class,'menu-title') and (contains(text(),'Expertise') or contains(text(),'ความเชี่ยวชาญ') or contains(text(),'权限'))]]
${SORT_BY_ID_TH}    xpath=//th[contains(@class, 'sorting') and contains(@aria-label, 'ลำดับ')]
${SORT_BY_ID}    xpath=//th[contains(@class, 'sorting') and contains(@aria-label, 'ID')]
${SORT_BY_ID_CN}    xpath=//th[contains(@class, 'sorting') and contains(@aria-label, '序号')]

# ปุ่ม 
${ADD_Expertise_BUTTON}    xpath=//div[contains(@class,'card-header')]//a[contains(@href, '/Expertises/create')]
${VIEW_Expertise_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_Expertise_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_REARCH_GROUP_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]



@{EXPECTED_Expertise_PAGE_EN}    
...    Teacher Expertise    ID    Teacher Name    Name    Action    Show     entries    Search:    
...    	Sartra Wongthanavasu    	Big Data Analytics    Computer Vision    	Cellular Automata
@{EXPECTED_Expertise_PAGE_TH}    
...    ความเชี่ยวชาญของอาจารย์    ลำดับ    ชื่ออาจารย์     ชื่อ    การกระทำ    แสดง     รายการ    ค้นหา:    
...    	ศาสตรา วงศ์ธนวสุ    	การวิเคราะห์ข้อมูลขนาดใหญ่    	การมองเห็นของคอมพิวเตอร์    ออโตมาตาของเซลลูลาร์   
@{EXPECTED_Expertise_PAGE_CN}    
...    教师专长    编号    教师姓名    名称    操作    	Sartra Wongthanavasu    大数据分析    	计算机视觉    细胞自动机
	           


# @{EXPECTED_ADD_Expertise_PAGE_EN}    
# ...    Create Expertise
# ...    Expertise Name (Thai):    Expertise Name (English): 
# ...    Expertise                     
# ...    Submit            
# @{EXPECTED_ADD_Expertise_PAGE_CN}    
# ...    创建角色    名字    允许:
# ...    提交        

# @{EXPECTED_Detail_EN}    
# ...    Expertise Name (Thai):    สาขาวิชาวิทยาการคอมพิวเตอร์    
# ...    Expertise Name (English):    Expertise of Computer Science  
# ...    Back
   

# @{EXPECTED_Detail_CN}    
# ...    部门名称（泰语）:    部门名称（英语）:  
# ...    返回

# @{EXPECTED_EDIT_EN}    
# ...    Expertise Name (Thai):       
# ...    Expertise Name (English):                   
# ...    Submit    

# @{EXPECTED_DELETE_EN}    
# ...    Are you sure?
# ...    If you delete this, it will be gone forever.   
# ...    Cancel      
# ...    OK   

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

Scroll Page Down Slightly
    Execute JavaScript    window.scrollBy(0, 5000)
    Sleep    0.5s

Scroll Up
    Execute JavaScript    window.scrollTo(0, 0)

Change Language
    [Arguments]    ${language_button}
    Click Element    ${language_button}
    Sleep    3s

Go To Expertise
    Wait Until Element Is Visible    ${Expertise_MENU}    timeout=10s
    Click Element    ${Expertise_MENU}

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Go To Add Expertise
    Scroll Element Into View    ${ADD_Expertise_BUTTON}
    Wait Until Element Is Visible    ${ADD_Expertise_BUTTON}    timeout=15s
    Log    ${ADD_Expertise_BUTTON}
    Log Source
    Click Element    ${ADD_Expertise_BUTTON}
    Sleep    2s

Go To VIEW Expertise
    Scroll Element Into View    ${VIEW_Expertise_BUTTON}
    Wait Until Element Is Visible    ${VIEW_Expertise_BUTTON}    timeout=15s
    Log    ${VIEW_Expertise_BUTTON}
    Log Source
    Click Element    ${VIEW_Expertise_BUTTON}
    Sleep    2s

Go To EDIT Expertise
    Scroll Element Into View    ${EDIT_Expertise_BUTTON}
    Wait Until Element Is Visible    ${EDIT_Expertise_BUTTON}    timeout=15s
    Log    ${EDIT_Expertise_BUTTON}
    Log Source
    Click Element    ${EDIT_Expertise_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_REARCH_GROUP_BUTTON}
    Wait Until Element Is Visible    ${DELETE_REARCH_GROUP_BUTTON}    timeout=10s
    Click Element    ${DELETE_REARCH_GROUP_BUTTON}

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_Expertise_BUTTON}

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
Test Admin Expertise In English
    Open Browser To Login Page
    Login As Admin    admin@gmail.com    12345678
    Scroll Page Down
    Go To Expertise
    Sleep    5s
    Click Element    ${SORT_BY_ID_TH}
    Sleep    2s
    Change Language    ${LANG_TO_ENGLISH}
    Click Element    ${SORT_BY_ID}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_Expertise_PAGE_EN}
    Scroll Page Down Slightly
    Scroll Up
    Sleep    2s
    Change Language    ${LANG_TO_CHINESE}
    Click Element    ${SORT_BY_ID_CN}
    Scroll Page Down Slightly
    Scroll Up
    Sleep    2s

    Logout



    
