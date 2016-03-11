<?php 

namespace DunamisClasses;

 /**
 * 	Classe que gerencia todo o cache e session de informações do site
 */
 class cache 
 {
 	
 	private static $time = '1 year';
 	private $folder;



	public function __construct($folder = null) {
		$this->setFolder(!is_null($folder) ? $folder : sys_get_temp_dir());
	}



    protected function setFolder($folder) {
            // Se a pasta existir, for uma pasta e puder ser escrita
            if (file_exists($folder) && is_dir($folder) && is_writable($folder)) {
                    $this->folder = $folder;
            } else {
                    trigger_error('Não foi possível acessar a pasta de cache', E_USER_ERROR);
            }
    }



	protected function generateFileLocation($key) {
	    return $this->folder . DIRECTORY_SEPARATOR . sha1($key) . '.tmp';
	}



    protected function createCacheFile($key, $content) {
        // Gera o nome do arquivo
        $filename = $this->generateFileLocation($key);
       
        // Cria o arquivo com o conteúdo
        return file_put_contents($filename, $content)
                OR trigger_error('Não foi possível criar o arquivo de cache', E_USER_ERROR);
    }


    public function save($key, $content, $time = null) {
            $time = strtotime(!is_null($time) ? $time : self::$time);
                   
            $contentSend = serialize(array(
                    'expires' => $time,
                    'content' => $content));
           
            return $this->createCacheFile($key, $contentSend);
    }


    public function read($key) {
            $filename = $this->generateFileLocation($key);
            if (file_exists($filename) && is_readable($filename)) {
                    $cache = unserialize(file_get_contents($filename));
                    if ($cache['expires'] > time()) {
                            return $cache['content'];
                    } else {
                            unlink($filename);
                    }
            }
            return null;
    }


    public function delete($key) {
            $filename = $this->generateFileLocation($key);
            if (file_exists($filename) && is_readable($filename)) {
                 unlink($filename); 
            }
            return true;
    }



 }