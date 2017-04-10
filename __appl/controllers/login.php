<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends YOGI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
		$this->seting=$this->get_seting();
	}
	
	public function index(){
		//print_r($_POST);
		$user=$this->db->escape_str($this->input->post('user'));
		$pass=$this->db->escape_str($this->input->post('pwd'));
		$error=false;
		if($user && $pass){
			$cek_user=$this->mhome->getdata('data_login',$user);
			//print_r($cek_user);exit;
			if(count($cek_user)>0){
				if(isset($cek_user['status']) && $cek_user['status']==1){
					//echo $cek_user['status'];exit;
					//echo $pass.'->'.$this->encrypt->decode($cek_user['pwd']);exit;
					if($pass==$this->encrypt->decode($cek_user['pwd'])){
						//echo $pass;exit;
						$this->session->set_userdata($this->config->item('user_data'), base64_encode(serialize($cek_user)));	
					}
					else{
						$error=true;
						$this->session->set_flashdata('error', 'Password Invalid');
					}
				}
				else{
					$error=true;
					$this->session->set_flashdata('error', 'USER Sudah Tidak Aktif Lagi');
				}
			}
			else{
				$error=true;
				$this->session->set_flashdata('error', 'User Tidak Terdaftar');
			}
			//if(isset($cek_u))
		}
		else{
			$error=true;
			$this->session->set_flashdata('error', 'Isi User Dan Password');
		}
		//echo $error;exit;
		if($error==true)header("Location: {$this->host}login");
		else header("Location: {$this->host}kelas");
	
		
	}
	
	function logout(){
		$this->session->unset_userdata($this->config->item('user_data'), 'limit');
		$this->session->unset_userdata($this->config->item('modeling'), 'limit');
		$this->session->sess_destroy();
		header("Location: " . $this->host);
	}
	function cek_user(){
		echo $this->mhome->getdata('cek_user');
	}
	function simpan_reg(){
		echo $this->mhome->simpan_reg();
	}
	function register($p1="",$p2=""){
		//echo $this->lib->kirimemail("email_test", 'yogi_p4try4@yahoo.com');exit;
		$usr="";
		if($p2!=""){$usr=$p2;}
		if($p1=="notif"){
			$this->load->library('lib');
			$data=$this->mhome->getdata('cek_user','get',$usr);
			//print_r($data);exit;
			if(isset($data['nama_user'])){
				$this->lib->kirimemail("email_notif", $data['Email'],$data['nama_user'],$data['pwd']);
			}else{
				$this->smarty->assign('msg',1);
			}
			$temp='modul/notif.html';
			$this->smarty->assign('temp',$temp);
			return $this->smarty->display('index.html');
			//return $this->smarty->display('modul/notif.html');
		}else if($p1=="act"){
			$usr=$p2;
			//echo $usr;exit;
			$data=$this->mhome->getdata('cek_user','get',$usr);
			if(isset($data['nama_user'])){
				if($this->mhome->simpan_reg("act",$data['nama_user'])==1){
					$temp='modul/act.html';
					$this->smarty->assign('temp',$temp);
					return $this->smarty->display('index.html');
				}
			}
		}
		$opt="<option value='L'>Laki - laki </option><option value='L'>Wanita</option>";
		$this->smarty->assign('opt',$opt);
		$this->smarty->display('registrasi/register.html');
	}
}
