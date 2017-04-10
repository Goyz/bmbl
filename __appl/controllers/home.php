<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends YOGI_Controller {
	
	function __construct(){
		parent::__construct();
		//$this->cek_user();		
		$this->smarty->assign('acak', md5(date('H:i:s')) );
		$this->captcha = $this->session->userdata('captcha');
		$this->load->library('encrypt');
		$this->seting=$this->get_seting();
		$this->smarty->assign("seting", $this->seting);
		
		//print_r($this->auth);
	}
	
	public function index(){
		/*if($this->auth){
			$this->modul('awal');
		}else{
			
		}*/
		//echo $this->kode_voucher();
		$this->smarty->display('index.html');
	}
	
	private function cek_user(){
		if(!$this->auth){
			if($this->session->flashdata('error')){
				$this->smarty->assign("error", $this->session->flashdata('error'));
			}
			//$this->smarty->display('main-login.html');
			$this->smarty->display('index.html');
		}
	}
	
	function modul($mod,$p2="",$p3="",$p4=""){
		//print_r($this->auth);
		//print_r($this->modeling);exit;
		//echo $this->modeling['id'];exit;
		
		/*if($this->auth){
			$this->smarty->assign('mod',$mod);
			$this->smarty->assign('main',$p2);
			$this->smarty->assign('sub_mod',$p3);
			$temp=$mod.'/'.$p2.'.html';
			//echo $this->config->item('appl').APPPATH.'views/'.$temp;
			if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
			else{$this->smarty->display($temp);}
			
			
			//$this->smarty->display($mod.'/'.$p2.'.html');
		}*/
		//else{
			$temp='modul/'.$mod.".html";
			switch($mod){
				case "registrasi":
					$provinsi=$this->mhome->getdata('cl_propinsi');
					$tingkatan=$this->mhome->getdata('cl_tingkatan');
					$this->smarty->assign('provinsi',$provinsi);
					$this->smarty->assign('tingkatan',$tingkatan);
					$temp='modul/'.$mod.".html";
				break;
				case "profile":
					if($this->auth['cl_user_group_id']==3){
						$poin=$this->mhome->getdata('get_poin');
						$this->smarty->assign('poin',$poin);
					}
				break;	
				case "login":
					return $this->smarty->display('main-login.html'); 
				break;	
				default:$temp='modul/'.$mod.".html";
			}
			//$this->index();
			$this->smarty->assign('mod',$mod);
			$this->smarty->assign('temp',$temp);
			$this->smarty->assign('main',$p2);
			//echo $this->config->item('appl').APPPATH.'views/'.$temp;
			if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
			else{$this->smarty->display('index.html');}
		//}
	}
	
	/*
	function get_menu(){
		return $menu=$this->mhome->getdata('menu');
	}
	*/
	function konten($mod,$p2="",$p3="",$p4=""){
		
		if($this->auth){
			$temp='modul/'.$mod.".html";
			switch($mod){
				case "broadcast":
					$email=$this->mhome->getdata('get_email');
					$this->smarty->assign('email',$email);
				break;
				case "link":
					$this->smarty->assign('id_pel',$p2);
					$this->smarty->assign('temp',$temp);
					return $this->smarty->display('index.html');
				break;
				case "layout":
					$temp='template/konten_materi.html';
					$id_pel=$this->input->post('pel');
					$_POST['id']=$id_pel;
					$matpel=$this->mhome->getdata('cl_mata_pelajaran');
					$modul=$this->input->post('modul');
					$this->smarty->assign('mod',$modul);
					$this->smarty->assign('matpel',$matpel);
					$this->smarty->assign('id_pel',$id_pel);
					$this->smarty->assign('modul',$modul);
					//echo $id_pel;exit;
					//$temp='modul/'.$mod.".html";
					return $this->smarty->display($temp);
				break;
				case "video":
					$modul=$this->input->post('modul');
					$cek_bulanan=$this->mhome->getdata('cek_bulanan');
					$cek_poin=$this->mhome->getdata('cek_poin');
					if($cek_bulanan >= 0){
						$sub=$this->input->post('sub_bab');
						$data=$this->mhome->getdata('video',$modul);
						$this->smarty->assign('sub',$sub);
						$this->smarty->assign('data',$data);
						$this->smarty->assign('modul',$modul);
						$this->smarty->assign('mod',$modul);
					}else{
						if($cek_poin > 0){
							//echo $this->input->post('modul');exit;
							
							switch($modul){
								case "kelas":$poin_db=(int)$this->seting['poin_vid_materi'];break;
								case "un":$poin_db=(int)$this->seting['poin_vid_un'];break;
								case "smbptn":$poin_db=(int)$this->seting['poin_vid_smbptn'];break;
								case "banksoal":$poin_db=(int)$this->seting['poin_vid_lat'];break;
								default:$poin_db=10;
							}
							//echo $cek_poin.'->'.$poin_db;exit;
							if((int)$cek_poin >= $poin_db){
								$sub=$this->input->post('sub_bab');
								$data=$this->mhome->getdata('video',$modul);
								$this->smarty->assign('sub',$sub);
								$this->smarty->assign('data',$data);
								$this->smarty->assign('modul',$modul);
								$this->smarty->assign('mod',$modul);
							}else{
								$temp='pesan.html';
								$this->smarty->assign('msg','Mohon Maaf Poin Anda Kurang Untuk Menonton Materi Ini<br>Silahkan Membeli/Mengisi Voucher Anda.<br> Terima Kasih');
							}
						}else{
							$temp='pesan.html';
							$this->smarty->assign('msg','Mohon Maaf Poin Anda Sudah Habis <br>Silahkan Membeli/Mengisi Voucher Anda.<br> Terima Kasih');
						}
					}
					$this->smarty->assign('temp',$temp);
					return $this->smarty->display('index.html');
				break;
				case "prev":
					//echo "OKKKK";exit;
					$modul=$this->input->post('mod');
					$id_materi=$this->input->post('id_mat');
					$vid=$this->mhome->getdata('get_video',$id_materi);
					//print_r($vid);
					$this->smarty->assign('vid',$vid);
					$this->smarty->assign('modul',$modul);
				break;
				case "kelas":
					//$temp='modul/'.$mod.".html";
					$this->smarty->assign('mod',$mod);
					$this->smarty->assign('temp',$temp);
					$this->smarty->assign('main',$p2);
					if($this->auth['cl_user_group_id']==3){
						$pelajaran=$this->mhome->getdata('cl_mata_pelajaran',$this->auth["cl_kelas_id"]);
						$this->smarty->assign('pelajaran',$pelajaran);
					}
					return $this->smarty->display('index.html');
				break;
				case "banksoal":
					//$temp='modul/'.$mod.".html";
					$this->smarty->assign('mod',$mod);
					$this->smarty->assign('temp',$temp);
					$this->smarty->assign('cat',$p2);
					//echo $p2;
					$y=date('Y');
					$yl=((int)$y-5);
					$tahun = range($y, $yl);
					//$pelajaran=$this->mhome->getdata('cl_mata_pelajaran',$this->auth["cl_kelas_id"]);
					$this->smarty->assign('tahun',$tahun);
					return $this->smarty->display('index.html');
				break;
				case "bab":
					$this->smarty->assign('id',$this->input->post('id'));
					$this->smarty->assign('mod',$this->input->post('mod'));
					$this->smarty->assign('judul',$this->input->post('judul'));
					$bab=$this->mhome->getdata('bab');
					$this->smarty->assign('bab',$bab);
					//return $this->smarty->display('modul/bab.html');
				break;
				case "sub_bab":
					$this->smarty->assign('id_bab',$this->input->post('id_bab'));
					$this->smarty->assign('judul',$this->input->post('judul'));
					$sub_bab=$this->mhome->getdata('sub_bab');
					$this->smarty->assign('sub_bab',$sub_bab);
					return $this->smarty->display('modul/sub_bab.html');
				break;
				case "materi":
					$this->smarty->assign('id_sub_bab',$this->input->post('id_bab'));
					$this->smarty->assign('judul',$this->input->post('judul'));
					$materi=$this->mhome->getdata('materi');
					$this->smarty->assign('materi',$materi);
					//return $this->smarty->display('modul/materi.html');
				break;
				case "konten":
					$this->smarty->assign('id_sub_bab',$this->input->post('id'));
					$modul=$this->input->post('modul');
					$this->smarty->assign('modul',$modul);
					switch($modul){
						case "kelas":
							$konten=$this->mhome->getdata('konten');
						break;
						case "soal":
							$temp='modul/soal.html';
							$konten=$this->mhome->getdata('soal');
						break;
						
						default:
							$konten=$this->mhome->getdata('banksoal');
						break;
					}
					$this->smarty->assign('konten',$konten);
					//return $this->smarty->display('modul/konten.html');
				break;
				
				default:$temp='modul/'.$mod.".html";

			}
			$this->smarty->assign('mod',$mod);
			$this->smarty->assign('temp',$temp);
			$this->smarty->assign('main',$p2);
			//echo $this->config->item('appl').APPPATH.'views/'.$temp;
			if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
			else{$this->smarty->display($temp);}
		}else{
			return $this->smarty->display('index.html');
		}
	}
	function get_grid($mod,$table){
		if($this->auth['cl_user_group_id']==2){
			
		}
		$temp='modul/grid.html';
		$this->smarty->assign('mod',$mod);
		$this->smarty->assign('table',$table);
		if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
		else{$this->smarty->display($temp);}
	}
	function get_form($mod){
		if($this->auth){
			$temp='form/'.$mod.".html";
			$sts=$this->input->post('editstatus');
			$this->smarty->assign('sts',$sts);
			if($this->input->post('modul'))$this->smarty->assign('modul',$this->input->post('modul'));
			switch($mod){
				case "mata_pelajaran":
					$kelas=$this->mhome->getdata('cl_kelas','get');
					$this->smarty->assign('kelas',$kelas);
					if($sts=='edit'){
						$data=$this->mhome->getdata('cl_mata_pelajaran');
						$this->smarty->assign('data',$data);
					}
				break;
				case "bab":
					$kelas=$this->mhome->getdata('cl_kelas','get');
					//$pelajaran=$this->mhome->getdata('mata_pel');
					//print_r($pelajaran);
					$this->smarty->assign('kelas',$kelas);
					//$this->smarty->assign('pelajaran',$pelajaran);
					if($sts=='edit'){
						$data=$this->mhome->getdata('tbl_bab_pelajaran');
						//print_r($data);
						$this->smarty->assign('data',$data);
					}
				break;
				case "sub_bab":
					//$bab=$this->mhome->getdata('bab','all');
					//print_r($pelajaran);
					//$this->smarty->assign('bab',$bab);
					$kelas=$this->mhome->getdata('cl_kelas','get');
					$this->smarty->assign('kelas',$kelas);
					if($sts=='edit'){
						$data=$this->mhome->getdata('tbl_sub_bab_pelajaran');
						//print_r($data);
						$this->smarty->assign('data',$data);
					}
				break;
				case "materi":
					$sub_bab=$this->mhome->getdata('sub_bab','all');
					//print_r($pelajaran);
					$this->smarty->assign('sub_bab',$sub_bab);
					if($sts=='edit'){
						$data=$this->mhome->getdata('tbl_materi');
						//print_r($data);
						$this->smarty->assign('data',$data);
					}
				break;
				case "konten":
					$kelas=$this->mhome->getdata('cl_kelas','get');
					$this->smarty->assign('kelas',$kelas);
					if($sts=='edit'){
						$temp='form/edit_konten.html';
						$data=$this->mhome->getdata('tbl_konten_materi');
						$this->smarty->assign('data',$data);
					}
				break;
				case "un":
				case "smbptn":
				case "latihan":
				case "banksoal":
					$temp='form/banksoal.html';
					if($mod=='un')$flag = 'U';
					if($mod=='smbptn')$flag ='S';
					if($mod=='latihan')$flag ='L';
					$kelas=$this->mhome->getdata('cl_kelas','get',$mod);
					//$mat_pel=$this->mhome->getdata('bab','get');
					//$bab=$this->mhome->getdata('bab','get');
					//$materi=$this->mhome->getdata('tbl_materi','get');
					$this->smarty->assign('flag',$flag);
					$this->smarty->assign('kelas',$kelas);
					if($sts=='edit'){
						$data=$this->mhome->getdata('tbl_bank_soal');
						$this->smarty->assign('data',$data);
						//print_r($data);
					}
				break;
				
				case "kelas":
					if($sts=='edit'){
						$data=$this->mhome->getdata('cl_kelas');
						$this->smarty->assign('data',$data);
					}
				break;
				case "peserta":
				case "profile":
					if($mod=='profile')$temp='form/peserta.html';
					$kelas=$this->mhome->getdata('cl_kelas','get');
					$provinsi=$this->mhome->getdata('cl_propinsi','get');
					$this->smarty->assign('kelas',$kelas);
					$this->smarty->assign('provinsi',$provinsi);
					if($sts=='edit'){
						$data=$this->mhome->getdata('cl_peserta');
						$this->smarty->assign('data',$data);
					}
				break;
				case "guru":
					$kelas=$this->mhome->getdata('cl_kelas','get');
					$this->smarty->assign('kelas',$kelas);
					if($sts=='edit'){
						$data=$this->mhome->getdata('cl_guru');
					
						$this->smarty->assign('data',$data);
					}
				break;
			}
			$this->smarty->assign('mod',$mod);
			$this->smarty->assign('temp',$temp);
		
			if(!file_exists($this->config->item('appl').APPPATH.'views/'.$temp)){$this->smarty->display('konstruksi.html');}
			else{$this->smarty->display($temp);}
		
		}else{
			return $this->smarty->display('index.html');
		}
	}
	function getdata($p1,$p2="",$p3=""){
		echo $this->mhome->getdata($p1,$p2,$p3);
	}
	
	function simpansavedata($type="",$sts=""){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				//$post[$k] = $this->db->escape_str($this->input->post($k));
				$post[$k] = $this->input->post($k);
			}
			
		}
		
		if(isset($post['editstatus'])){$editstatus = $post['editstatus'];unset($post['editstatus']);}
		else $editstatus = $sts;
		
		//print_r($post);exit;
		echo $this->mhome->simpansavedata($type, $post, $editstatus);
	}
	function set_model(){
		$id_model=$this->input->post('id');
		$sts=$this->input->post('status');
		if($sts=='Y'){
			$data=$this->mhome->getdata('tbl_model','session',$id_model);
			$this->session->set_userdata($this->config->item('modeling'), base64_encode(serialize($data)));	
		}
		else{
			$data=array('id'=>0,'nama_model'=>'NO MODEL ACTIVATE');
			$this->session->unset_userdata($this->config->item('modeling'), 'limit');
		}
		echo json_encode($data);
	}
	
	function config_act($p1=""){
		$id_grid=$this->input->post('id_grid');
		$id_tree=$this->input->post('id_tree');
		echo $this->mhome->config_act($id_grid,$id_tree,$p1);
		
	}
	function import_data($p1,$p2,$obj='',$nama_file=''){
		echo $this->mhome->crud_file($p1,$p2,$obj,$nama_file);
		
	}
	function download_na($p1){
		//echo 1;
		$this->load->helper('download');
		$data = file_get_contents("__repository/template_import/".$p1.".xlsx");
		//echo $data;
		force_download('Template.xlsx', $data);
	}
	function get_report($p1){
		if($p1=='sum_fte' || $p1=='sum_exp'){
			$data=$this->mhome->get_report($p1);
			$this->smarty->assign('data',$data);
			$this->smarty->display('report/'.$p1.'.html');
		}
		else{
			echo $this->mhome->get_report($p1);
		}
	}
	
	function duplicate_model(){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				$post[$k] = $this->db->escape_str($this->input->post($k));
			}
			
		}
		echo $this->mhome->duplicate_model($post);
	}
	
	function getcost($p1="",$p2="",$p3,$p4){
		echo $this->mhome->get_cost($p1,$p2,$p3,$p4);
	}
	
	function copy_act($p1=""){
		echo $this->mhome->copy_act($p1);
	}
	
	function genCaptcha($rand){
		//echo $rand;exit;
		header("Content-type: image/jpeg");// out out the image
		$RandomStr = md5(microtime());// md5 to generate the random string
		$ResultStr = substr($RandomStr,0,5);//trim 5 digit
		$NewImage = imagecreatefromjpeg("__assets/images/back_captcha.jpg");//image create by existing image and as back ground
		$font='__assets/font/ROCCB.ttf';

		$TextColor = imagecolorallocate($NewImage, 1, 34, 128);//text color-white

		imagettftext($NewImage, 22, 4, 25, 25, $TextColor, $font, $ResultStr);

		$this->session->set_userdata("captcha", $ResultStr);
		imagejpeg($NewImage);//Output image to browser/**/
		ImageDestroy($image); //Free up resources
		exit();
	}
	
	function get_combo(){
		$mod=$this->input->post('v');
		$val=$this->input->post('v3');
		$bind=$this->input->post('v2');
		$data=$this->mhome->getdata($mod);
		$opt="<option value=''>--Pilih--</option>";
		
		foreach($data as $v){
			if($v['id']==$val)$sel="selected"; else $sel="";
			$opt .="<option value='".$v['id']."' ".$sel.">".$v['txt']."</option>";
		}
		echo $opt;
	}
	
	function checkCaptcha($txt){
		if($txt==$this->captcha){echo "sama";} else {echo "tidak sama";}	
	}
	
	function upload($p1){
		//print_r($_POST);exit;
		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
		switch($p1){
			case "cover":
				//echo "<pre>";print_r($_POST);echo"</pre>";
				$upload_dir = "__repository/cover/";
				if(!file_exists($upload_dir))mkdir($upload_dir, 0777, true);
				$upload_dir .=$this->auth['nama_user']."/";
				if(!file_exists($upload_dir))mkdir($upload_dir, 0777, true);
				$data=$this->input->post('data');
				//echo "<pre>";print_r($data);echo"</pre>";
				$idx=0;
				foreach($data['img'] as $x=>$v){
						$idx++;
					//echo $x;exit;
					//if($x=='img'){
						//$img = str_replace('data:image/png;base64,', '', $v);
						$img = str_replace('data:image/jpeg;base64,', '', $v);
						$img = str_replace(' ', '+', $img);
						$data = base64_decode($img);
						//$nama=date('YmdHis')."_".$idx.".png";
						$nama =$d->format("YmdHisu")."_".$idx.".jpeg";
						$file = $upload_dir.$nama;
						$success = file_put_contents($file, $data);
						if($success){
							$post=array('cover'=>$nama);
							$_POST['id']=$x;
							if($this->input->post('modul')=='materi' || $this->input->post('modul')=='konten'){
								$sts=$this->mhome->simpansavedata('tbl_file_materi',$post,'edit');
							}else{
								$sts=$this->mhome->simpansavedata('tbl_file_bank_soal',$post,'edit');
							}
						}
					//}
				}
				echo $sts;
			break;
			case "bank_soal":
			case "materi":
				$id_mat=$this->input->post('id_materi');
				$flag=$this->input->post('flag');
				$judul=$this->db->escape_str($this->input->post('judul'));
				$data=array('judul'=>$judul,
							'flag'=>$flag,
							'create_date'=>date('Y-m-d H:i:s'),
							'create_by'=>$this->auth['nama_user']
				);
				if($p1=="materi"){
					$data['tbl_konten_materi_id']=$id_mat;
					//$data['flag_video']='U';
					$upload_path='__repository/file_materi/';
					$tbl="tbl_file_materi";
				}
				else{
					$upload_path='__repository/bank_soal/';
					$data['tbl_bank_soal_id']=$id_mat;
					$tbl="tbl_file_bank_soal";
				}
				$object='file_materi';
				if(!file_exists($upload_path))mkdir($upload_path, 0777, true);
				$upload_path .=$this->auth['nama_user']."/";
				if(!file_exists($upload_path))mkdir($upload_path, 0777, true);
				if(isset($_FILES['file_materi'])){
					$file=$_FILES['file_materi']['name'];
					//$nameFile = $this->string_sanitize(pathinfo($file, PATHINFO_FILENAME));
					$nameFile=$d->format("YmdHisu");
						$upload=$this->lib->uploadnong($upload_path, $object, $nameFile);
						if($upload){
							$data['file_materi']=$upload;
							echo $this->mhome->simpansavedata($tbl,$data,'add');
						}else{
							echo 2;
						}
				}
			break;
			
		}
		
		
		
		//echo $upload;
	}
	function hapus_file(){
		if($this->auth){
			$mod=$this->input->post('mod');
			switch($mod){
				case "konten":
					$data=$this->mhome->getdata('tbl_file_materi','get');
					if(isset($data['file_materi'])){
						if($data['flag']=='U'){
							$path='__repository/file_materi/'.$data['create_by'].'/';
							chmod($path.$data['file_materi'],0777);
							unlink($path.$data['file_materi']);
						}
						echo $hapus=$this->mhome->simpansavedata('tbl_file_materi','','delete');
					}
				break;
			}
		}
	}
	function string_sanitize($s='VR SN230039 LINE 1(i) & 2') {
		//$s = "a[]^sdAA'uy ast!213%'";
		$result = preg_replace("/[^% a-zA-Z0-9]+/", "", $s);
		return $result;
		//echo $result;
	}	
	function tree($p1=""){
		if($this->auth){
			echo $this->mhome->getdata('tree_pelajaran',$p1);
		}
	}
	function get_waktu(){
		$this->load->helper('fungsi');
		$waktu=$this->input->post('waktu');
		//$waktu_data="02:00:00";
		//echo date_parse($waktu_data);exit;
		$waktu_data=$this->mhome->getdata('get_waktu');
		//print_r (date_parse($waktu_data["waktu_sisa"]));exit;
		
		if(isset($waktu_data["waktu_sisa"])){
			$sisa=hitung_waktu($waktu,$waktu_data["waktu_sisa"]);
			$data=array('cl_peserta_id'=>$this->auth["peserta_id"],
						'waktu_sisa'=>$sisa,
						'flag'=>1,
						'create_date'=>date('Y-m-d H:i:s'),
						'create_by'=>$this->auth["nama_user"]
			);
			$this->mhome->simpansavedata('tbl_waktu',$data,'add');
			echo $sisa;
		}else{
			echo "tidak Ada waktu ";
		}
		
		//echo $a."->".$get_jam."->".$get_data."->".$sisa;
	}
	function calc_poin(){
		echo $this->mhome->calc_poin();
	}
	
	function tes(){
		$this->smarty->assign('temp','tes.html');
		return $this->smarty->display('index.html');
	}
	function generate_voucher(){
		
		$qty=$this->input->post('qty');
		$nominal=$this->input->post('nominal');
		$voucher=array();
		$html="<ol>";
		for($i=0;$i<$qty;$i++){
			$v=$this->kode_voucher();
			$voucher[]=$v;
			$html .="<li>";
			$html .=$v;
			$html .="</li>";
		}
		$html .="</ol>";
		//echo $html;
		
		$simpan=$this->mhome->generate_voucher($voucher);
		if($simpan==1){
			echo json_encode(array('msg'=>1,'res'=>$html),true);
		}else{
			echo json_encode(array('msg'=>0,'res'=>$html),true);
		}
	}
	
	function cetak_voucher(){
		$this->load->library('mlpdf');	
		$data=$this->mhome->getdata('cetak_voucher');
		$pdf = $this->mlpdf->load();
		$this->smarty->assign('data', $data);
		
		$htmlcontent = $this->smarty->fetch("template/temp_pdf.html");
        $htmlheader = $this->smarty->fetch("template/header.html");
		
		//echo $htmlcontent;exit;
		
		$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 33, 20, 5, 2, 'P');
		$spdf->ignore_invalid_utf8 = true;
		// bukan sulap bukan sihir sim salabim jadi apa prok prok prok
		$spdf->allow_charset_conversion = true;     // which is already true by default
		$spdf->charset_in = 'iso-8859-1';  // set content encoding to iso
		$spdf->SetDisplayMode('fullpage');		
		$spdf->SetHTMLHeader($htmlheader);
		//$spdf->keep_table_proportions = true;
		$spdf->useSubstitutions=false;
		$spdf->simpleTables=true;
		
		$spdf->SetHTMLFooter('
			<div style="font-family:arial; font-size:8px; text-align:center; font-weight:bold;">
				<table width="100%" style="font-family:arial; font-size:8px;">
					<tr>
						<td width="30%" align="left">
							
						</td>
						<td width="40%" align="center">
							
						</td>
						<td width="30%" align="right">
							Hal. {PAGENO} dari {nbpg}
						</td>
					</tr>
				</table>
			</div>
		');				
		$filename = date('YmdHis');
		$spdf->SetProtection(array('print'));				
		$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
		//$spdf->Output('repositories/Dokumen_LS/LS_PDF/'.$filename.'.pdf', 'F'); // save to file because we can
		//$spdf->Output('repositories/Billing/'.$filename.'.pdf', 'F');
		$spdf->Output($filename.'.pdf', 'I'); // view file	
		
		
		
		
		
	}
	function isi_voucher(){
		$v1=$this->db->escape_str($this->input->post('v_1'));
		$v2=$this->db->escape_str($this->input->post('v_2'));
		$v3=$this->db->escape_str($this->input->post('v_3'));
		$v4=$this->db->escape_str($this->input->post('v_4'));
		$voucher=$v1."-".$v2."-".$v3."-".$v4;
		//CEK VOUCHER
		$cek=$this->mhome->getdata('cek_voucher',$voucher);
		echo json_encode($cek);
		
		
		
	}
}
