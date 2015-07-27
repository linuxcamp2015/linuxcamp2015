<?php
session_start();      //주의:파일 최상단에 위치시켜주세요!!
$_GET[regname]=mb_convert_encoding( $_GET[regname] , "CP949", "utf-8" );
$_GET[regworkplace]=mb_convert_encoding( $_GET[regworkplace] , "CP949", "utf-8" );
$_GET[regpfname]=mb_convert_encoding( $_GET[regpfname] , "CP949", "utf-8" );

require_once("/var/www/html/linux_camp/wordpress/INIpay50/registration/db_setting.php");

$server_name = SERVER_NAME;
$db_name = DB_NAME;

$dbh = new PDO("mysql:host=$server_name;dbname=$db_name", USER_NAME, PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$res_regmems = $dbh->query("SELECT * FROM regi_member");
$num_row = $res_regmems->rowCount();


if($_GET["regname"] && $_GET["regphone"] && $_GET["regemail"] && $_GET["regworkplace"] && $_GET["regpos"] && $num_row < MAX_NUM_ROW )
{

/* * ************************
 * 1. 라이브러리 인클루드 *
 * ************************ */
require("../libs/INILib.php");

/* * *************************************
 * 2. INIpay50 클래스의 인스턴스 생성  *
 * ************************************* */
$inipay = new INIpay50;

/* * ************************
 * 3. 암호화 대상/값 설정 *
 * ************************ */
$inipay->SetField("inipayhome", "/var/www/html/linux_camp/wordpress/INIpay50");       // 이니페이 홈디렉터리(상점수정 필요)
$inipay->SetField("type", "chkfake");      // 고정 (절대 수정 불가)
$inipay->SetField("debug", "true");        // 로그모드("true"로 설정하면 상세로그가 생성됨.)
$inipay->SetField("enctype", "asym");    //asym:비대칭, symm:대칭(현재 asym으로 고정)
/* * ************************************************************************************************
 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
 * ************************************************************************************************ */
$inipay->SetField("admin", "1111");     // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
$inipay->SetField("checkopt", "false");   //base64함:false, base64안함:true(현재 false로 고정)
//필수항목 : mid, price, nointerest, quotabase
//추가가능 : INIregno, oid
//*주의* : 	추가가능한 항목중 암호화 대상항목에 추가한 필드는 반드시 hidden 필드에선 제거하고 
//          SESSION이나 DB를 이용해 다음페이지(INIsecureresult.php)로 전달/셋팅되어야 합니다.
$inipay->SetField("mid", "Kisssig002");            // 상점아이디
$inipay->SetField("price", "$_GET[regpos]");                // 가격
$inipay->SetField("nointerest", "no");             //무이자여부(no:일반, yes:무이자)
$inipay->SetField("quotabase", "선택:일시불:2개월:3개월:6개월"); //할부기간
$regoid='linuxcamp_'.mt_rand(10000000,99999999);
$inipay->SetField("oid", "$regoid");


/* * ******************************
 * 4. 암호화 대상/값을 암호화함 *
 * ****************************** */
$inipay->startAction();

/* * *******************
 * 5. 암호화 결과  *
 * ******************* */
if ($inipay->GetResult("ResultCode") != "00") {
    echo $inipay->GetResult("ResultMsg");
    exit(0);
}

/* * *******************
 * 6. 세션정보 저장  *
 * ******************* */
$_SESSION['INI_MID'] = "Kisssig002"; //상점ID
$_SESSION['INI_ADMIN'] = "1111";   // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
$_SESSION['INI_PRICE'] = "$_GET[regpos]";     //가격 
$_SESSION['INI_RN'] = $inipay->GetResult("rn"); //고정 (절대 수정 불가)
$_SESSION['INI_ENCTYPE'] = $inipay->GetResult("enctype"); //고정 (절대 수정 불가)
}
  else if ( $num_row >= MAX_NUM_ROW )
  {
    echo '<script language="javascript">';
    echo 'parent.location.replace("http://linuxcamp.kau.ac.kr/?page_id=185")';       
    echo '</script>';
  }

  else 
  {
    echo '<script language="javascript">';
    echo 'parent.location.replace("register_err.php")';
    echo '</script>';
  }

?>

<html>    
    <head>
        <title>결제 페이지</title>
        <meta http-equiv="Content-Type" content="text/html; charset=CP949">
        <link rel="stylesheet" href="css/group.css" type="text/css">

        <!-- 아래의 meta tag 4가지 항목을 반드시 추가 하시기 바랍니다. -->
        <meta http-equiv="Cache-Control" content="no-cache"/> 
        <meta http-equiv="Expires" content="0"/> 
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" />
        <!-- 끝  -->

        <style>
            body, tr, td {font-size:10pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
            table, img {border:none}

            /* Padding ******/ 
            .pl_01 {padding:1 10 0 10; line-height:19px;}
            .pl_03 {font-size:20pt; font-family:굴림,verdana; color:#FFFFFF; line-height:29px;}

            /* Link ******/ 
            .a:link  {font-size:9pt; color:#333333; text-decoration:none}
            .a:visited { font-size:9pt; color:#333333; text-decoration:none}
            .a:hover  {font-size:9pt; color:#0174CD; text-decoration:underline}

            .txt_03a:link  {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
            .txt_03a:visited {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
            .txt_03a:hover  {font-size: 8pt;line-height:18px;color:#EC5900; text-decoration:underline}
        </style>


        <!-------------------------------------------------------------------------------
        * 웹SITE 가 https를 이용하면 https://plugin.inicis.com/pay61_secunissl_cross.js 사용 
        * 웹SITE 가 Unicode(UTF-8)를 이용하면 http://plugin.inicis.com/pay61_secuni_cross.js 사용
        * 웹SITE 가 https, unicode를 이용하면 https://plugin.inicis.com/pay61_secunissl_cross.js 사용  
        -------------------------------------------------------------------------------->
        <script language=javascript src="http://plugin.inicis.com/pay61_secuni_cross.js"></script> 
        <script language=javascript>
            StartSmartUpdate();

            var openwin;

            function pay(frm)
            {
                // MakePayMessage()를 호출함으로써 플러그인이 화면에 나타나며, Hidden Field
                // 에 값들이 채워지게 됩니다. 일반적인 경우, 플러그인은 결제처리를 직접하는 것이
                // 아니라, 중요한 정보를 암호화 하여 Hidden Field의 값들을 채우고 종료하며,
                // 다음 페이지인 INIsecureresult.php로 데이터가 포스트 되어 결제 처리됨을 유의하시기 바랍니다.

                if (document.ini.clickcontrol.value == "enable")
                {

                    if (document.ini.goodname.value == "")  // 필수항목 체크 (상품명, 상품가격, 구매자명, 구매자 이메일주소, 구매자 전화번호)
                    {
                        alert("상품명이 빠졌습니다. 필수항목입니다.");
                        return false;
                    }
                    else if (document.ini.buyername.value == "")
                    {
                        alert("구매자명이 빠졌습니다. 필수항목입니다.");
                        return false;
                    }
                    else if (document.ini.buyeremail.value == "")
                    {
                        alert("구매자 이메일주소가 빠졌습니다. 필수항목입니다.");
                        return false;
                    }
                    else if (document.ini.buyertel.value == "")
                    {
                        alert("구매자 전화번호가 빠졌습니다. 필수항목입니다.");
                        return false;
                    }
                    else if (ini_IsInstalledPlugin() == false) //플러그인 설치유무 체크
                    {
                        alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.");
                        return false;
                    }
                    else {
                        if (MakePayMessage(frm)) {
                            disable_click();
                            openwin = window.open("childwin.html", "childwin", "width=299,height=149");
                            return true;
                        } else {
                            if (IsPluginModule()) {//plugin타입 체크
                                alert("결제를 취소하셨습니다.");
                            }
                            return false;
                        }
                    }
                }
                else
                {
                    return false;
                }
            }


            function enable_click()
            {
                document.ini.clickcontrol.value = "enable"
            }

            function disable_click()
            {
                document.ini.clickcontrol.value = "disable"
            }

            function focus_control()
            {
                if (document.ini.clickcontrol.value == "disable")
                    openwin.focus();
            }
        </script>


        <script language="JavaScript" type="text/JavaScript">
            <!--
            function MM_reloadPage(init) {  //reloads the window if Nav4 resized
            if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
            else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
            }
            MM_reloadPage(true);

            function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if (restore) selObj.selectedIndex=0;
            }
            //-->
        </script>
    </head>

    <!-----------------------------------------------------------------------------------------------------
    ※ 주의 ※
     아래의 body TAG의 내용중에 
     onload="javascript:enable_click()" onFocus="javascript:focus_control()" 이 부분은 수정없이 그대로 사용.
     아래의 form TAG내용도 수정없이 그대로 사용.
    ------------------------------------------------------------------------------------------------------->

    <body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 onload="javascript:enable_click()" onFocus="javascript:focus_control()"><center>
        <form name=ini method=post action="INIsecureresult.php" onSubmit="return pay(this)"> 
            <table width="632" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                    <td height="85" background="img/card.gif" style="padding:0 0 0 64">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                                <td width="3%" valign="top"><img src="img/title_01.gif" width="8" height="27" vspace="5"></td>
                                <td width="97%" height="40" class="pl_03"><font color="#FFFFFF"><b>리눅스커널캠프 사전등록</b></font></td>
                            </tr>
                        </table></td>
                </tr>
                <tr> 
                    <td align="center" bgcolor="6095BC"><table width="620" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td bgcolor="#FFFFFF" style="padding:8 0 0 56">
                                    <table width="510" border="0" cellspacing="0" cellpadding="0">
                                        <tr> 
                                            <td width="7"><img src="img/life.gif" width="7" height="30"></td>
                                            <td background="img/center.gif"><img src="img/icon03.gif" width="12" height="10"> 
                                                <b>입력하신 정보를 확인하신 후 결제버튼을 눌러주십시오.</b></td>
                                            <td width="8"><img src="img/right.gif" width="8" height="30"></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table width="510" border="0" cellspacing="0" cellpadding="0">
                                        <tr> 
                                            <td width="510" colspan="2"  style="padding:0 0 0 23"> <table width="470" border="0" cellspacing="0" cellpadding="0">
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="177" height="26">상 품 명</td>
                                                        <td width="280"><input type=text name=goodname size=25 value="리눅스커널캠프 참가신청" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">성 명</td>
                                                        <td width="343"><input type=text name=buyername size=25 value="<?php echo "$_GET[regname]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">이메일 주소</td>
                                                        <td width="343"><input type=text name=buyeremail size=25 value="<?php echo "$_GET[regemail]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>

                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">휴대폰 번호</td>
                                                        <td width="343"><input type=text name=buyertel size=25 value="<?php echo "$_GET[regphone]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">학교명 / 직장명</td>
                                                        <td width="343"><input type=text name=workplace size=25 value="<?php echo "$_GET[regworkplace]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">지도 교수</td>
                                                        <td width="343"><input type=text name=pfname size=25 value="<?php echo "$_GET[regpfname]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">결제 금액</td>
                                                        <td width="343"><input type=text name=pos size=25 value="<?php echo "$_GET[regpos]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">거래 번호</td>
                                                        <td width="343"><input type=text name=dealoid size=25 value="<?php echo "$regoid"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>



                                                    <tr valign="bottom"> 
                                                        <td height="40" colspan="3" align="center"><input type=image src="img/button_03.gif" width="63" height="25"></td>
                                                    </tr>
                                                </table></td>
                                        </tr>
                                    </table>
                                    <br>
                                </td>
                            </tr>
                        </table></td>
                </tr>
                <tr> 
                    <td><img src="img/bottom01.gif" width="632" height="13"></td>
                </tr>
            </table>
    </center>


    <!-- 기타설정 -->
    <input type=hidden name=currency size=20 value="WON">

    <!--
    SKIN : 플러그인 스킨 칼라 변경 기능 - 6가지 칼라(ORIGINAL, GREEN, ORANGE, BLUE, KAKKI, GRAY)
    HPP : 컨텐츠 또는 실물 결제 여부에 따라 HPP(1)과 HPP(2)중 선택 적용(HPP(1):컨텐츠, HPP(2):실물).
    Card(0): 신용카드 지불시에 이니시스 대표 가맹점인 경우에 필수적으로 세팅 필요 ( 자체 가맹점인 경우에는 카드사의 계약에 따라 설정) - 자세한 내용은 메뉴얼  참조.
    OCB : OK CASH BAG 가맹점으로 신용카드 결제시에 OK CASH BAG 적립을 적용하시기 원하시면 "OCB" 세팅 필요 그 외에 경우에는 삭제해야 정상적인 결제 이루어짐.
    no_receipt : 은행계좌이체시 현금영수증 발행여부 체크박스 비활성화 (현금영수증 발급 계약이 되어 있어야 사용가능)
    -->
    <input type=hidden name=acceptmethod size=20 value="HPP(1):Card(0):OCB:receipt:cardpoint">


    <!--
    상점 주문번호 : 무통장입금 예약(가상계좌 이체),전화결재 관련 필수필드로 반드시 상점의 주문번호를 페이지에 추가해야 합니다.
    결제수단 중에 은행 계좌이체 이용 시에는 주문 번호가 결제결과를 조회하는 기준 필드가 됩니다.
    상점 주문번호는 최대 40 BYTE 길이입니다.
    주의:절대 한글값을 입력하시면 안됩니다.
    -->
<!--    <input type=hidden name=oid size=40 value="oid_1234567890">-->


    <!--
    플러그인 좌측 상단 상점 로고 이미지 사용
    이미지의 크기 : 90 X 34 pixels
    플러그인 좌측 상단에 상점 로고 이미지를 사용하실 수 있으며,
    주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 상단 부분에 상점 이미지를 삽입할수 있습니다.
    -->
    <!--input type=hidden name=ini_logoimage_url  value="http://[사용할 이미지주소]"-->

    <!--
    좌측 결제메뉴 위치에 이미지 추가
    이미지의 크기 : 단일 결제 수단 - 91 X 148 pixels, 신용카드/ISP/계좌이체/가상계좌 - 91 X 96 pixels
    좌측 결제메뉴 위치에 미미지를 추가하시 위해서는 담당 영업대표에게 사용여부 계약을 하신 후
    주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 좌측 결제메뉴 부분에 이미지를 삽입할수 있습니다.
    -->
    <!--input type=hidden name=ini_menuarea_url value="http://[사용할 이미지주소]"-->

    <!--
    플러그인에 의해서 값이 채워지거나, 플러그인이 참조하는 필드들
    삭제/수정 불가
    uid 필드에 절대로 임의의 값을 넣지 않도록 하시기 바랍니다.
    -->
    <input type=hidden name=ini_encfield value="<?php echo($inipay->GetResult("encfield")); ?>">
    <input type=hidden name=ini_certid value="<?php echo($inipay->GetResult("certid")); ?>">
    <input type=hidden name=quotainterest value="">
    <input type=hidden name=paymethod value="Card">
    <input type=hidden name=cardcode value="">
    <input type=hidden name=cardquota value="">
    <input type=hidden name=rbankcode value="">
    <input type=hidden name=reqsign value="DONE">
    <input type=hidden name=encrypted value="">
    <input type=hidden name=sessionkey value="">
    <input type=hidden name=uid value=""> 
    <input type=hidden name=sid value="">
    <input type=hidden name=version value=4000>
    <input type=hidden name=clickcontrol value="">

</form>
</body>
</html>                                                                                                                                                                                                                                                                                                                                                                                                                                                              
