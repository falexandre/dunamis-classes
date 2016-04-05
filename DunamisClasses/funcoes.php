<?php 

namespace DunamisClasses;

class funcoes 
{
	/**
	 * Retorna a distancia entro 2 pontos de latitude e longitude
	 * @param  [type] number $p1LA [description] latitude inicial
	 * @param  [type] number $p1LO [description] longitude inicial
	 * @param  [type] number $p2LA [description] latidude final
	 * @param  [type] number $p2LO [description] longitude final
	 * @return [type] number      [description] metros
	 */
	public static function distanciaPontosGPS($p1LA, $p1LO, $p2LA, $p2LO , $formato = 'M') {
			$r     = 6371.0;	       
			$p1LA  = $p1LA * pi() / 180.0;
			$p1LO  = $p1LO * pi() / 180.0;
			$p2LA  = $p2LA * pi() / 180.0;
			$p2LO  = $p2LO * pi() / 180.0;	       
			$dLat  = $p2LA - $p1LA;
			$dLong = $p2LO - $p1LO;	       
			$a     = 	sin($dLat / 2) * sin($dLat / 2) 
						+ cos($p1LA) * cos($p2LA) 
						* sin($dLong / 2) * sin($dLong / 2);
			$c     = 2 * atan2(sqrt($a), sqrt(1 - $a));	       
	    return ($formato == 'M') ? round($r * $c * 1000) : round($r * $c); // resultado em metros.
	}

	
	
	public static function urlSeo($str){

		$str = strtolower(utf8_decode($str)); $i=1;
	    $str = strtr($str, utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÝÝŸàáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyyaaaaaaaceeeeiiiinoooooouuuyyy');
	    $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
	    while($i>0) $str = str_replace('--','-',$str,$i);
	    if (substr($str, -1) == '-') $str = substr($str, 0, -1);
	    return $str;

	}


	/**
	 * Verifica o acesso HTTP da Api
	 * @return [Exception] throw
	 */
	public static function apiVerifyAcess(){

		$user = $_SERVER[ 'PHP_AUTH_USER'    ];
		$pass = $_SERVER[ 'PHP_AUTH_PW'      ];

		if( defined(LOGINAPI) and defined(SENHAAPI) ){
			if(  $user != LOGINAPI and $pass != SENHAAPI ){
			 throw new Exception("Erro de autenticação HTTP, acesso não autorizado!");
			}
		}


	}


	/**
	*
	*Fução que limpa a string e deixa somente numeros
	*
	*/
	public static function soNumeros($string) {
	    return preg_replace('/[^0-9]/', "", $string);
	}



	/*************************
	Formata CNPJ para exibição
	*************************/

	public static function formataCNPJ($v) {
	    $v = self::soNumeros($v);

	    $ret = substr($v, 0, 2);
	    $sub = substr($v, 2, 3);
	    if($sub){
	        $ret .= '.'.$sub;

	        $sub = substr($v, 5, 3);
	        if($sub){
	            $ret .= '.'.$sub;

	            $sub = substr($v, 8, 4);
	            if($sub){
	                $ret .= '/'.$sub;

	                $sub = substr($v, 12, 2);
	                if($sub){
	                    $ret .= '-'.$sub;
	                }
	            }
	        }
	    }

	    return $ret;
	}




	/*************************
	Valida CNPJ para gravação
	*************************/

	public static function validaCNPJ( $cnpj = null ) {


	$cnpj = str_pad(str_replace(array('.','-','/'),'',$cnpj),14,'0',STR_PAD_LEFT);
	if (strlen($cnpj) != 14){
	return false;
	}else{
	for($t = 12; $t < 14; $t++){
	for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++){
	$d += $cnpj{$c} * $p;
	$p = ($p < 3) ? 9 : --$p;
	}
	$d = ((10 * $d) % 11) % 10;
	if($cnpj{$c} != $d){
	return false;
	}
	}
	return true;
	}


	}







	/*************************
	Formata CPF para exibição
	*************************/

	public static function formataCPF($v) {
	    $v = self::soNumeros($v);

	    $ret = substr($v, 0, 3);
	    $sub = substr($v, 3, 3);
	    if($sub){
	        $ret .= '.'.$sub;

	        $sub = substr($v, 6, 3);
	        if($sub){
	            $ret .= '.'.$sub;

	            $sub = substr($v, 9, 2);
	            if($sub){
	                $ret .= '-'.$sub;
	            }
	        }
	    }

	    return $ret;
	}





	/*************************
	Valida CPF para gravar
	*************************/

