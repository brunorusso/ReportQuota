<?php
/*
Inclui o arquivo de cabecalhos
*/
include 'includes/header.inc.php';
include 'includes/config.php';

/*
Executa o repquota pegando a quota de todos os usuï¿½ios
*/
$verifica_quota = shell_exec("/usr/sbin/repquota -au");

/*
Quebra a saida do comando repquota em um array
*/
$keywords = preg_split("/[\s,]+/", "$verifica_quota");

/*
Conta o total de linhas do array criado anteriormente
*/
$tot = count($keywords);

/*
Cria dois arrays que serï¿½ utilizados para armazenar as informacoes de cada usuario
*/
//$usuario_normal = array();
$usuario_normal_desc = array();
//$usuario_acima = array();
$usuario_acima_desc = array();

/*
A variavel j, a, n e utilizada para controle
*/
$j=0;
$a=0;
$n=0;
/*
Inicia o for a partir do array 38
*/
for ($i=38; $i<=$tot;)
{
	/*
	Seleciona o Nome do usuario
	*/
	$nome = ("$keywords[$i]");
	$i++;

	/*
	Verifica se o parametro passado e o nome mesmo caso seja null sai do for
	*/
	if ($nome == "")
	{
		break;
	}
	
	
	/*
	Verifica se o usuï¿½io esta usando acima do seu espaco
	*/
	$grace = ("$keywords[$i]");
	$i++;
	$grace = substr($grace, 0, 1);
	//////////////////print("$grace");
	if ($grace == "+")
	{
		/*
		Usuario esta usando acima do seu limite
		*/
		/*
	        Seleciona o Espaco Usado e converte para Mb
        	*/
   	     	$usado = ("$keywords[$i]");
        	$usado = round($usado/1024, 2);
        	$i++;
        	/*
	        Seleciona o Espaï¿½ Disponivel para o usuario e converte para Mb
        	*/
        	$tamanho = ("$keywords[$i]");
        	$tamanho = round($tamanho/1024, 2);
        	$i++;
        	/*
	        Seleciona o Espaï¿½ Adicional para o usuario e converte para Mb
        	*/
		$adicional = ("$keywords[$i]");
        	$adicional = round($adicional/1024, 2);
        	$i++;
		/*
                Seleciona o periodo restante para o Espaï¿½ Adicional
                */
                $tempo_adicional = ("$keywords[$i]");
                $i++;
                $i++;
                $i++;
                $i++;
		/*
		Efetua o calculo do % usado
		*/
		$status = calcula_status($usado, $tamanho);
	 	/*
		Grava os valores recebidos em um array
		*/
		$usuario_acima_desc[$a] = $nome;
		$a++;
		$usuario_acima_desc[$a] = $usado;
		$a++;
		$usuario_acima_desc[$a] = $tamanho;
		$a++;
		$usuario_acima_desc[$a] = $adicional;
		$a++;
		$usuario_acima_desc[$a] = $tempo_adicional;
		$a++;
		$usuario_acima_desc[$a] = $status;
		$a++;
	}else{
               /*
                Usuario NAO esta usando acima do seu limite
                */
                /*
                Seleciona o Espaco Usado e converte para Mb
                */
                $usado = ("$keywords[$i]");
                $usado = round($usado/1024, 2);
                $i++;
                /*
                Seleciona o Espaï¿½ Disponivel para o usuario e converte para Mb
                */
                $tamanho = ("$keywords[$i]");
                $tamanho = round($tamanho/1024, 2);
                $i++;
                /*
                Seleciona o Espaï¿½ Adicional para o usuario e converte para Mb
                */
                $adicional = ("$keywords[$i]");
                $adicional = round($adicional/1024, 2);
                $i++;
                $i++;
                $i++;
                $i++;
                /*
                Efetua o calculo do % usado
                */
                $status = calcula_status($usado, $tamanho);
		/*
                Grava os valores recebidos em um array
                */
                $usuario_normal_desc[$n] = $nome;
                $n++;
                $usuario_normal_desc[$n] = $usado;
                $n++;
                $usuario_normal_desc[$n] = $tamanho;
                $n++;
                $usuario_normal_desc[$n] = $adicional;
                $n++;
                $usuario_normal_desc[$n] = $status;
                $n++;
	}
$j++;
}

