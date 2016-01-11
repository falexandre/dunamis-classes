<?php

namespace DunamisClasses;

class aviso{

	private	$mensagem;
	private	$email_contato;	
	private	$from;	
	private	$from_name;	
	private	$titulo_corpo             = "Formul&aacute;rio de contato";
	private	$assunto                  = "Formul&aacute;rio de contato";
	private $smtp                     = URL_SMTP;
	private $email_autenticacao       = EMAIL_SMTP;
	private $senha_email_autenticacao = EMAIL_PASS;
	private $autenticar 		      = false;
	private $anexo;

	
	private $conteudo = array(); // conteudo caso seja um aviso
	
	
		/* Monta os títulos dos dados do cliente */
	public function setText( $value ){	
		array_push( $this->conteudo , $value );	
	}	
	
	public function setAutenticar( $autenticar ){
		$this->autenticar = $autenticar;
	}

	public function setEmailContato( $email ){
		$this->email_contato = $email;
	}

	public function setEmailFrom( $email ){
		$this->from = $email;
	}

	public function setFromName( $name ){
		$this->from_name = $name;
	}

	public function setTitulo( $titulo ){
		$this->titulo_corpo = $titulo;
	}

	public function setAssunto( $assunto ){
		$this->assunto = $assunto;
	}

	public function setSMTP( $smtp ){
		$this->smtp = $smtp;
	}

	public function setEmailAutenticacao( $email ){
		$this->email_autenticacao = $email;
	}

	public function setSenhaAutenticacao( $senha ){
		$this->senha_email_autenticacao = $senha;
	}



	public function getMensagem(){
		return $this->mensagem;
	}


	public function valida( $campos = array() , $ignorados = array() ){


		$erro = null;


		foreach ($campos as $nome => $valor ) {
			
				switch ($nome) {
							
					case 'Email':
						
						$filtro =  filter_var( $valor , FILTER_VALIDATE_EMAIL );
						if(!$filtro){$erro++;} 
						elseif ( !in_array( $nome , $ignorados) AND empty($filtro) ) {$erro++;}

						break;


					default: 
						if ( !in_array( $nome , $ignorados) AND empty($valor) ) {$erro++;}
					break;
				}

		}

		return ( empty($erro) ) ? true : false;



	}

	
	private function getCampos(){	

			$monta_campos = null;

			foreach ( $this->conteudo  as $titulo => $valor) {		
			$monta_campos .= '
						<tr>
			                <td>
								'.utf8_decode($valor).'
			                </td>
			            </tr>'."\n";			
			}	

		

	
		return $monta_campos;	
	}	

	
	
	
	public function geraEmail(){
	
	
		$html_email ='
			<!doctype html>
			<html>

			<head>
			    <meta charset="UTF-8">
			</head>

			<body>
			    <table width="600" align="center" border="0" cellspacing="0" cellpadding="0">
			        <tbody>
			            <tr>
			                <td>
			                  <img src="'.DOMINIO.'public/site/img/logo-email.jpg" width="600" height="100" alt="logo da empresa" />
			                </td>
			            </tr>';
			            
		$html_email .= $this->getCampos();

		$html_email .='<tr>
			                <td>
			                    <p style="background:#CCCCCC;;color:#000000;font-weight:bold;font-size:11px;height:15px;text-align:center" >Este é um e-mail automático disparado pelo sistema. Favor não respondê-lo, pois esta conta não é monitorada.</p>
			                </td>
			            </tr>
			        </tbody>
			    </table>
			</body>

			</html>

		';


		$mail = new \PHPMailer();
		$mail->IsSMTP(); 						
		$mail->Host     = $this->smtp;			
		$mail->SMTPAuth = true;	
		$mail->Port     = 587; 				
		$mail->Username = $this->email_autenticacao; 			
		$mail->Password = $this->senha_email_autenticacao; 			
		$mail->From     = $this->from; 		    
		$mail->FromName = $this->from_name; 			
		$mail->AddAddress($this->email_contato);
		//$mail->AddBCC('fabiolalexandre@gmail.com', 'DUNAMIS');
		$mail->addAttachment($this->anexo); 
		$mail->IsHTML(true);					
		$mail->Subject  = $this->assunto; 		
		$mail->Body     = $html_email;
	
		
		$enviado 		= $mail->Send();		
		$mail->ClearAllRecipients();			
		$mail->ClearAttachments();	

		if($this->autenticar){


		}else{

			if(is_array($this->email_contato)){
				foreach ($this->email_contato as $value) {
					$to  .= $value . ', ';
				}
				$to = trim($to,', ');
			}else{
				$to = $this->email_contato;
			}

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: $this->fromName <$this->from>\n";
			$headers .= "Bcc: Fábio <fabiolalexandre@gmail.com>\n";

			// Mail it
			$enviado = mail($to, $this->assunto , $html_email, $headers);

		}



		if($enviado):	
			$this->mensagem = "E-mail enviado com sucesso!"; return true; 
		else: 			
			$this->mensagem = "Erro ao enviar e-mail, tente novamente!"; return false;
		endif;
		
	
	}



	


}


	
	