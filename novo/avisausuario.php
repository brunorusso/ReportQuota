<?php

$usuario = $HTTP_POST_VARS['email'];
$usado = $HTTP_POST_VARS['usado'];
$tamanho = $HTTP_POST_VARS['tamanho'];
$status = $HTTP_POST_VARS['status'];

$reserva = (($tamanho*20)/100)+$tamanho;


$msg = "$usuario\n";
$msg .= "Voc� atingiu o limite de $status % de utiliza��o de espa�o no Servidor de e-mail\n";
$msg .= "� necess�rio que voc� efetue uma limpeza imediatamente!\n";
$msg .= "\n\n";
$msg .= "OBSERVA��O\n";
$msg .= "Espa�o Dispon�vel: $tamanho Mb\n";
$msg .= "Espa�o Utilizado: $usado Mb\n";
$msg .= "Espa�o Reserva (at�): $reserva Mb\n";
$msg .= "\n\n";
$msg .= "Atenciosamente,\n";
$msg .= "TI - Tecnologia da Informa��o";

$email = "$usuario@kallan.com.br";

mail("$email", "Espaco Excedido", "$msg", "From:bruno.russo@kallan.com.br");
print("<script language='javascript'>
	alert('E-mail enviado ao usu�rio!');
	location=href='index.php'
</script>");
?>