/*
Desenha a tela para usuarios acima
*/
print("<table width=75% border=0 cellpading=0 cellspacing=0 align=center>");
cabecalho("Usuários acima do permitido", "8");
print("<tr bgcolor=D3DCE3>");
        print("<td><b>Usuário</b></td>");
        print("<td><b><center>Utilizado</center></b></td>");
        print("<td><b><center>Disponível</center></b></td>");
        print("<td><b><center>Tempo</center></b></td>");
        print("<td><b><center>Status</center></b></td>");
        print("<td><b><center>Avisar</center></b></td>");
print("</tr>");
exibe_usuarios_acima($usuario_acima_desc);
exibe_total_usuarios($usuario_acima_desc, 6);
print("</table>");

/*
Desenha a tela para usuarios 
*/
print("<table width=75% border=0 cellpading=0 cellspacing=0 align=center>");
cabecalho("Relação de Usuários", "7");
print("<tr bgcolor=D3DCE3>");
        print("<td><b>Usuário</b></td>");
        print("<td><b><center>Utilizado</center></b></td>");
        print("<td><b><center>Disponível</center></b></td>");
        print("<td><b><center>Status</center></b></td>");
        print("<td><b><center>Avisar</center></b></td>");
print("</tr>");
exibe_usuarios($usuario_normal_desc);
exibe_total_usuarios($usuario_normal_desc, 5);
print("</table>");



//Funcoes utilizadas pelo sistema
function status_cor($status)
{
	if($status <= "60") 	
	{
		#verde
		$cor="1EA22B";
		return $cor;
	}
        if($status > "60" and $status <= "70")
        {
		#amarelo
                $cor="E3DD24";
                return $cor;
        }
        if($status > "70" and $status <= "90")
        {
		#laranja
        	$cor="E37E20";
        	return $cor;
        }
        if($status > "90" and $status <= "100")
        {
		#vermelho
        	$cor="FF0000";
        	return $cor;
        }
        if($status > "100")
        {
                #Azulado
                $cor="8894BF";
                return $cor;
        }
}

function calcula_status($usado, $tamanho)
{
	return round(($usado*100)/$tamanho, 2);
}

function cabecalho($titulo, $tamanho)
{
	print("<tr>");
        	print("<td colspan=$tamanho bgcolor=EAE6B8><center>$titulo</center></td>");
	print("</tr>");
	
}

function exibe_total_usuarios($usuario_acima_desc, $numero)
{
        $total = count($usuario_acima_desc)/$numero;
	print("<tr>");
        	print("<td colspan=$tamanho bgcolor=EAE6B8><center>Total de usuários $total</center></td>");
	print("</tr>");
}

function exibe_usuarios_acima($usuario_acima_desc)
{
        $total = count($usuario_acima_desc);
	$k=0;
	for($k=0; $k < $total;)
	{
        	$nome = $usuario_acima_desc[$k];
		$k++;
        	$utilizado = $usuario_acima_desc[$k];
		$k++;
        	$disponivel = $usuario_acima_desc[$k];
		$k++;
		$k++;
        	$tempo = $usuario_acima_desc[$k];
		$k++;
        	$status = $usuario_acima_desc[$k];
		$k++;
		$fundo_linha = status_cor($status);
                $cor = $fundo_linha;
 		print("<tr>");
                print("<td bgcolor=$fundo_linha>$nome</td>");
                print("<td bgcolor=$fundo_linha><center>$utilizado Mb</center></td>");
                print("<td bgcolor=$fundo_linha><center>$disponivel Mb</center></td>");
                print("<td bgcolor=$fundo_linha><center>$tempo</center></td>");
                print("<td bgcolor=$cor><center>$status %</center></td>");
                print("<td bgcolor=$fundo_linha>");
                        print("<form name=aviso method=POST action=avisausuario.php>");
                                print("<input type=hidden name=email value=$nome>");
                                print("<input type=hidden name=usado value=$utilizado>");
                                print("<input type=hidden name=tamanho value=$disponivel>");
                                print("<input type=hidden name=status value=$status>");
                                print("<center><input type=submit value=Avisar></center>");
                        print("</form>");
                print("</td>");
		print("</tr>");
	}
return ;	
}

