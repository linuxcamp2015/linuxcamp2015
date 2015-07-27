<?php

session_start();      //����:���� �ֻ�ܿ� ��ġ�����ּ���!!


$_GET[regname]=mb_convert_encoding( $_GET[regname] , "CP949", "utf-8" );
$_GET[regworkplace]=mb_convert_encoding( $_GET[regworkplace] , "CP949", "utf-8" );
$_GET[regpfname]=mb_convert_encoding( $_GET[regpfname] , "CP949", "utf-8" );


$dbh = new PDO("mysql:host=SERVER_NAME;dbname=DB_NAME", USER_NAME, PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$num_row = $res_regmems->rowCount();

if($_GET["regname"] && $_GET["regphone"] && $_GET["regemail"] && $_GET["regworkplace"] && $_GET["regpos"] && $num_row != $max_num_row)
{

  /* * ************************
   * 1. ���̺귯�� ��Ŭ��� *
   * ************************ */
  require("../libs/INILib.php");
  
  /* * *************************************
   * 2. INIpay50 Ŭ������ �ν��Ͻ� ����  *
   * ************************************* */
  $inipay = new INIpay50;
  
  /* * ************************
   * 3. ��ȣȭ ���/�� ���� *
   * ************************ */
  $inipay->SetField("inipayhome", "/var/www/html/linux_camp/wordpress/INIpay50");       // �̴����� Ȩ���͸�(�������� �ʿ�)
  $inipay->SetField("type", "chkfake");      // ���� (���� ���� �Ұ�)
  $inipay->SetField("debug", "true");        // �α׸��("true"�� �����ϸ� �󼼷αװ� ������.)
  $inipay->SetField("enctype", "asym");    //asym:���Ī, symm:��Ī(���� asym���� ����)
  /* * ************************************************************************************************
   * admin �� Ű�н����� �������Դϴ�. �����Ͻø� �ȵ˴ϴ�. 1111�� �κи� �����ؼ� ����Ͻñ� �ٶ��ϴ�.
   * Ű�н������ ���������� ������(https://iniweb.inicis.com)�� ��й�ȣ�� �ƴմϴ�. ������ �ֽñ� �ٶ��ϴ�.
   * Ű�н������ ���� 4�ڸ��θ� �����˴ϴ�. �� ���� Ű���� �߱޽� �����˴ϴ�.
   * Ű�н����� ���� Ȯ���Ͻ÷��� �������� �߱޵� Ű���� ���� readme.txt ������ ������ �ֽʽÿ�.
   * ************************************************************************************************ */
  $inipay->SetField("admin", "1111");     // Ű�н�����(Ű�߱޽� ����, ���������� �н������ �������)
  $inipay->SetField("checkopt", "false");   //base64��:false, base64����:true(���� false�� ����)
  //�ʼ��׸� : mid, price, nointerest, quotabase
  //�߰����� : INIregno, oid
  //*����* : 	�߰������� �׸��� ��ȣȭ ����׸� �߰��� �ʵ�� �ݵ�� hidden �ʵ忡�� �����ϰ� 
  //          SESSION�̳� DB�� �̿��� ����������(INIsecureresult.php)�� ����/���õǾ�� �մϴ�.
  $inipay->SetField("mid", "INIpayTest");            // �������̵�
  $inipay->SetField("price", "$_GET[regpos]");                // ����
  $inipay->SetField("nointerest", "no");             //�����ڿ���(no:�Ϲ�, yes:������)
  $inipay->SetField("quotabase", "����:�Ͻú�:2����:3����:6����"); //�ҺαⰣ
  $regoid='linuxcamp_'.mt_rand(10000000,99999999);
  $inipay->SetField("oid", "$regoid");
  
  
  /* * ******************************
   * 4. ��ȣȭ ���/���� ��ȣȭ�� *
   * ****************************** */
  $inipay->startAction();
  
  /* * *******************
   * 5. ��ȣȭ ���  *
   * ******************* */
  if ($inipay->GetResult("ResultCode") != "00") {
    echo $inipay->GetResult("ResultMsg");
    exit(0);
  }
  
  /* * *******************
   * 6. �������� ����  *
   * ******************* */
  $_SESSION['INI_MID'] = "INIpayTest"; //����ID
  $_SESSION['INI_ADMIN'] = "1111";   // Ű�н�����(Ű�߱޽� ����, ���������� �н������ �������)
  $_SESSION['INI_PRICE'] = "$_GET[regpos]";     //���� 
  $_SESSION['INI_RN'] = $inipay->GetResult("rn"); //���� (���� ���� �Ұ�)
  $_SESSION['INI_ENCTYPE'] = $inipay->GetResult("enctype"); //���� (���� ���� �Ұ�)
}

else if ( $num_row >= $max_num_row )
{
  echo '<script language="javascript">';
  echo 'parent.location.replace("register_full.php")';
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
        <title>���� ������</title>
        <meta http-equiv="Content-Type" content="text/html; charset=CP949">
        <link rel="stylesheet" href="css/group.css" type="text/css">

        <!-- �Ʒ��� meta tag 4���� �׸��� �ݵ�� �߰� �Ͻñ� �ٶ��ϴ�. -->
        <meta http-equiv="Cache-Control" content="no-cache"/> 
        <meta http-equiv="Expires" content="0"/> 
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" />
        <!-- ��  -->

        <style>
            body, tr, td {font-size:10pt; font-family:����,verdana; color:#433F37; line-height:19px;}
            table, img {border:none}

            /* Padding ******/ 
            .pl_01 {padding:1 10 0 10; line-height:19px;}
            .pl_03 {font-size:20pt; font-family:����,verdana; color:#FFFFFF; line-height:29px;}

            /* Link ******/ 
            .a:link  {font-size:9pt; color:#333333; text-decoration:none}
            .a:visited { font-size:9pt; color:#333333; text-decoration:none}
            .a:hover  {font-size:9pt; color:#0174CD; text-decoration:underline}

            .txt_03a:link  {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
            .txt_03a:visited {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
            .txt_03a:hover  {font-size: 8pt;line-height:18px;color:#EC5900; text-decoration:underline}
        </style>


        <!-------------------------------------------------------------------------------
        * ��SITE �� https�� �̿��ϸ� https://plugin.inicis.com/pay61_secunissl_cross.js ��� 
        * ��SITE �� Unicode(UTF-8)�� �̿��ϸ� http://plugin.inicis.com/pay61_secuni_cross.js ���
        * ��SITE �� https, unicode�� �̿��ϸ� https://plugin.inicis.com/pay61_secunissl_cross.js ���  
        -------------------------------------------------------------------------------->
        <script language=javascript src="http://plugin.inicis.com/pay61_secuni_cross.js"></script> 
        <script language=javascript>
            StartSmartUpdate();

            var openwin;

            function pay(frm)
            {
                // MakePayMessage()�� ȣ�������ν� �÷������� ȭ�鿡 ��Ÿ����, Hidden Field
                // �� ������ ä������ �˴ϴ�. �Ϲ����� ���, �÷������� ����ó���� �����ϴ� ����
                // �ƴ϶�, �߿��� ������ ��ȣȭ �Ͽ� Hidden Field�� ������ ä��� �����ϸ�,
                // ���� �������� INIsecureresult.php�� �����Ͱ� ����Ʈ �Ǿ� ���� ó������ �����Ͻñ� �ٶ��ϴ�.

                if (document.ini.clickcontrol.value == "enable")
                {

                    if (document.ini.goodname.value == "")  // �ʼ��׸� üũ (��ǰ��, ��ǰ����, �����ڸ�, ������ �̸����ּ�, ������ ��ȭ��ȣ)
                    {
                        alert("��ǰ���� �������ϴ�. �ʼ��׸��Դϴ�.");
                        return false;
                    }
                    else if (document.ini.buyername.value == "")
                    {
                        alert("�����ڸ��� �������ϴ�. �ʼ��׸��Դϴ�.");
                        return false;
                    }
                    else if (document.ini.buyeremail.value == "")
                    {
                        alert("������ �̸����ּҰ� �������ϴ�. �ʼ��׸��Դϴ�.");
                        return false;
                    }
                    else if (document.ini.buyertel.value == "")
                    {
                        alert("������ ��ȭ��ȣ�� �������ϴ�. �ʼ��׸��Դϴ�.");
                        return false;
                    }
                    else if (ini_IsInstalledPlugin() == false) //�÷����� ��ġ���� üũ
                    {
                        alert("\n�̴����� �÷����� 128�� ��ġ���� �ʾҽ��ϴ�. \n\n������ ������ ���Ͽ� �̴����� �÷����� 128�� ��ġ�� �ʿ��մϴ�. \n\n�ٽ� ��ġ�Ͻ÷��� Ctrl + F5Ű�� �����ðų� �޴��� [����/���ΰ�ħ]�� �����Ͽ� �ֽʽÿ�.");
                        return false;
                    }
                    else {
                        if (MakePayMessage(frm)) {
                            disable_click();
                            openwin = window.open("childwin.html", "childwin", "width=299,height=149");
                            return true;
                        } else {
                            if (IsPluginModule()) {//pluginŸ�� üũ
                                alert("������ ����ϼ̽��ϴ�.");
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
    �� ���� ��
     �Ʒ��� body TAG�� �����߿� 
     onload="javascript:enable_click()" onFocus="javascript:focus_control()" �� �κ��� �������� �״�� ���.
     �Ʒ��� form TAG���뵵 �������� �״�� ���.
    ------------------------------------------------------------------------------------------------------->

    <body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 onload="javascript:enable_click()" onFocus="javascript:focus_control()"><center>
        <form name=ini method=post action="INIsecureresult.php" onSubmit="return pay(this)"> 
            <table width="632" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                    <td height="85" background="img/card.gif" style="padding:0 0 0 64">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                                <td width="3%" valign="top"><img src="img/title_01.gif" width="8" height="27" vspace="5"></td>
                                <td width="97%" height="40" class="pl_03"><font color="#FFFFFF"><b>������Ŀ��ķ�� �������</b></font></td>
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
                                                <b>�Է��Ͻ� ������ Ȯ���Ͻ� �� ������ư�� �����ֽʽÿ�.</b></td>
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
                                                        <td width="177" height="26">�� ǰ ��</td>
                                                        <td width="280"><input type=text name=goodname size=25 value="������Ŀ��ķ�� ������û" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">�� ��</td>
                                                        <td width="343"><input type=text name=buyername size=25 value="<?php echo "$_GET[regname]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">�̸��� �ּ�</td>
                                                        <td width="343"><input type=text name=buyeremail size=25 value="<?php echo "$_GET[regemail]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>

                                                    <tr> 
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">�޴��� ��ȣ</td>
                                                        <td width="343"><input type=text name=buyertel size=25 value="<?php echo "$_GET[regphone]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr> 
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">�б��� / �����</td>
                                                        <td width="343"><input type=text name=workplace size=25 value="<?php echo "$_GET[regworkplace]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">���� ����</td>
                                                        <td width="343"><input type=text name=pfname size=25 value="<?php echo "$_GET[regpfname]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">���� �ݾ�</td>
                                                        <td width="343"><input type=text name=pos size=25 value="<?php echo "$_GET[regpos]"?>" readonly=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                                                    </tr>
						    <tr>
                                                        <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                                                        <td width="109" height="25">�ŷ� ��ȣ</td>
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


    <!-- ��Ÿ���� -->
    <input type=hidden name=currency size=20 value="WON">

    <!--
    SKIN : �÷����� ��Ų Į�� ���� ��� - 6���� Į��(ORIGINAL, GREEN, ORANGE, BLUE, KAKKI, GRAY)
    HPP : ������ �Ǵ� �ǹ� ���� ���ο� ���� HPP(1)�� HPP(2)�� ���� ����(HPP(1):������, HPP(2):�ǹ�).
    Card(0): �ſ�ī�� ���ҽÿ� �̴Ͻý� ��ǥ �������� ��쿡 �ʼ������� ���� �ʿ� ( ��ü �������� ��쿡�� ī����� ��࿡ ���� ����) - �ڼ��� ������ �޴���  ����.
    OCB : OK CASH BAG ���������� �ſ�ī�� �����ÿ� OK CASH BAG ������ �����Ͻñ� ���Ͻø� "OCB" ���� �ʿ� �� �ܿ� ��쿡�� �����ؾ� �������� ���� �̷����.
    no_receipt : ���������ü�� ���ݿ����� ���࿩�� üũ�ڽ� ��Ȱ��ȭ (���ݿ����� �߱� ����� �Ǿ� �־�� ��밡��)
    -->
    <input type=hidden name=acceptmethod size=20 value="HPP(1):Card(0):OCB:receipt:cardpoint">


    <!--
    ���� �ֹ���ȣ : �������Ա� ����(������� ��ü),��ȭ���� ���� �ʼ��ʵ�� �ݵ�� ������ �ֹ���ȣ�� �������� �߰��ؾ� �մϴ�.
    �������� �߿� ���� ������ü �̿� �ÿ��� �ֹ� ��ȣ�� ��������� ��ȸ�ϴ� ���� �ʵ尡 �˴ϴ�.
    ���� �ֹ���ȣ�� �ִ� 40 BYTE �����Դϴ�.
    ����:���� �ѱ۰��� �Է��Ͻø� �ȵ˴ϴ�.
    -->
<!--    <input type=hidden name=oid size=40 value="oid_1234567890">-->


    <!--
    �÷����� ���� ��� ���� �ΰ� �̹��� ���
    �̹����� ũ�� : 90 X 34 pixels
    �÷����� ���� ��ܿ� ���� �ΰ� �̹����� ����Ͻ� �� ������,
    �ּ��� Ǯ�� �̹����� �ִ� URL�� �Է��Ͻø� �÷����� ��� �κп� ���� �̹����� �����Ҽ� �ֽ��ϴ�.
    -->
    <!--input type=hidden name=ini_logoimage_url  value="http://[����� �̹����ּ�]"-->

    <!--
    ���� �����޴� ��ġ�� �̹��� �߰�
    �̹����� ũ�� : ���� ���� ���� - 91 X 148 pixels, �ſ�ī��/ISP/������ü/������� - 91 X 96 pixels
    ���� �����޴� ��ġ�� �̹����� �߰��Ͻ� ���ؼ��� ��� ������ǥ���� ��뿩�� ����� �Ͻ� ��
    �ּ��� Ǯ�� �̹����� �ִ� URL�� �Է��Ͻø� �÷����� ���� �����޴� �κп� �̹����� �����Ҽ� �ֽ��ϴ�.
    -->
    <!--input type=hidden name=ini_menuarea_url value="http://[����� �̹����ּ�]"-->

    <!--
    �÷����ο� ���ؼ� ���� ä�����ų�, �÷������� �����ϴ� �ʵ��
    ����/���� �Ұ�
    uid �ʵ忡 ����� ������ ���� ���� �ʵ��� �Ͻñ� �ٶ��ϴ�.
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