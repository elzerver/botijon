<?php
class sha1 extends command {

	public function __construct(){
		$this->name = 'sha1';
		$this->public = true;
		$this->server = 'irc.freenode.net';
	}

	public function help(){
		return 'Uso: !sha1 <cadena>. Devuelve el hash sha1 de la cadena.';
	}
	
	public function process($args){
		$this->output = sha1($args);
		$this->save($args);
	}
	
	protected function save($args){		
		//store the md5 and sha1 into a database;
		global $db;
		$sql = "select count(1) as count from hashes where string = :string";
		$r = $db->Parse($sql, 1);
		$r->Bind(":string", $args);
		$r->Execute();
		$row = $r->GetRow();
		if ( $row->count == 0 ){
			$md5 = md5($args);
			$sha1 = sha1($args);
			$sql = "insert into hashes ( string , md5, sha1) values (:string, :md5, :sha1)";
			$r = $db->Parse($sql, 1);
			$r->Bind(":string", $args);
			$r->Bind(":md5", $md5);
			$r->Bind(":sha1", $sha1);
			$r->Execute();
		}		
	}
}