function exibe_usuarios($usuario_acima)
{
        //print_r($usuario_acima);
	$total = count($usuario_acima);
        $k=0;
	$b = 0;
        for($k=0; $k < $total;)
        {
                $nome = $usuario_acima[$k];
		$quota[$b][0] = $nome;
                $k++;
                $utilizado = $usuario_acima[$k];
		$quota[$b][1] = $utilizado;
                $k++;
                $disponivel = $usuario_acima[$k];
		$quota[$b][2] = $disponivel;
                $k++;
                $k++;
                $status = $usuario_acima[$k];
		$quota[$b][3] = $status;
                $k++;
		$b++;
       }
	sort($quota);

	for($k=0; $k < $b;)
        {
                $nome = $quota[$k][0];
                $utilizado = $quota[$k][1];
                $disponivel = $quota[$k][2];
                $status = $quota[$k][3];
		$k++;
		$fundo_linha = status_cor($status);
		$cor = $fundo_linha;
	
                print("<tr>");
                print("<td bgcolor=$fundo_linha>$nome</td>");
                print("<td bgcolor=$fundo_linha><center>$utilizado Mb</center></td>");
                print("<td bgcolor=$fundo_linha><center>$disponivel Mb</center></td>");
                print("<td bgcolor=$cor><center>$status %</center></td>");
                print("<td bgcolor=$fundo_linha>");
                        print("<form name=aviso method=POST action=avisausuario.php>");
                                print("<input type=hidden name=email value=$nome>");
                                print("<input type=hidden name=usado value=$utilizado>");
                                print("<input type=hidden name=tamanho value=$disponivel>");
                                print("<input type=hidden name=status value=$status>");
                                print("<center><input type=submit value=Avisar></center>");
                        print("</form>");
                print("</td>");
                print("</tr>");
 	}
return ;
}

function linha($fundo)
{
	$calculo = $fundo/2;
	if (is_int($fundo/2))
	{
		$cor_fundo="e2e2e2";
		return $cor_fundo;
	}else{
		$cor_fundo="d3dce3";
		return $cor_fundo;
	}
}

function tela()
{
//Desenha a tela
print("<table width=75% border=0 cellpading=0 cellspacing=0 align=center>");
print("<tr>");
        print("<td colspan=6 bgcolor=EAE6B8><center>Usuï¿½ios com espaï¿½ em perï¿½do de GRAï¿½</center></td>");
print("</tr>");
print("<tr bgcolor=D3DCE3>");
        print("<td><b>Usuï¿½io</b></td>");
        print("<td><b><center>Utilizado</center></b></td>");
        print("<td><b><center>DisponÃ­el</center></b></td>");
        print("<td><b><center>Restam</center></b></td>");
        print("<td colspan=2><b><center>Status</center></b></td>");
print("</tr>");

        print("<tr>");
               print("<td bgcolor=$fundo_linha>$nome</td>");
                print("<td bgcolor=$fundo_linha><center>$usado Mb</center></td>");
                print("<td bgcolor=$fundo_linha><center>$tamanho Mb</center></td>");
                print("<td bgcolor=$fundo_linha><center>$dias</center></td>");
                print("<td bgcolor=$cor><center>$status %</center></td>");
                print("<td bgcolor=$fundo_linha>");
                        print("<form name=aviso method=POST action=avisausuario.php>");
                                print("<input type=hidden name=email value=$nome>");
                                print("<input type=hidden name=usado value=$usado>");
                                print("<input type=hidden name=tamanho value=$tamanho>");
                                print("<input type=hidden name=status value=$status>");
                                print("<center><input type=submit value=Avisar></center>");
                        print("</form>");
                print("</td>");
        print("</tr>");
}
print("<table width=75% border=0 align=center>");
print("<tr>");
print("<td>");
print("<center>ReportQuota v. $versao de $atualizacao<br>");
print("Para maiores informaï¿½es visite: <a href=$link>ReportQuota</a></center>");
print("</td>");
print("</tr>");
print("</table>");


?>

