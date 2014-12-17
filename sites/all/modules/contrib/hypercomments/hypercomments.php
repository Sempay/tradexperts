<?php
/**
* Hypercomments class
*/
class HyperComments {
	public $api_version = '1.0.0';
	public $secret_key  = NULL;

	function __construct($secret_key  = NULL) {
		$this->secret_key = $secret_key;
	}
	/**
	* Sunc commetns method
	*/
	public function hc_notify($data, $time, $signature) {	
		if($this->secret_key) {
			if($signature == md5((string)$this->secret_key.(string)$data.(string)$time)) {

				$data_decode = json_decode($data); 
				$res = array();
			    foreach($data_decode as $cmd){                                 
			        switch($cmd->cmd){
			        	case 'streamMessage':
			        		$r = $this->save_comment($cmd);
			        		$res[] = $r;
			        	break;
			        	case 'streamEditMessage':
			        	    // TODO
			        		//$r = $this->edit_comment($cmd);
			        		$res[] = 'ok';
			        	break;
			        	case 'streamRemoveMessage':
			        		$r = $this->delete_comment($cmd);
			        		$res[] = $r;
			        	break;
			        }
			    }
			    return $res;
			}else{
				return 'error: invalid sign';
			}
		}else{
			return 'error: no secret key';
		}
	}
	/**
	* Save comments in DB
	*/
	protected function save_comment($data) {
		$post_id_mas = explode('?q=node/', $data->xid);                                    
        $post_id     = $post_id_mas[1];

		$c = new stdClass();
		$c->nid      = $post_id;
		$c->pid      = (isset($data->parent_id)) ? $this->get_cid_by_hid($data->parent_id) : 0;
		$c->uid      = (isset($data->user_id)) ? $data->user_id : 0;
		$c->name     = $data->nick;
		$c->hostname = $data->ip;
		$c->created  = time();
		$c->status   = COMMENT_PUBLISHED;
        $c->language = LANGUAGE_NONE;
        $c->subject  = substr($data->text, 0, 30).'...';
		$c->comment_body[$c->language][0]['value']  = $data->text;
        $c->comment_body[$c->language][0]['format'] = 'filtered_html';
        comment_submit($c);
        comment_save($c);
        $this->insert_meta($post_id, $data->id);
        return $data;    	
	}
	/**
	* Delete comments from DB
	*/
	protected function delete_comment($data) {
		$cid = $this->get_cid_by_hid($data->id);
		comment_delete($cid);
		return 'ok';   
	}
	/**
	* Get a bunch of id systems
	*/
	protected function get_cid_by_hid($hc_parent) {
		$sql = "SELECT cid FROM hypercomments_meta WHERE hid='".$hc_parent."'";
		$rows = db_query($sql);
		$cid = 0;
		foreach ($rows as $row) {
			$cid = $row->cid;
		}
		return $cid;
	}
	/**
	* Save a bunch of id systems
	*/
	protected function insert_meta($nid, $hid) {
		$rows = db_query("SELECT cid FROM comment WHERE nid=:nid AND status=:status ORDER BY cid DESC LIMIT 1", array(
				'nid' => 1,
				'status' => COMMENT_PUBLISHED
			));
		$last_cid = 0;
		foreach ($rows as $row) {
			$last_cid = $row->cid;
		}
		$data = array(
			'cid' => $last_cid,
			'hid' => $hid
		);
		db_insert('hypercomments_meta')->fields($data)->execute();
	}
}