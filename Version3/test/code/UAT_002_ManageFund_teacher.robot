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

# ปุ่ม 
${ADD_FUND_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@href, '/funds/create')]
${VIEW_FUND_DETAIL_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-primary') and .//i[contains(@class, 'mdi-eye')]]
${EDIT_MANAGE_FUND_BUTTON}    xpath=//div[contains(@class,'card-body')]//a[contains(@class, 'btn-outline-success') and .//i[contains(@class, 'mdi-pencil')]]
${DELETE_FUND_BUTTON}    xpath=//div[contains(@class,'card-body')]//button[contains(@class, 'show_confirm') and .//i[contains(@class, 'mdi-delete')]]
${BACK_BUTTON}    xpath=//a[contains(text(), 'Back')]
${CANCEL_BUTTON}    xpath=//a[contains(text(), 'Cancel') or contains(text(),'取消')]
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
...    Fund Type    Internal Capital    
...    Fund Level    Not specified	
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

@{EXPECTED_FUND_DETAIL_PAGE_EN}    
...    Fund Detail
...    Fund Description  
...    Funds Name    Year    Description    
...    Fund Type    Internal Capital    
...    Level Funds    Not specified       
...    Agency    Add details by    Pusadee Seresangtakul    
...    Back

@{EXPECTED_FUND_DETAIL_PAGE_TH}    
...    รายละเอียดทุนวิจัย
...    คำอธิบายทุนวิจัย    ชื่อทุน    ปี    รายละเอียด    ประเภทกองทุน    ทุนภายใน    ระดับทุน    หน่วยงาน    เพิ่มรายละเอียดโดย    พุธษดี ศิริแสงตระกูล    ย้อนกลับ    

@{EXPECTED_FUND_DETAIL_PAGE_CN}    
...    资金详情
...    资金描述
...    资金名称    年份    描述    基金类型    内部资金    资金级别    未指定    机构    添加详情由
    
@{EXPECTED_EDITFUND_PAGE_TH}    
...    แก้ไขทุนวิจัย
...    กรอกข้อมูลเพื่อแก้ไขรายละเอียดทุนวิจัย    
...    ประเภททุน    ทุนภายใน   
...    ระดับทุน    ไม่ได้ระบุ   
...    ชื่อทุน    
...    หน่วยงาน/โครงการที่สนับสนุนงานวิจัย    
...    ส่ง    ยกเลิก
@{EXPECTED_EDITFUND_PAGE_EN}    
...    Edit Fund
...    Fill in the information to edit research fund details.    
...    Type Funds    Internal Capital            
...    Level Funds    External capital      
...    Funds Name    
...    Supporting Agencies / Research Projects    
...    Submit    
...    Cancel

@{EXPECTED_DELETE_EN}    
...    Are you sure?
...    If you delete this, it will be gone forever.   



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
Click To Show Fund Type Dropdown
    Wait Until Element Is Visible    xpath=//select[@id='fund_type']    timeout=10s
    Click Element    xpath=//select[@id='fund_type']
    Sleep    1s  # ให้เวลา Dropdown แสดงผล
    
Click To Show Fund Level Dropdown
    Wait Until Element Is Visible    xpath=//select[@name='fund_level']    timeout=10s
    Click Element    xpath=//select[@name='fund_level']
    Sleep    1s  # ให้เวลา Dropdown แสดงผล

Click Add Fund And Verify
    Sleep    3s
    Page Should Contain Element    ${ADD_FUND_BUTTON}

Go To VIEW Fund detail
    Scroll Element Into View    ${VIEW_FUND_DETAIL_BUTTON}
    Wait Until Element Is Visible    ${VIEW_FUND_DETAIL_BUTTON}    timeout=15s
    Log    ${VIEW_FUND_DETAIL_BUTTON}
    Log Source
    Click Element    ${VIEW_FUND_DETAIL_BUTTON}
    Sleep    2s

Go To EDIT Manage Fund
    Scroll Element Into View    ${EDIT_MANAGE_FUND_BUTTON}
    Wait Until Element Is Visible    ${EDIT_MANAGE_FUND_BUTTON}    timeout=15s
    Log    ${EDIT_MANAGE_FUND_BUTTON}
    Log Source
    Click Element    ${EDIT_MANAGE_FUND_BUTTON}
    Sleep    2s

Delete button
    Scroll Element Into View    ${DELETE_FUND_BUTTON}
    Wait Until Element Is Visible    ${DELETE_FUND_BUTTON}    timeout=10s
    Click Element    ${DELETE_FUND_BUTTON}

Logout
    Click Element    ${LOGOUT_BUTTON}
    Wait Until Element Is Visible    ${LOGIN_PAGE_HEADER}    timeout=10s

Back button
    Wait Until Element Is Visible    ${BACK_BUTTON}    timeout=10s
    Click Element    ${BACK_BUTTON}

Cancel button
    Wait Until Element Is Visible    ${Cancel_BUTTON}    timeout=10s
    Click Element    ${Cancel_BUTTON}

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

*** Test Cases ***
Test Teacher Manage Fund In English
    Open Browser To Login Page
    Login As Teacher     pusadee@kku.ac.th    123456789
    Go To Manage Fund
    Change Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_PAGE_EN}
    Go To Add Fund

    Click To Show Fund Type Dropdown
    Click To Show Fund Type Dropdown

    Click To Show Fund Level Dropdown
    Click To Show Fund Level Dropdown

    Verify Page Contains Multiple Texts    @{EXPECTED_ADDFUND_PAGE_EN}
    Sleep    2s
    Change Language    ${LANG_TO_CHINESE}
    Sleep    2s

    Click To Show Fund Type Dropdown
    Click To Show Fund Type Dropdown

    Click To Show Fund Level Dropdown
    Click To Show Fund Level Dropdown
    
    Logout

Test Teacher Veiw Manage Fund 
    Open Browser To Login Page
    Login As Teacher    pusadee@kku.ac.th    123456789
    Go To Manage Fund
    Change Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_PAGE_EN}
    Go To VIEW Fund detail
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_DETAIL_PAGE_EN} 
    Change Language    ${LANG_TO_THAI}
    Sleep    2s
    Verify Page Contains Multiple Texts    @{EXPECTED_FUND_DETAIL_PAGE_TH} 
    Sleep    2s
    Change Language    ${LANG_TO_CHINESE}
    Sleep    2s

    Logout

Test Teacher Edit Manage Fund 
    Open Browser To Login Page
    Login As Teacher    pusadee@kku.ac.th    123456789
    Go To Manage Fund
    Change Language    ${LANG_TO_ENGLISH}
    Go To EDIT Manage Fund
    Verify Page Contains Multiple Texts    @{EXPECTED_EDITFUND_PAGE_EN}
    Cancel button
    Wait Until Page Contains    Fund    timeout=10s
    Sleep    2s
    Delete button
    Wait Until Element Is Visible   xpath=//div[contains(@class,'swal-modal')]//div[contains(@class,'swal-title')]    timeout=10s
    Sleep   2s
    Verify Page Contains Multiple Texts    @{EXPECTED_DELETE_EN}
    Sleep   2s
    Close Browser
    Sleep   0.5s
    Open Browser To Login Page
    Login As Teacher    pusadee@kku.ac.th    123456789
    Go To Manage Fund
    Change Language    ${LANG_TO_CHINESE}
    Go To EDIT Manage Fund
    Sleep    2s
    Cancel button
    Sleep    2s
    Delete button
    Wait Until Element Is Visible   xpath=//div[contains(@class,'swal-modal')]//div[contains(@class,'swal-title')]    timeout=10s
    Sleep   2s

    