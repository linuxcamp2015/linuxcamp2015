<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>register</title>
<style type="text/css">
#signup {
        width: 350px;
        height: 500px;
        margin: 100px auto 50px auto;
        padding: 20px;
        position: relative;
        background: #fff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAECAMAAAB883U1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAlQTFRF7+/v7u7u////REBVnAAAAAN0Uk5T//8A18oNQQAAABZJREFUeNpiYGJiYmBiYgRiBhAGCDAAALsAFJhiJ+UAAAAASUVORK5CYII=);
        border: 1px solid #ccc;
        border-radius: 3px;
}


.form-control {
 display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.428571429;
  color: #555555;
  vertical-align: middle;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
          transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
}
.radio {
  display: inline-block;
  min-height: 20px;
  padding-left: 20px;
  margin-top: 10px;
  margin-bottom: 10px;
  vertical-align: middle;


}
.button
{
        margin: 20px 0 0 0;
        padding: 15px 8px;
        width: 45%;
        cursor: pointer;
        border: 1px solid #2493FF;
        overflow: visible;
        display: inline-block;
        color: #fff;
        font: bold 1.4em arial, helvetica;
        text-shadow: 0 -1px 0 rgba(0,0,0,.4);
        background-color: #2493ff;
        background-image: linear-gradient(top, rgba(255,255,255,.5), rgba(255,255,255,0));
        transition: background-color .2s ease-out;
        border-radius: 3px;
        box-shadow: 0 2px 1px rgba(0, 0, 0, .3), 0 1px 0 rgba(255, 255, 255, .5) inset;
}
</style>
</head>
<body>
<FORM  ACTION="INIsecurestart.php" METHOD=get align="center" id="signup">
        <input class="form-control" type="text" placeholder="이름" style="width: 350px; 
                 margin-top: 10px;" name="regname"></input><br>
        <input class="form-control" type="text" placeholder="휴대폰 번호 ( -없이)" style="width: 350px;
                margin-top: 10px;" name="regphone"></input><br>
        <input class="form-control" type="text" placeholder="이메일주소" style="width: 350px;
                margin-top: 10px;" name="regemail"></input><br>    
	<div class="radio" style="width: 150px; display: inline-block; 
                margin: 10px 0 10px 10px">
        <label><input type="radio" name="regpos" value="1000" checked> 학생 (500,000원)
        </input></label></div>        
	<div class="radio" style="width: 150px; display: inline-block; 
                margin: 10px 0 10px 10px;">        
	<label><input type="radio" name="regpos" value="900000"> 일반 (900,000원)<br>
        </input></label></div>
        
        <br><input class="form-control" type="text" placeholder="학교명/직장명" style="width: 350px;
                margin-top: 10px;" name="regworkplace"></input><br>
        <input class="form-control" type="text" placeholder="지도교수" style="width: 350px; 
                margin-top: 10px;" name="regpfname"></input><br>
&nbsp;&nbsp;&nbsp;&nbsp;<input class="button" type="submit" value="등록"></input>
<input class="button" type="reset" value="다시 작성"></input>
</FORM>
</body>
</html>
