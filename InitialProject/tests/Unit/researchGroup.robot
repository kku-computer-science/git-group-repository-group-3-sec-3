*** Settings ***
Documentation    Test suite for verifying language switching functionality on the Research Group page.
Library          SeleniumLibrary
Library          String
Test Teardown    Close Browser

*** Variables ***
${BROWSER}    chrome
${URL}        http://127.0.0.1:8000/researchgroup  
${WAIT_TIME}  3s

#Research Group Name (AGT)
${EXPECTED_GROUPNAME_TH}    เทคโนโลยี GIS ขั้นสูง (AGT)
${EXPECTED_GROUPNAME_EN}    Advanced GIS Technology (AGT)
${EXPECTED_GROUPNAME_CN}    高级 GIS 技术 （AGT）

#Research Group Description (AGT) (ใช้คำหลัก เนื่องจากมีการตัดคำ)
${EXPECTED_GROUPDESC_TH}    เพื่อดำเนินการวิจัยและให้บริการวิชาการในสาขาอินเทอร์เน็ต GIS สุขภาพ GIS และแบบจำลองทางอุทกวิทยาด้วย GIS
${EXPECTED_GROUPDESC_EN}    To conduct research and provide academic services in the fields of Internet, GIS, Health GIS, and Hydrologic modeling with GIS.
${EXPECTED_GROUPDESC_CN}    使用 GIS 在 Internet GIS、GIS 健康和水文建模领域进行研究并提供学术服务

${EXPECTED_GROUPDETAIL_TH}    เพื่อดำเนินการวิจัยและให้บริการวิชาการในสาขาอินเทอร์เน็ต GIS สุขภาพ GIS และแบบจำลองทางอุทกวิทยาด้วย GIS
${EXPECTED_GROUPDETAIL_EN}    To conduct research and provide academic services in the fields of Internet, GIS, Health GIS, and Hydrologic modeling with GIS.
${EXPECTED_GROUPDETAIL_CN}    使用 GIS 在 Internet GIS、GIS 健康和水文建模领域进行研究并提供学术服务

#Project Supervisor (ตรวจสอบชื่อ, นามสกุล, ตำแหน่ง, ปริญญา)
@{EXPECTED_LABSUPERVISOR_TH}    
...    รศ.ดร. ชัยพล กีรติกสิกร    
...    ผศ.ดร. พิพัธน์ เรืองแสง    
...    ผศ.ดร. ณกร วัฒนกิจ

@{EXPECTED_LABSUPERVISOR_EN}    
...    Asst. Prof. Pipat Reungsang, Ph.D.    
...    Assoc. Prof. Chaiyapon Keeratikasikorn, Ph.D.    
...    Asst. Prof. Nagon Watanakij, Ph.D.

@{EXPECTED_LABSUPERVISOR_CN}    
...    Asst. Prof. Pipat Reungsang, Ph.D.    
...    Assoc. Prof. Chaiyapon Keeratikasikorn, Ph.D.    
...    Asst. Prof. Nagon Watanakij, Ph.D.

# ✅ Expected Static Texts
@{EXPECTED_THAI_TEXTS}    
...    กลุ่มวิจัย    
...    หัวหน้าห้องปฏิบัติการ    
...    รายละเอียดเพิ่มเติม

@{EXPECTED_ENGLISH_TEXTS}    
...    Research Group    
...    Laboratory Supervisor    
...    More details

@{EXPECTED_CHINESE_TEXTS}    
...    研究小组    
...    实验室负责人    
...    更多详情

# ✅ Language Switchers
${LANG_TO_THAI}       xpath=//a[contains(text(), 'ไทย')]
${LANG_TO_ENGLISH}    xpath=//a[contains(text(), 'English')]
${LANG_TO_CHINESE}    xpath=//a[contains(text(), '中文')]

*** Keywords ***
Open Browser To Research Group Page
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window

Wait And Click
    [Arguments]    ${locator}
    Wait Until Element Is Visible    ${locator}    timeout=10s
    Click Element    ${locator}

Verify Page Contains Texts
    [Arguments]    @{expected_texts}
    ${html_source}=    Get Source
    Log    HTML Source: ${html_source}
    FOR    ${text}    IN    @{expected_texts}
        Should Contain    ${html_source}    ${text}
    END