	public static function validaCPF( $cpf = null ) {

	 
	    // Verifica se um número foi informado
	    if(empty($cpf)) {
	        return false;
	    }
	 
	    // Elimina possivel mascara
	    $cpf = preg_replace('/[^0-9]/', "", $cpf);
	    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	     
	    // Verifica se o numero de digitos informados é igual a 11
	    if (strlen($cpf) != 11) {

	return false;

	    // Verifica se nenhuma das sequências invalidas abaixo
	    // foi digitada. Caso afirmativo, retorna falso
	    } else if (
	$cpf == '00000000000' ||
	        $cpf == '11111111111' ||
	        $cpf == '22222222222' ||
	        $cpf == '33333333333' ||
	        $cpf == '44444444444' ||
	        $cpf == '55555555555' ||
	        $cpf == '66666666666' ||
	        $cpf == '77777777777' ||
	        $cpf == '88888888888' ||
	        $cpf == '99999999999') {
	        return false;
	     // Calcula os digitos verificadores para verificar se o
	     // CPF é válido
	     } else {
	         
	        for ($t = 9; $t < 11; $t++) {
	             
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
	 
	        return true;
	    
	}



	}






	/*************************
	Valida Dodumento CPF e CNPJ
	*************************/

	public static function validaDOC( $doc = null ) {

	$numeros = strlen($doc);
	if ($numeros == 11):
	return self::validaCPF($doc);
	elseif ($numeros == 14):
	return self::validaCNPJ($doc);
	else:
	return false;
	endif;

	}





	/*************************
	Formata Dodumento CPF e CNPJ
	*************************/

	public static function formataDOC( $doc = null ) {

	$numeros = strlen($doc);
	if ($numeros == 11):
	return self::formataCPF($doc);
	elseif ($numeros == 14):
	return self::formataCNPJ($doc);
	else:
	return false;
	endif;

	}

	/*************************
	Valida campo vazio recebendo array, caso algum campo
	esteja vazio retorna false
	*************************/

	public static function camposVazio( $campos ) {

	$erro = null;
	foreach( $campos as $val ):
	if(empty($val)): $erro++;	endif;
	endforeach;

	return ( empty($erro) ) ? true : false;
	}





	/*************************
	Formata CEP para exibição
	*************************/

	public static function formataCEP($v) {
	    $v = self::soNumeros($v);
	    if(((int) $v) == 0){
	        return '';
	    }
	    $ret = substr($v, 0, 5);
	    $sub = substr($v, 5, 3);
	    if($sub){
	        $ret .= '-'.$sub;
	    }
	    return $ret;
	}





	/*************************
	Formata DATA para exibição
	*************************/


	public static function formataDATA( $data , $hora = true ) {

	if($hora){
	$data = strtotime($data);
	$new_date = date('d/m/Y H:i:s', $data);
	}else{
	$data = strtotime($data);
	$new_date = date('d/m/Y', $data);
	}
	return $new_date;
	}




	/*************************
	Formata DATA para gravar
	*************************/


	public static function gravarDATA( $data , $hora = true ) {

	$data = implode ('-', explode('/',$data));
	if($hora){
	$data = strtotime($data);
	$new_date = date('Y-m-d H:i:s', $data);
	}else{
	$data = strtotime($data);
	$new_date = date('Y-m-d', $data);
	}
	return $new_date;
	}






	/*************************
	Retorna o valor em reais
	*************************/


	public static function valorReais($valor) {
	//$valor = str_replace(".", ",",$valor);
	return number_format($valor,2,',','.');
	  
	}





	/*************************
	Retorna o valor para gravar no banco de dados
	*************************/


	public static function gravarValor($valor) {
	$valor = str_replace(".", "",$valor);
	$valor = str_replace(",", ".",$valor);
	return	$valor;
	  
	}





	/*************************
	Retorna o valor em reais por extenso
	*************************/


	public static function valorPorExtenso($valor=0) {

	    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

	    $rt = '';

	    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
	    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
	    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

	    $z = 0;

	    $valor = number_format($valor, 2, ".", ".");
	    $inteiro = explode(".", $valor);
	    for($i = 0; $i < count($inteiro); $i++)
	        for($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
	            $inteiro[$i] = "0".$inteiro[$i];

	    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
	    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
	    for($i = 0; $i < count($inteiro); $i++){
	        $valor = $inteiro[$i];
	        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
	        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
	        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

	        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
	        $t = count($inteiro) - 1 - $i;
	        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
	        if($valor == "000")
	            $z++; elseif($z > 0)
	            $z--;
	        if(($t == 1) && ($z > 0) && ($inteiro[0] > 0))
	            $r .= ( ($z > 1) ? " de " : "").$plural[$t];
	        if($r)
	            $rt = $rt.((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ").$r;
	    }

	    return($rt ? $rt : "zero");
	}





	/**
	*
	* Retorna o nome do mes em extenso
	*
	* @param integer $mes O numero do mes
	* @return string Uma string com o nome do mes
	*/

	public static function stringMes($mes){
	    
	$mes = trim($mes);

	switch ($mes){
	        case 1	: $mes = 'Janeiro'; break;
	        case 01	: $mes = 'Janeiro';	break;
	        case 2	: $mes = 'Fevereiro'; break;
	        case 02	: $mes = 'Fevereiro'; break;
	        case 3	: $mes = 'Março'; break;
	        case 03	: $mes = 'Março'; break;
	        case 4	: $mes = 'Abril'; break;
	        case 04	: $mes = 'Abril'; break;
	        case 5	: $mes = 'Maio'; break;
	        case 05	: $mes = 'Maio'; break;
	        case 6	: $mes = 'Junho'; break;
	        case 06	: $mes = 'Junho'; break;
	        case 7	: $mes = 'Julho'; break;
	        case 07	: $mes = 'Julho'; break;
	        case 8	: $mes = 'Agosto'; break;
	        case 08	: $mes = 'Agosto'; break;
	        case 9	: $mes = 'Setembro'; break;
	        case 09	: $mes = 'Setembro'; break;
	        case 10	: $mes	= 'Outubro'; break;
	        case 11	: $mes	= 'Novembro'; break;
	        case 12	: $mes	= 'Dezembro'; break;
	        default : $mes	= 'Mês inexistente';	break;
	    }
	    return $mes;
	}







	/**
	* Retorna o mês abreviado.
	* @param int $mes Mês.
	* @return string Mês abreviado.
	*/
	public static function stringMesAbreviado($mes){
	    $mesExtenso = self::stringMes((int)$mes);
	    if ($mesExtenso){
	        return substr($mesExtenso, 0, (($mes == 5)?4:3) );
	    }else{
	        trigger_error('Mês inválido');
	        return '';
	    }
	}





	/*************************
	Limita string para abreviação
	*************************/


	public static function limitarString($str, $limitar = 100, $limpar = true){
	if($limpar == true){$str = strip_tags($str);}
	if(strlen($str) <= $limitar ){ return $str;}
	return substr($str, 0, strrpos(substr($str,0,$limitar), ' ')) . ' [...]';
	}



	/*************************
	Gera senha aleatório
	*************************/

	public static function geraSenha($length = 6 , $campos = "aeiouybdghjmnpqrstvz0123456789") {
	return substr(str_shuffle($campos), 0, $length);
	}





	/*************************
	Formata Numero Telefone
	*************************/

	public static function formataTEL($string) {
	$string	= self::soNumeros($string);
	return $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);

	}








	/*************************
	Formata Link para clique correto
	*************************/

	public static function formataLINK($string) {

	$inicio = substr($string, 0 , 7);
	$format = ($inicio == "http://") ? $string : "http://" . $string;
	return (substr($format , 0 , 15) == "http://https://") ? substr($format , 7) : $format;

	}




	/**
	* Retorna o código de incorporação do video
	* @param int $width largura do embed
	* @param int $height altura do embed
	* @return String Código embed do vídeo
	*/
	public static function youTube($link,$iframe=true){
	$cod_video = explode('watch?v=', $link);
	$cod_video = is_numeric($cod_video[1]) ? $cod_video[1] : reset( explode('&', $cod_video[1]) );
	$video = "<iframe class=\"embed-responsive-item\" src=\"http://www.youtube.com/embed/$cod_video\" frameborder=\"0\" allowfullscreen></iframe>";
	return $iframe ? $video : $cod_video;
	}




	/**
	* Retorna a a saldação para o cliente conforme horário
	*/
	public static function exibeSadacao(){
	// verifica a hora para saudação
	$hora	= date("H");
	$saudacao="";
	// saudação conforme hora
	if($hora >= 0 && $hora < 6){
	$saudacao	= "BOA MADRUGADA";
	}elseif($hora >= 6 && $hora < 12){
	$saudacao	= "BOM DIA";
	}elseif($hora >= 12 && $hora < 18){
	$saudacao	= "BOA TARDE";
	}else{
	$saudacao	= "BOA NOITE";
	}
	return $saudacao;
	}

	/**
	* 	print ucwords_improved('ANAstÁcio pereiRA E sIlva', array('e'));
	*/
	public static function PrimeiraMaiuscula( $s, $e = array() ) {

	setlocale(LC_CTYPE, 'pt_BR');

	$e = array( 'da', 'das', 'de', 'do', 'dos', 'e' );

	     return join(' ',
	                array_map(
	                    create_function(
	                        '$s',
	                        'return (!in_array($s, ' . var_export($e, true) . ')) ? ucfirst($s) : $s;'
	                    ),
	                    explode(
	                        ' ',
	                        strtolower($s)
	                    )
	                )
	            );

	}




	/**
	* Gera Idade conforme data de nascimento
	*
	* @param tem que ser a data em forma de / ou -
	*
	* exemplo 10/03/2013 ou 10-03-2013
	*/
	public static function Idade( $data_nasc ) {

	$data_formatada	=	date('d/m/Y', strtotime($data_nasc));
	list($dia, $mes, $ano) = explode('/', $data_formatada);
	    // Descobre que dia é hoje e retorna a unix timestamp
	    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	    // Descobre a unix timestamp da data de nascimento
	    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
	    return $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);


	}



	/**
	* Gera mapa Google
	*
	* @param endereço normal
	*
	* exemplo R. Hermann Tribess, 330 - Tribess - Blumenau - SC - Brasil
	*/
	public static function geraMapa( $endereco , $height = null , $width = null , $zoom = "18" , $title = null , $html = null ) {

	$largura	= (	empty($height)	)	? "900" : $height;
	$altura	= (	empty($width)	)	? "500" : $width;
	$address = urlencode( $endereco );
	$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
	$output	= json_decode($geocode);
	$latitude	= $output->results[0]->geometry->location->lat;
	$longitude	= $output->results[0]->geometry->location->lng;
	$local_titulo	= ( empty($html) or empty($title) ) ? "" : '
	var local = new google.maps.LatLng('.$latitude.' , '.$longitude.');
	marcadorLocal = new google.maps.Marker({
	position: local,
	map: map,
	title:"'.$title.'",
	});
	var infowindow = new google.maps.InfoWindow({
	content: "'.$html.'"
	});
	google.maps.event.addListener(marcadorLocal, \'click\', function(event) {
	infowindow.open(map,marcadorLocal);
	});
	';
	$mapa .=	'
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script>
	var map = null;
	function mapa(){
	var latlng = new google.maps.LatLng('.$latitude.' , '.$longitude.');
	var myOptions = {
	zoom: '.$zoom.',
	center: latlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	//criando o mapa
	map = new google.maps.Map(document.getElementById("mapa"), myOptions);
	'.$local_titulo.'
	}
	google.maps.event.addDomListener(window, \'load\', mapa);
	</script>
	';
	$mapa .=	'
	<div id="mapa" style="margin: 0; padding: 0; height: '.$altura.'px; width: '.$largura.'px;"></div>
	';
	return $mapa;
	}



	/**
	* Retorna a porcentagem que vale o valor de um total
	*
	* @param number - Total , partedo total a saber a porcentagem que equivalor
	* Ultimo parametro se quiser que o numero retorne inteiro
	*/
	public static function porcentoTotal( $total , $parte_do_total , $inteiro = false ) {

	$porcentagem	=	( $parte_do_total / $total ) * 100;
	if( $inteiro )	:	return round($porcentagem);
	else	:	return $porcentagem;
	endif;

	}


	/**
	* Retorna string removendo conteudo SQL e limpa gravando seguro
	*
	* @param number - $informação string
	*
	*/
	public static function infoGravar( $str, $formUse = true){
	    // remove palavras que contenham sintaxe sql
	    $str = preg_replace("/(from|select|insert|delete|where|drop table|show tables|like|grant|revoke|#|\*|--|\\\\)/i","",$str);
	    $str = trim($str);//limpa espaços vazio
	    $str = strip_tags($str);//tira tags html e php
	    if(!$formUse || !get_magic_quotes_gpc())
	            $str = addslashes($str);//Adiciona barras invertidas a uma string
	    return $str;
	}



	/**
	 * Transforma stdclass array
	 * @param  stdclass $d objeto
	 * @return array    convertida
	 */
	public static function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}




	/**
	 * Retorna status tratado do pag seguro
	 * @param  [type] $cod int
	 * @return [type]      array com staus string e descricao do que é o status
	 */
	public static function getStatusPagseguro( $cod ){

		switch ($cod) {
			
			case null:
				return array( 'status' => 'Sem Status', 'descricao' => 'Ainda não foi gravado status, retorno NULL.' );
				break;

			case '1':
				return array( 'status' => 'Aguardando pagamento', 'descricao' => 'o comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento.' );
				break;

			case '2':
				return array( 'status' => 'Em análise', 'descricao' => 'o comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação.' );
				break;

			case '3':
				return array( 'status' => 'Paga', 'descricao' => 'a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.' );
				break;

			case '4':
				return array( 'status' => 'Disponível', 'descricao' => 'a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.' );
				break;

			case '5':
				return array( 'status' => 'Em disputa', 'descricao' => 'o comprador, dentro do prazo de liberação da transação, abriu uma disputa.' );
				break;

			case '6':
				return array( 'status' => 'Devolvida', 'descricao' => 'o valor da transação foi devolvido para o comprador.' );
				break;

			case '7':
				return array( 'status' => 'Cancelada', 'descricao' => 'a transação foi cancelada sem ter sido finalizada.' );
				break;

			default:
				return array( 'status' => 'erro', 'descricao' => "Código não relacionado" );
				break;
				

		}


	}




}