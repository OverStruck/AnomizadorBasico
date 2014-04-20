<?php
/*
Version 1.01 4/10/2014

Lo que vas a querer editar es la variable $HTML
Esta variable tiene el codigo HTML que se mostrara en la pagina

IMPORTANTE!
Esta variable ($servidor) contiene la URL correcta, generada dinamicamente,
del anomizador. Ejem: http://www.Mi-Web.com/capeta/anomizador.php

Si sabes lo que haces, puedes modificarla
*/

$servidor    = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$redirectURL = $_GET['p'];   //url codificada
$encodeURL   = $_GET['url']; //url a codificar

// Primero comprobamos que tengamos el parametro "p" de "pagina"
if (isValid_Param($redirectURL))
{
	$redirectURL = base64_decode($redirectURL); //Decodifica la URL
	//comprueba que sea una URL valida
	if (isValid_URL($redirectURL))
	{
		//ATENCION
		//Esta linea es CRITICA, es lo que hace que la URL sea "anomizada"
		header('Refresh: 3; url=' . $redirectURL);
		/*********************************************************
		Aqui pon el mensaje de espera. Dentro de la variable $HTML
		*********************************************************/
		$HTML = '<p>Por favor espere 3 segundos</p>';
	}
	else
	{
		// Este codigo solo se ejecuta si por alguna razon la url no es valida
		// Puedes poder aqui cualquier mensaje de error
		header('Refresh: 3; url=' . $servidor);
		$HTML = '<h1>ERROR. URL invalida.</h1>';
	}
}
else if (isValid_Param($encodeURL)) // si no tenemos el parametro "p", hay que comprobar si el parametro "url" existe
{
	/*
		Ahora comprobaremos que la URL tenga "HTTP" o "HTTPS" al principio.
		Si no lo tiene, se lo agregaremos para que sea valida
	*/
	if (!strpos($encodeURL, '://'))
    	$encodeURL = 'http://' . $encodeURL;

	if (isValid_URL($encodeURL))
	{
		//codifica la URL
		$encodeURL = base64_encode($encodeURL);
		$encodeURL = $servidor . '?p=' . $encodeURL;

		/*********************************************************
		Aqui pon la forma con la URL anonimizada. La variable
		$url tiene la URL ya codificada
		*********************************************************/
		$HTML      = '
		<form>
		<b>Enlace directo:</b><br />
		<input size="60" type="text" value="' . $encodeURL . '"><br />
		<br /><b>Enlace HTML para sitios web y blogs:</b><br />
		<textarea rows="4" cols="50"><a href="' . $encodeURL . '" title="Enlace anonimo" target="_blank">Enlace anonimizado!</a></textarea><br />
		<br /><b>Enlace Para foros (bbcode):</b><br />
		<textarea rows="4" cols="50">[url=' . $encodeURL . ']Enlace anonimo[/url]</textarea><br />
		</form><br /><br />
		<a href="' . $encodeURL . '" target="_blank">Probar enlace</a>
		';
	}
	else
	{
		// De nuevo, este codigo solo se ejecuta si la url es invalida
		header('Refresh: 5; url=' . $servidor);
		$HTML = '<h1>ERROR. URL invalida.</h1>';
	}
}
else
{
	// Si los parametros "p" y "url" no estan precentes, entonces significa que el
	// user esta en la pagina directamente, aqui mostraremos la forma para que el
	// user pueda escribir la URL que quiere anomizar.

	/*********************************************************
	Aqui pon la forma en donde el user pondrea la URL que
	quiere anonimizar. Recuerda, dentro de la variable $HTML
	*********************************************************/
	$HTML = '
	<form action="" method="get">
	<b>Ingrese una direcci&oacute;n</b><br />
	<input size="60" type="text" name="url">
	<input type="submit" value="Anonimizar!">
	</form>
	';
}
/*
Fin del script.
Abajo solo esta codigo HTML5 muy basico
Puedes modificarlo a tu gusto

El codigo de la variable HTML saldra dentro de las etiquetas BODY
*/
?>

<!DOCTYPE html>
<html>
<head>
	<title>Anomizador</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
// html generado dinamicamente dependiendo de lo que se ocupe
echo $HTML;
?>
</body>
</html>

<?php
//funciones del script
function isValid_Param($p)
{
	return isset($p) && !empty($p);
}

function isValid_URL($url)
{
	return preg_match("/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/", $url);
}
?>