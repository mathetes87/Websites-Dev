<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Preuniversitario</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<link href="forma.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
    <div id="centrar">
        <div id="loginbox">
            <h3>Ingresa con tu RUT y clave:</h3>
            <form name="form1" method="post" action="checklogin.php">
                <div>
                    <label>RUT</label>
                    <span><input name="myusername" type="text" id="myusername"></span>
                </div>

                <div>
                    <label>Contrase&ntilde;a</label>
                    <span><input name="mypassword" type="password" id="mypassword"></span>
                </div>

                <div>
                    <input id="botonLogin" type="submit" name="Submit" value="Entrar">
                </div>
            </form>
        </div>
    </div>
</body>