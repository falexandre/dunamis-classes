<?php

namespace DunamisClasses;

class email{

	private	$mensagem;
	private	$email_contato;	
	private	$from;	
	private	$fromName;	
	private	$titulo_corpo             = "Formul&aacute;rio de contato";
	private	$assunto                  = "Formul&aacute;rio de contato";
	private $smtp                     = URL_SMTP;
	private $email_autenticacao       = EMAIL_SMTP;
	private $senha_email_autenticacao = EMAIL_PASS;
	private $autenticar 		      = true;
	private $anexo;

	
	private $campos   = array(); //informações do titulo Nome:, Telefone: ...
	private $conteudo = array(); // conteudo caso seja um aviso
	
	/* Monta os títulos dos dados do cliente */
	public function setPost(  $campo , $value ){	
		$this->campos[$campo] = $value;	
	}	
		/* Monta os títulos dos dados do cliente */
	public function setText( $value ){	
		array_push( $this->conteudo , $value );	
	}	

	public function setEmailContato( $email ){
		$this->email_contato = $email;
	}

	public function setEmailFrom( $email ){
		$this->from = $email;
	}

	public function setNomeFrom( $nome ){
		$this->fromName = $nome;
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

	public function setAutenticar( $autenticar ){
		$this->autenticar = $autenticar;
	}

	public function setEmailAutenticacao( $email ){
		$this->email_autenticacao = $email;
	}

	public function setSenhaAutenticacao( $senha ){
		$this->senha_email_autenticacao = $senha;
	}

	public function setAnexo( $file ){
		$this->anexo = $file;
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
							
					case 'email':
						
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

	
	/* Monta as linhas dos campos do orçamento */
	private function getCampos(){	
			$monta_campos = null;
			foreach ($this->campos as $titulo => $valor) {		
			$monta_campos .= "<TR>
								 <TD vAlign=top align=right width=150 bgColor=#c0c0c0><B>$titulo: </B></TD>
								 <TD width=450 bgColor=#e0e0e0>$valor</TD>
							  </TR>";			
			}		
		return $monta_campos;	
	}	

	
	
	
	public function geraEmail(){
	
	
		$html_email .="
		<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<HTML>
		<META http-equiv=Content-Type content=\"text/html; charset=iso-8859-1\">
		<BODY bgColor=#ffffff>
		<TABLE cellSpacing=1 cellPadding=3 width=600 border=0 align=center>
			<TBODY>
				   <TR>
					 <TD align=middle bgColor=#3C8DBC colSpan=2><FONT
					 	color=#ffffff><B>". $this->titulo_corpo ."</B></FONT></TD>
				   </TR>";
				   
		$html_email .= $this->getCampos();		   
				   
		$html_email .="		   
					<TR>
					 <TD bgColor=#3C8DBC colSpan=2 align=\"center\" height=\"10\" >
					 </TD>
				   </TR>
		</TBODY>
			</TABLE>
			<TABLE cellSpacing=1 cellPadding=3 width=600 border=0 align=center >
				<TR align=center  >
				  <TD>Mensagem enviada em:<B>". date('d/m/Y') . "</B> às <B>". date('H:i:s') ."</B> hrs.</TD>
				</TR>
			</TABLE>
			</BODY>
			</HTML>
		";


		if($this->autenticar){

			$mail = new \PHPMailer();
			$mail->IsSMTP(); 						
			$mail->Host     = $this->smtp;			
			$mail->SMTPAuth = true;	
			$mail->Port     = 587; 				
			$mail->Username = $this->email_autenticacao; 			
			$mail->Password = $this->senha_email_autenticacao; 			
			$mail->From     = $this->from; 	
			$mail->Sender 	= $this->from;
			$mail->FromName = $this->fromName; 			
			if(is_array($this->email_contato)){
				foreach ($this->email_contato as $value) {
					$mail->AddAddress($value);
				}
			}else{
				$mail->FromName = $this->email_contato; 			
				$mail->AddAddress($this->email_contato);
			}
			$mail->AddBCC('fabiolalexandre@gmail.com', 'DUNAMIS');
			$mail->addAttachment($this->anexo); 
			$mail->IsHTML(true);					
			$mail->Subject  = $this->assunto; 		
			$mail->Body     = $html_email;
		
			
			$enviado 		= $mail->Send();		
			$mail->ClearAllRecipients();			
			$mail->ClearAttachments();				

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
			$enviado = mail($to, utf8_encode("Formulário de contato - "). APPTITULO, $html_email, $headers);

		}


		if($enviado):	
			$this->mensagem = "E-mail enviado com sucesso!"; return true; 
		else: 			
			$this->mensagem = "Erro ao enviar e-mail, tente novamente!"; return false;
		endif;
		
	
	}



	


}


	
	