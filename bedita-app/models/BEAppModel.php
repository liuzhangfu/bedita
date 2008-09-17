<?php
/**
 * Bedita model base class
 * 
 * @filesource
 * @copyright		Copyright (c) 2007
 * @link			
 * @package			
 * @subpackage		
 * @since			
 * @version			
 * @modifiedby		
 * @lastmodified	
 * @license			
 */

class BEAppModel extends AppModel {

	protected $modelBindings = array();
	
	/**
	 * Collassa il risultato di un record in un array unico
	 * 
	 * @param array record	record da collassare
	 * 
	 * @return array		record collassato
	 */
	function am($record) {
		$tmp = array() ;
		foreach ($record as $key => $val) {
			if(is_array($val)) $tmp = array_merge($tmp, $val) ;
			else $tmp[$key] = $val ;
		}
		
		return $tmp ;
	}

	/**
	 * Get SQL date format
	 *
	 * @param unknown_type $value
	 * @return unknown
	 */
	public function getDefaultDateFormat($value = null) {
		if(is_integer($value)) return date("Y-m-d", $value) ;
		
		if(is_string($value) && !empty($value)) {
			$conf = Configure::getInstance() ;			
			$d_pos = strpos($conf->dateFormatValidation,'dd');
			$m_pos = strpos($conf->dateFormatValidation,'mm');
			$y_pos = strpos($conf->dateFormatValidation,'yyyy');
			$value = substr($value, $y_pos, 4) . "-" . substr($value, $m_pos, 2) . "-" . substr($value, $d_pos, 2);
			return $value ;
		}
		
		return null ;
	}
	
	/**
	 * Check date field in $this->data[ModelName][$key] -> set to null if empty or call getDefaultDateFormat
	 *
	 * @param string $key
	 */
	protected function checkDate($key) {
		$data = &$this->data[$this->name];
		if(empty($data[$key])) {
			$data[$key] = null;
		} else {
			$data[$key] = $this->getDefaultDateFormat($data[$key]);
		}
	}

	/**
	 * Check float/double field in $this->data[ModelName][$key] -> set to null if empty
	 *
	 * @param string $key
	 */
	protected function checkFloat($key) {
		$data = &$this->data[$this->name];
		if(empty($data[$key])) {
			$data[$key] = null;
		}
	}
	
	/**
	 * Default text format
	 *
	 * @param unknown_type $value
	 * @return unknown
	 */
	protected function getDefaultTextFormat($value = null) {
		$labels = array('html', 'txt', 'txtParsed') ;
		if(isset($value) && in_array($value, $labels)) 
			return $value ;
		$conf = Configure::getInstance() ;
		return ((isset($conf->type))?$conf->type:'') ;
	}
	
		
	/**
	 * Object search Toolbar
	 *
	 * @param integer 	$page		
	 * @param integer 	$dimPage	
	 * @param mixed 	$sqlCondition	sql search condition
	 * @param boolean 	$recursive	TRUE, retrieve connected objects
	 * @param mixed 	$sqlCondition	sql search condition
	 * @return array
	 */
	function toolbar($page = null, $dimPage = null, $sqlCondition = null, $recursive = null, $searchText = null) {
		// conta il numero di record
		if(($size = $this->findCount($sqlCondition, $recursive)) === false) return false ;
		
		$toolbar = array("first" => 0, "prev" => 0, "next" => 0, "last" => 0, "size" => 0, "pages" => 0, "page" => 0, "dim" => 0) ;
		
		if(!$page || empty($page)) $page = 1 ;
		if(!$dimPage || empty($dimPage)) $dimPage = $size ;
		
		$pageCount = $size / $dimPage ;
		settype($pageCount,"integer");
		if($size % $dimPage) $pageCount++ ;
		
		$toolbar["pages"] 	= $pageCount ;
		$toolbar["page"]  	= $page ;
		$toolbar["dim"]  	= $dimPage ;
		
		if($page == 1) {
			if($page >= $pageCount) {
				// Una sola
				
			} else {
				// Prima pagina
				$toolbar["next"] = $page+1 ;
				$toolbar["last"] = $pageCount ;
			} 
		} else {
			if($page >= $pageCount) {
				// Ultima
				$toolbar["first"] = 1 ;
				$toolbar["prev"] = $page-1 ;
			} else {
				// Pagina di mezzo
				$toolbar["next"] = $page+1 ;
				$toolbar["last"] = $pageCount ;
				$toolbar["first"] = 1 ;
				$toolbar["prev"] = $page-1 ;
			}
		}

		$toolbar["start"]	= (($page-1)*$dimPage)+1 ;
		$toolbar["end"] 	= $page * $dimPage ;
		if($toolbar["end"] > $size) $toolbar["end"] = $size ;
		
		$toolbar["size"] = $size ;
		
		return $toolbar ;	
	}

	/**
	 * SQL limit clausole
	 *
	 * @param int $page, page num
	 * @param int $dim, global size/count
	 * @return string
	 */
	protected function getLimitClausole($page = 1, $dim = 100000) {
		$offset = ($page > 1) ? (($page -1) * $dim) : null;
		return isset($offset) ? "$offset, $dim" : "$dim" ;
	}

	public function containLevel($level = "minimum") {
		if(!isset($this->modelBindings[$level])) {
			throw new BeditaException("Contain level not found: $level");
		}
		$this->contain($this->modelBindings[$level]);
	}
}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
/**
 * Classe utilizata per le viste nn fa niente 
 * un solo metodo: afterFind
 */
class _emptyAfterFindView {
	function afterFind($result) { return $result ; }
}


?>