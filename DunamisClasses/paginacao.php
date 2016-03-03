<?php

namespace DunamisClasses;

use ActiveRecord\Model;

class paginacao extends Model{


	private $table_usada; 					// tabela usada para a paginacao
	private $total_registro; 				// total de registro do banco de dados
	private $pagina_atual; 					// pagina atual da navegacao
	private $TotalPorPagina; 				// total de registro por página
	private $links_laterais;  				// links laterais da paginacao
	private $resultado;  					// conteudo da página a ser exibida
	private $url_atual;  					// url completa da pagina para links
	private $quantidade_exibir;  			// quantidade a exibir por página
	private $inicio;  						// variavel inicial montegem de links
	private $limite;  						// variavel limit montegem de links
	static  $table_name;					// setar antes de instaciar a classe exemplo paginacao::$table_name = 'nomedatabela';


	public function setConfig( $config = array() ){

		try {

			if( !is_array( $config ) ){ throw new Exception("Paramentro configração tem que ser um array.");}

			$default = array(
				'paginaAtual'      => 1,
				'quantidadeExibir' => 10,
				'urlAtual' 		   => DOMINIO.DS,
				'condicao' 		   => array(),
				'order' 		   => 'id desc',
				'select' 		   => '*',
			);

			//junta as 2 array caso nao seja configurado mantem um padrao
			$setings = array_merge($default, $config);

			//monta as variaveis de configuracao para melhor usar
			$quantidade           = $setings['quantidadeExibir'];//quantidade a ser mostrada
			$paginaAtual          = $setings['paginaAtual'];
			$link          		  = $setings['urlAtual'];
			$inicioPaginacao      = ($quantidade * $paginaAtual) - $quantidade; 
			$condicionais         = $setings['condicao'];
			$select         	  = $setings['select'];
			$orderBy         	  = $setings['order'];

			//busca as informaçoes no banco conforme a pagina
			$listar               = parent::all( 
				array(
						'select'     => $select ,
						'conditions' => $condicionais ,
						'order'      => $orderBy ,
						'limit'      => $quantidade , 
						'offset'     => $inicioPaginacao  
				)    
			);

			/**
			 * Busca todos os registros da tabela para saber 
			 * quantas paginas serão exibidas
			 */
			$count_total          = parent::count(
				array(
						'conditions' => $condicionais 
				)
			);


			// Seta as variaveis para deixar acessivel na classe
			$this->url_atual    	 = $link; 		 //url atual para links
			$this->resultado    	 = $listar; 	 //resultado a ser mostrado na pagina
			$this->total_registro    = $count_total; //total das informacoes do banco
			$this->pagina_atual      = $paginaAtual; //pagina atual da navegacao
			$this->quantidade_exibir = $quantidade;  //quantidade de linhas por pagina
			$this->TotalPorPagina    = ceil( $count_total / $quantidade );
			$this->links_laterais    = ceil( $quantidade  / 2 );
			//monta os links da paginacao para sempre ter um limite right e left 
	
			$this->inicio            = $paginaAtual - $this->links_laterais;
			$this->limite            = $paginaAtual + $this->links_laterais;


		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}




	public function getListagem(){
		return $this->resultado;
	}


	public function getTotal(){
		return $this->total_registro;
	}


	public function getRowView(){
		return $this->quantidade_exibir;
	}



	public function getPaginacao(){


	if( $this->total_registro > $this->quantidade_exibir ){

		$class_pg1 =  ( $this->pagina_atual == 1 						) ? 'class="disabled"' : ''; //desativa link pagina inicial
		$class_pgU =  ( $this->pagina_atual == $this->TotalPorPagina 	) ? 'class="disabled"' : ''; //desativa link pagina final

		$link_anterior		 = ( $this->pagina_atual == 1 						) ? '#' : $this->pagina_atual - 1;
		$link_posterior		 = ( $this->pagina_atual == $this->TotalPorPagina 	) ? '#' : $this->pagina_atual + 1;

		$result = '
		<nav>
		  <ul class="pagination">
  		    <li '.$class_pg1.' >
		    	<a href="'.$this->url_atual.DS.$this->quantidade_exibir.DS.$link_anterior.'" aria-label="Previous">
		    		<span aria-hidden="true">&laquo;</span>
		    	</a>
		    </li>
		';


		//links do centro
		for ($i = $this->inicio; $i <= $this->limite; $i++){
			if($this->pagina_atual == $i){
				//link atual pagina atual desativado
				$result .= sprintf('<li class="active"><a href="#">%s <span class="sr-only">(current)</span></a></li>', $i );

			}elseif($i >= 1 && $i <= $this->TotalPorPagina){
				// demais links
				$result .= sprintf('<li><a href="%s">%s</a></li>'	, $this->url_atual.DS.$this->quantidade_exibir.DS.$i , $i  );
			}
		}


		$result .= '
		    <li '.$class_pgU.' >
		      <a href="'.$this->url_atual.DS.$this->quantidade_exibir.DS.$link_posterior.'" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		      </a>
    		</li>
		  </ul>
		</nav>
		';

		
			return $result;
		}else{
			return;
		}

	}

	public function toArray(){

		if( $this->total_registro > $this->quantidade_exibir ){
			$result = array();

			for ($i = $this->inicio; $i <= $this->limite; $i++){
				if($this->pagina_atual == $i){
					$result['atual'] = $i;
					$result[] = $i;
				}elseif($i >= 1 && $i <= $this->TotalPorPagina){
				$result[] = $i;
				}
			}

			return $result;
		}else{
			return;
		}

	}


}


	
	