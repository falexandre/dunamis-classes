<?php

namespace DunamisClasses;

use \DateTime;

class ngTratamento{

	
	private function __construct(){}


	public static function limpaSQLtag( $tratado ){
		$tratado = trim($tratado);//limpa espaços vazio
    	$tratado = strip_tags($tratado);//tira tags html e php...
    	// remove palavras que contenham sintaxe sql
    	$tratado = preg_replace("/(from|select|insert|delete|where|drop table|show tables|like|grant|revoke|#|\*|--|\\\\)/i","",$tratado);
    	return $tratado;
	}


	public static function salvar( Array $campos ){

		$campos_tratados = array();
		
			foreach ( $campos as $nome_campo => $valor ) {
				if( $nome_campo != 'id' ){
					switch ($nome_campo) {

						case 'senha':
							$newValor = self::limpaSQLtag($valor);
							$newValor = crypt($newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;
							
						case 'data_atendimento':
							$newValor = self::limpaSQLtag($valor);
							$newValor = strtr($newValor , '/' , '-');
							$newValor = strtotime($newValor);
							$newValor = date('Y-m-d', $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'CEP':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'cep':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'CPF_CNPJ':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'cnpj':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'RG_IE':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'cpf_cnpj':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'rg_ie':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'celular1':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'celular2':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'foneresidencial':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'fonecomercial':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'fone':
							$newValor = self::limpaSQLtag($valor);
							$newValor = preg_replace('/[^0-9]/', "", $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'valor':
							$newValor = self::limpaSQLtag($valor);
							// $newValor = str_replace(".", "",$newValor);
							// $newValor = str_replace(",", ".",$newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'valor_de':
							$newValor = self::limpaSQLtag($valor);
							$newValor = str_replace(".", "",$newValor);
							$newValor = str_replace(",", ".",$newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'valor_produto':
							$newValor = self::limpaSQLtag($valor);
							$newValor = str_replace(".", "",$newValor);
							$newValor = str_replace(",", ".",$newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'valor_parcela':
							$newValor = self::limpaSQLtag($valor);
							$newValor = str_replace(".", "",$newValor);
							$newValor = str_replace(",", ".",$newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'valor_total':
							$newValor = self::limpaSQLtag($valor);
							$newValor = str_replace(".", "",$newValor);
							$newValor = str_replace(",", ".",$newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'peso':
							$newValor = self::limpaSQLtag($valor);
							$newValor = number_format($newValor,3,",","");
							$campos_tratados[$nome_campo] = $newValor;
							break;


						case 'data_inicio':
							$newValor = self::limpaSQLtag($valor);
							$newValor = strtr($newValor , '/' , '-');
							$newValor = strtotime($newValor);
							$newValor = date('Y-m-d', $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'data':
							$newValor = self::limpaSQLtag($valor);
							$newValor = strtr($newValor , '/' , '-');
							$newValor = strtotime($newValor);
							$newValor = date('Y-m-d', $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;
							

						case 'datadenascimento':
							$newValor = self::limpaSQLtag($valor);
							$newValor = strtr($newValor , '/' , '-');
							$newValor = strtotime($newValor);
							$newValor = date('Y-m-d', $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'data_agendamento':
							$newValor = self::limpaSQLtag($valor);
							$newValor = strtr($newValor , '/' , '-');
							$newValor = strtotime($newValor);
							$newValor = date('Y-m-d', $newValor);
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'flg_newsletter':
							$newValor = self::limpaSQLtag($valor);
							$newValor = $newValor ? 'S' : 'N';
							$campos_tratados[$nome_campo] = $newValor;
							break;

						case 'data_aplicacao':
							if(!empty($valor)){
								$newValor = self::limpaSQLtag($valor);
								$newValor = strtr($newValor , '/' , '-');
								$newValor = strtotime($newValor);
								$newValor = date('Y-m-d', $newValor);
								$campos_tratados[$nome_campo] = $newValor;
							}else{
								$campos_tratados[$nome_campo] = $valor;
							}
							break;


						case 'cor':
							  $campos_tratados[$nome_campo] = $valor;
							break;

						
						default:
							$campos_tratados[$nome_campo] = self::limpaSQLtag($valor);
							break;
					}
				}
			}

		return $campos_tratados;

	}


	







}	