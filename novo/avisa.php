#!/usr/bin/php4 -q
<?php

//Executa o repquota
$verifica_quota = shell_exec("/usr/sbin/repquota -avug | grep days");

$num_aviso = 0;

//Quebra a saida do comando anterior em um array
$keywords = preg_split("/[\s,]+/", "$verifica_quota");

//Conta o total de linhas do array criado anteriormente
$tot = count($keywords);

//print_r($keywords);

for ($i=8; $i<=$tot; $i++)
{
	//Nome do usu�rio
	$nome = ("$keywords[$i]");
	$i++;
	$i++;
	//Espaco Usado
	$usado = ("$keywords[$i]");
	$usado = round($usado/1024, 2);
	$i++;
	//Tamanho	
	$tamanho = ("$keywords[$i]");
	$tamanho = round($tamanho/1024, 2);
	$i++;
	$i++;
	$i++;
	$i++;
	$i++;
	//Calcula o Status
	$status=0;
	if (!$usado==0)
	{
		$status = round(($usado*100)/$tamanho, 2);
	}

	if($status >= "90")
 	{	
	
	$reserva = (($tamanho*20)/100)+$tamanho;

	$msg = "$nome\n";
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

	$email = "$nome@kallan.com.br";

	mail("$email", "Espaco Excedido", "$msg");
	$num_aviso++;	
	}
}



//Executa o repquota
$verifica_quota = shell_exec("/usr/sbin/repquota -avug | grep -v days");


//Quebra a saida do comando anterior em um array
$keywords = preg_split("/[\s,]+/", "$verifica_quota");

//Conta o total de linhas do array criado anteriormente
$tot = count($keywords);

//print_r($keywords);

for ($i=30; $i<=$tot; $i++)
{
	//Nome do usu�rio
	$nome = ("$keywords[$i]");
	$i++;
	$i++;
	//Espaco Usado
	$usado = ("$keywords[$i]");
	$usado = round($usado/1024, 2);
	$i++;
	//Tamanho	
	$tamanho = ("$keywords[$i]");
	$tamanho = round($tamanho/1024, 2);
	$i++;
	$i++;
	$i++;
	$i++;
        //Calcula o Status
        $status=0;
        if (!$usado=="0")
        {
                $status = round(($usado*100)/$tamanho, 2);
        }
	
        if($status >= "90")
        {

        $reserva = (($tamanho*20)/100)+$tamanho;

        $msg = "$nome\n";
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

        $email = "$nome@kallan.com.br";

        mail("$email", "Espaco Excedido", "$msg", "From:bruno.russo@kallan.com.br");
        $num_aviso++;
        }

}

?>

