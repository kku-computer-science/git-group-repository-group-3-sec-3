*** Settings ***
Documentation    Test suite for verifying language switching functionality on the Research Group page.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${URL}        https://cssoften0368.cpkkuhost.com
${WAIT_TIME}  3s

${RESEARCH_GROUP_MENU}    xpath=//nav//a[contains(@class, 'nav-link') and contains(text(), 'กลุ่มวิจัย')]

@{EXPECTED_DETAIL_TH}    
...    เทคโนโลยี GIS ขั้นสูง (AGT)    
...    เพื่อดำเนินการวิจัยและให้บริการวิชาการในสาขาอินเทอร์เน็ต GIS สุขภาพ GIS และแบบจำลองทางอุทกวิทยาด้วย GIS    
...    รศ.ดร. ชัยพล กีรติกสิกร    
...    ผศ.ดร. พิพัธน์ เรืองแสง    
...    ผศ.ดร. ณกร วัฒนกิจ

@{EXPECTED_DETAIL_EN}    
...    Advanced GIS Technology (AGT)    
...    To conduct research and provide academic services in the fields of Internet, GIS, Health GIS, and Hydrologic modeling with GIS.
...    Asst.    Prof.    Pipat    Reungsang   Ph.D.    
...    Assoc.    Prof.    Chaiyapon    Keeratikasikorn    Ph.D.    
...    Asst.    Prof.    Nagon Watanakij    Ph.D.

@{EXPECTED_DETAIL_CN}    
...    高级 GIS 技术 （AGT）   
...    使用 GIS 在 Internet GIS、GIS 健康和水文建模领域进行研究并提供学术服务
...    Asst.    Prof.    Pipat    Reungsang       
...    Assoc.    Prof.    Chaiyapon    Keeratikasikorn        
...    Asst.    Prof.    Nagon Watanakij    

 


# ✅ Expected Static Texts
@{EXPECTED_THAI_TEXTS}    
...    กลุ่มวิจัย    
...    หัวหน้าห้องปฏิบัติการ    
...    รายละเอียดเพิ่มเติม

@{EXPECTED_THAI_INDETAIL_TEXTS}    
...    กลุ่มวิจัย    
...    หัวหน้าห้องปฏิบัติการ    


@{EXPECTED_ENGLISH_TEXTS}    
...    Research Group    
...    Laboratory Supervisor    
...    More    details

@{EXPECTED_ENGLISH_INDETAIL_TEXTS}    
...    Research Group    
...    Laboratory Supervisor    

@{EXPECTED_CHINESE_TEXTS}    
...    研究小组    
...    实验室负责人    
...    更    多    详    情
@{EXPECTED_CHINESE_INDETAIL_TEXTS}    
...    研究小组    
...    实验室负责人    

# ✅ Language Switchers
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

*** Keywords ***
Open Browser To Report Page
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Verify Page Contains Multiple Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

Scroll Page Down
    ${total_height}=    Execute JavaScript    return document.body.scrollHeight
    ${scroll_position}=    Set Variable    0

    WHILE    ${scroll_position} < ${total_height}
        ${scroll_position}=    Evaluate    ${scroll_position} + 300
        Execute JavaScript    window.scrollTo(0, ${scroll_position})
        Sleep    0.5s
    END

Go To Research Project
    Wait Until Element Is Visible    ${RESEARCH_GROUP_MENU}    timeout=10s
    Scroll Element Into View         ${RESEARCH_GROUP_MENU}
    Sleep    1s
    Click Element                    ${RESEARCH_GROUP_MENU}
    Sleep    2s

Scroll Page Down Slightly
    Execute JavaScript    window.scrollBy(0, 500)
    Sleep    0.5s

Scroll Up
    Execute JavaScript    window.scrollTo(0, 0)

Switch Language
    [Arguments]    ${lang_button}
    Click Element    ${lang_button}
    Sleep    1s  # รอหน้าอัปเดต 

Click More Details
    Wait Until Element Is Visible    xpath=//a[contains(@href, 'researchgroupdetail')]    timeout=10s
    Click Element    xpath=//a[contains(@href, 'researchgroupdetail')]   

*** Test Cases ***
Test Research Project from Thai to English
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Go To Research Project
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_TH}
    Sleep    0.5s
    Switch Language    ${LANG_TO_ENGLISH}
    Verify Page Contains Multiple Texts    @{EXPECTED_ENGLISH_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_EN}
    Scroll Page Down
    Scroll Up
    Sleep    1s
    Click More Details
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_ENGLISH_INDETAIL_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_EN}
    Sleep    2s
    Switch Language    ${LANG_TO_THAI}
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_INDETAIL_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_TH}

    Close Browser

Test Research Project from Thai to Chinese
    Open Browser To Report Page
    Sleep    ${WAIT_TIME}
    Go To Research Project
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_TH}
    Sleep    0.5s
    Switch Language    ${LANG_TO_CHINESE}
    Verify Page Contains Multiple Texts    @{EXPECTED_CHINESE_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_CN}
    Scroll Page Down
    Scroll Up
    Sleep    1s
    Click More Details
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_CHINESE_INDETAIL_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_CN}
    Sleep    2s
    Switch Language    ${LANG_TO_THAI}
    Sleep    1s
    Verify Page Contains Multiple Texts    @{EXPECTED_THAI_INDETAIL_TEXTS}
    Verify Page Contains Multiple Texts    @{EXPECTED_DETAIL_TH}

    Close Browser


    
