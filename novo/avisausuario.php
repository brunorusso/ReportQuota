<?php

$usuario = $HTTP_POST_VARS['email'];
$usado = $HTTP_POST_VARS['usado'];
$tamanho = $HTTP_POST_VARS['tamanho'];
$status = $HTTP_POST_VARS['status'];

$reserva = (($tamanho*20)/100)+$tamanho;


$msg = "$usuario\n";
$msg .= "Você atingiu o limite de $status % de utilização de espaço no Servidor de e-mail\n";
$msg .= "É necessário que você efetue uma limpeza imediatamente!\n";
$msg .= "\n\n";
$msg .= "OBSERVAÇÃO\n";
$msg .= "Espaço Disponível: $tamanho Mb\n";
$msg .= "Espaço Utilizado: $usado Mb\n";
$msg .= "Espaço Reserva (até): $reserva Mb\n";
$msg .= "\n\n";
$msg .= "Atenciosamente,\n";
$msg .= "TI - Tecnologia da Informação";

$email = "$usuario@kallan.com.br";

mail("$email", "Espaco Excedido", "$msg", "From:bruno.russo@kallan.com.br");
print("<script language='javascript'>
	alert('E-mail enviado ao usuário!');
	location=href='index.php'
</script>");
?>