Click More Details
    Wait Until Element Is Visible    xpath=//a[contains(@href, 'researchgroupdetail')]    timeout=10s
    Click Element    xpath=//a[contains(@href, 'researchgroupdetail')]
    Sleep    ${WAIT_TIME}

Verify Research Group Name
    [Arguments]    ${expected_group_name}
    Wait Until Element Is Visible    xpath=//h5[contains(text(), '${expected_group_name}')]    timeout=10s
    ${actual_group_name}=    Get Text    xpath=//h5[contains(text(), '${expected_group_name}')]
    Should Be Equal    ${actual_group_name}    ${expected_group_name}

Verify Research Group Description
    [Arguments]    ${expected_group_desc}
    Wait Until Element Is Visible    xpath=//h3[contains(text(), '${expected_group_desc}')]    timeout=10s
    ${actual_group_desc}=    Get Text    xpath=//h3[contains(text(), '${expected_group_desc}')]
    Should Contain    ${actual_group_desc}    ${expected_group_desc}

Verify Project Supervisor
    [Arguments]    @{expected_supervisors}
    ${actual_supervisors}=    Get Text    xpath=//h2[@class='card-text-2']
    Log    Actual Supervisors: ${actual_supervisors}

    FOR    ${supervisor}    IN    @{expected_supervisors}
        Should Contain    ${actual_supervisors}    ${supervisor}
    END

Verify Research Group Detail
    [Arguments]    ${expected_group_detail}
    Sleep    ${WAIT_TIME}
    
    #ดึง HTML ทั้งหมดมาเช็คก่อน
    ${page_source}=    Get Source
    Log    ${page_source}

    #ตรวจสอบว่าเนื้อหาอยู่ในหน้า
    Should Contain    ${page_source}    ${expected_group_detail}

*** Test Cases ***
# ✅ ตรวจสอบว่าเริ่มต้นหน้าเว็บเป็นภาษาไทย
Verify Default Language Is Thai
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Verify Research Group Name    ${EXPECTED_GROUPNAME_TH}
    Verify Research Group Description    ${EXPECTED_GROUPDESC_TH}
    Verify Project Supervisor    @{EXPECTED_LABSUPERVISOR_TH}

# ✅ ตรวจสอบการเปลี่ยนจากไทยเป็นอังกฤษ
Switch To English And Verify
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_ENGLISH_TEXTS}
    Verify Research Group Name    ${EXPECTED_GROUPNAME_EN}
    Verify Research Group Description    ${EXPECTED_GROUPDESC_EN}
    Verify Project Supervisor    @{EXPECTED_LABSUPERVISOR_EN}

# ✅ ตรวจสอบการเปลี่ยนจากไทยเป็นจีน
Switch To Chinese And Verify
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_CHINESE_TEXTS}
    Verify Research Group Name    ${EXPECTED_GROUPNAME_CN}
    Verify Research Group Description    ${EXPECTED_GROUPDESC_CN}
    Verify Project Supervisor    @{EXPECTED_LABSUPERVISOR_CN}

# ✅ ตรวจสอบการเปลี่ยนกลับเป็นภาษาไทย
Switch Back To Thai And Verify
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_THAI}
    Sleep    ${WAIT_TIME}
    Verify Page Contains Texts    @{EXPECTED_THAI_TEXTS}
    Verify Research Group Name    ${EXPECTED_GROUPNAME_TH}
    Verify Research Group Description    ${EXPECTED_GROUPDESC_TH}
    Verify Project Supervisor    @{EXPECTED_LABSUPERVISOR_TH}

Verify Research Group Detail In Thai
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Click More Details
    Verify Research Group Detail    ${EXPECTED_GROUPDETAIL_TH}

Verify Research Group Detail In English
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_ENGLISH}
    Sleep    ${WAIT_TIME}
    Click More Details
    Verify Research Group Detail    ${EXPECTED_GROUPDETAIL_EN}

Verify Research Group Detail In Chinese
    Open Browser To Research Group Page
    Sleep    ${WAIT_TIME}
    Wait And Click    ${LANG_TO_CHINESE}
    Sleep    ${WAIT_TIME}
    Click More Details
    Verify Research Group Detail    ${EXPECTED_GROUPDETAIL_CN}

#Done

