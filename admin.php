<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('encryption'); //in controller
		$this->load->library('MyPHPMailer');
		$this->load->helper('url');
	}
	
   public function index() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		$ta = $this->session->userdata('admin_ta');
		if(empty($ta)){ $ta = date('Y'); } 
		
		$this->load->model('m_kelolakegiatan'); 
		$stats = $this->m_kelolakegiatan->get_statistik_dashboard($ta);
		
		$a['stat_diisi']       = (int) $stats->diisi;
		$a['stat_belum_diisi'] = (int) $stats->belum_diisi;
		$a['stat_sudah_acc']   = (int) $stats->sudah_acc;
		$a['stat_belum_acc']   = (int) $stats->belum_acc;

		$data_opd = $this->m_kelolakegiatan->get_statistik_per_opd($ta);
		
		$nama_opd = array(); 
		$opd_acc = array();      
		$opd_menunggu = array(); 
		$opd_belum = array();    

		if(!empty($data_opd)) {
			foreach($data_opd as $row){
				$nama_opd[]     = $row->nama_opd;       
				$opd_acc[]      = (int) $row->total_acc; 
				$opd_menunggu[] = (int) $row->total_menunggu; 
				$opd_belum[]    = (int) $row->total_belum; 
			}
		}
		
		$a['grafik_opd_nama']     = json_encode($nama_opd);
		$a['grafik_opd_acc']      = json_encode($opd_acc);
		$a['grafik_opd_menunggu'] = json_encode($opd_menunggu);
		$a['grafik_opd_belum']    = json_encode($opd_belum);

		$data_tim = $this->m_kelolakegiatan->get_statistik_per_tim($ta);
		
		$nama_tim = array(); 
		$tim_acc = array();      
		$tim_menunggu = array(); 
		$tim_belum = array();    
		
		if(!empty($data_tim)) {
			foreach($data_tim as $row){
				$nama_tim[]     = strtoupper($row->nama_tim);       
				$tim_acc[]      = (int) $row->total_acc; 
				$tim_menunggu[] = (int) $row->total_menunggu; 
				$tim_belum[]    = (int) $row->total_belum; 
			}
		}
		$a['grafik_tim_nama']     = json_encode($nama_tim);
		$a['grafik_tim_acc']      = json_encode($tim_acc);
		$a['grafik_tim_menunggu'] = json_encode($tim_menunggu);
		$a['grafik_tim_belum']    = json_encode($tim_belum);
		
		$a['page']	= "d_amain";
		$this->load->view('admin/index', $a);
	}
	
	public function kalender() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		$a['page']	= "f_kalender";
		
		$this->load->view('admin/index', $a);
	}

	public function pengguna() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}		
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		
		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('idp'));
		$nama					= addslashes($this->input->post('nama'));
		$alamat					= addslashes($this->input->post('alamat'));
		$kepsek					= addslashes($this->input->post('kepsek'));
		$nip_kepsek				= addslashes($this->input->post('nip_kepsek'));
		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if ($mau_ke == "act_edt") {
			if ($this->upload->do_upload('logo')) {
				$up_data	 	= $this->upload->data();
				
				$this->db->query("UPDATE tr_instansi SET nama = '$nama', alamat = '$alamat', kepsek = '$kepsek', nip_kepsek = '$nip_kepsek', logo = '".$up_data['file_name']."' WHERE id = '$idp'");

			} else {
				$this->db->query("UPDATE tr_instansi SET nama = '$nama', alamat = '$alamat', kepsek = '$kepsek', nip_kepsek = '$nip_kepsek' WHERE id = '$idp'");
			}		

			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated</div>");			
			redirect('index.php/admin/pengguna');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM tr_instansi WHERE id = '1' LIMIT 1")->row();
			$a['page']		= "f_pengguna";
		}
		
		$this->load->view('admin/index', $a);	
	}
	
	public function manage_admin() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM t_admin")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/manage_admin/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('idp'));
		$username				= addslashes($this->input->post('username'));
		$password				= md5(addslashes($this->input->post('password')));
		$nama					= addslashes($this->input->post('nama'));
		$nip					= addslashes($this->input->post('nip'));
		$level					= addslashes($this->input->post('level'));
		$unitkerja				= addslashes($this->input->post('unitkerja'));
		$email					= addslashes($this->input->post('email'));
		$cari					= addslashes($this->input->post('q'));

		
		if ($mau_ke == "del") {
			$this->db->query("DELETE FROM t_admin WHERE id = '$idu'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/manage_admin');
		} else if ($mau_ke == "cari") {
			$a['data']		= $this->db->query("SELECT * FROM t_admin WHERE nama LIKE '%$cari%' ORDER BY id DESC")->result();
			$a['page']		= "l_manage_admin";
		} else if ($mau_ke == "add") {
			$a['page']		= "f_manage_admin";
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * FROM t_admin WHERE id = '$idu'")->row();	
			$a['page']		= "f_manage_admin";
		} else if ($mau_ke == "act_add") {	
			$cek_user_exist = $this->db->query("SELECT username FROM t_admin WHERE username = '$username'")->num_rows();

			if (strlen($username) < 3) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Username minimal 4 huruf</div>");
				redirect('index.php/admin/manage_admin');
			} else if ($cek_user_exist > 0) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Username telah dipakai. Ganti yang lain..!</div>");
				redirect('index.php/admin/manage_admin');	
			} else {
				$this->db->query("INSERT INTO t_admin VALUES (NULL, '$username', '$password', '$nama', '$nip', '$level','$unitkerja','$email')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			redirect('index.php/admin/manage_admin');
		} else if ($mau_ke == "act_edt") {
			if ($password = md5("-")) {
				$this->db->query("UPDATE t_admin SET username = '$username', nama = '$nama', nip = '$nip', level = '$level', email='$email',id_unitkerja='$unitkerja' WHERE id = '$idp'");
			} else {
				$this->db->query("UPDATE t_admin SET username = '$username', password = '$password', nama = '$nama', nip = '$nip', level = '$level', email='$email',id_unitkerja='$unitkerja' WHERE id = '$idp'");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated </div>");			
			redirect('index.php/admin/manage_admin');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM t_admin LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_manage_admin";
		}
		
		$this->load->view('admin/index', $a);
	}

	public function get_klasifikasi() {
		$kode 				= $this->input->post('kode',TRUE);
		
		$data 				=  $this->db->query("SELECT id, kode, nama FROM ref_klasifikasi WHERE kode LIKE '%$kode%' ORDER BY id ASC")->result();
		
		$klasifikasi 		=  array();
        foreach ($data as $d) {
			$json_array				= array();
            $json_array['value']	= $d->kode;
			$json_array['label']	= $d->kode." - ".$d->nama;
			$klasifikasi[] 			= $json_array;
		}
		
		echo json_encode($klasifikasi);
	}
	
	//tambahan
	public function get_bidang() {
		$kode 				= $this->input->post('kpd_yth',TRUE);
		
		$data 				=  $this->db->query("SELECT * FROM m_bidang WHERE nama_bidang LIKE '%$kode%' ORDER BY id_bidang ASC")->result();
		
		$klasifikasi 		=  array();
        foreach ($data as $d) {
			$json_array				= array();
            $json_array['value']	= $d->id_bidang." - ".$d->nama_bidang;
			$json_array['label']	= $d->id_bidang." - ".$d->nama_bidang;
			$klasifikasi[] 			= $json_array;
		}
		
		echo json_encode($klasifikasi);
	}
	
	public function get_instansi_lain() {
		$kode 				= $this->input->post('dari',TRUE);
		
		$data 				=  $this->db->query("SELECT dari FROM t_surat_masuk WHERE dari LIKE '%$kode%' GROUP BY dari")->result();
		
		$klasifikasi 		=  array();
        foreach ($data as $d) {
			$klasifikasi[] 	= $d->dari;
		}
		
		echo json_encode($klasifikasi);
	}
	
	public function passwod() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ke				= $this->uri->segment(3);
		$id_user		= $this->session->userdata('admin_id');
		
		//var post
		$p1				= md5($this->input->post('p1'));
		$p2				= md5($this->input->post('p2'));
		$p3				= md5($this->input->post('p3'));
		
		if ($ke == "simpan") {
			$cek_password_lama	= $this->db->query("SELECT password FROM t_admin WHERE id = $id_user")->row();
			//echo 
			
			if ($cek_password_lama->password != $p1) {
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-error">Password Lama tidak sama</div>');
				redirect('index.php/admin/passwod');
			} else if ($p2 != $p3) {
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-error">Password Baru 1 dan 2 tidak cocok</div>');
				redirect('index.php/admin/passwod');
			} else {
				$this->db->query("UPDATE t_admin SET password = '$p3' WHERE id = ".$id_user."");
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-success">Password berhasil diperbaharui</div>');
				redirect('index.php/admin/passwod');
			}
		} else {
			$a['page']	= "f_passwod";
		}
		
		$this->load->view('admin/index', $a);
	}
	
	//login
	public function login() {
		$this->load->view('admin/login');
	}
	
	public function do_login() {
		$u 		= $this->security->xss_clean($this->input->post('u'));
		$ta 	= $this->security->xss_clean($this->input->post('ta'));
        $p 		= md5($this->security->xss_clean($this->input->post('p')));
         
		$q_cek	= $this->db->query("SELECT * FROM t_admin WHERE username = '".$u."' AND password = '".$p."'");
		$j_cek	= $q_cek->num_rows();
		$d_cek	= $q_cek->row();
		
		//echo $this->db->last_query();
		
        if($j_cek == 1) {
            $data = array(
                    'admin_id' => $d_cek->id,
                    'admin_user' => $d_cek->username,
                    'admin_nama' => $d_cek->nama,
                    'admin_ta' => $ta,
                    'admin_level' => $d_cek->level,
		    'admin_nip' => $d_cek->nip,
					'admin_unitkerja' => $d_cek->id_unitkerja,
					'admin_eselon' => $d_cek->id_eselon,
					'admin_email' => $d_cek->email,
					'admin_valid' => true
                    );
            $this->session->set_userdata($data);
            redirect('index.php/admin');
        } else {	
			$this->session->set_flashdata("k", "<div id=\"alert\" class=\"alert alert-error\">username or password is not valid</div>");
			redirect('index.php/admin/login');
		}
	}
	
	public function logout(){
        $this->session->sess_destroy();
		redirect('index.php/admin/login');
    }
	


	public function get_kegiatan() {
		$unitkerja=$this->input->post('unitkerja');
		$bidang = substr($unitkerja,1,4);
		$query 	=  $this->db->query("SELECT * FROM m_jeniskegiatan WHERE substring(id_jeniskegiatan,1,4)='$bidang'");
		?>
		<option value="Kosong">-- Pilih Nama Kegiatan--<?php echo $bidang;?></option>
		<?php
        foreach($query->result() as $row)
        { 
             echo "<option value='".$row->id_jeniskegiatan."'>".$row->nama_kegiatan."</option>";
        }
	}
	
	
public function konfirmasi() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		parse_str($_SERVER['QUERY_STRING'], $_GET);  
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$id_kontrak_konfirm		= addslashes($this->input->post('id_kontrak'));
		$cari					= addslashes($this->input->post('q'));
		
		if ($mau_ke == "edt") {
			$id_edit 		  = $this->input->get('konfirmasi_id');
			?>
			<script>
			//window.alert('<?php echo $id_edit;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select * from t_kontrak where id_kontrak='$id_edit'")->row();	
			$a['page']		= "f_konfirmasi";
		}
		else if ($mau_ke == "act_edt")
		{
				$this->db->query("update t_kontrak set flag_konfirm='1' where id_kontrak='$id_kontrak_konfirm'");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data sudah dikonfirmasi selesai</div>");
				redirect('index.php/admin/oi/');
		
		}
		
		$this->load->view('admin/index', $a);
	}




//dda
public function dda() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM t_list_tabel")->num_rows();
		$per_page		= 150;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/dda/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));
	
		//ambil variabel post
		
		$idp					= addslashes($this->input->post('idp'));
		$nip					= addslashes($this->input->post('nip'));
		//$tgl					= addslashes($this->input->post('tgl'));
		//$jam_keluar			= addslashes($this->input->post('jam_keluar'));
		//$jam_masuk			= addslashes($this->input->post('jam_masuk'));
		$status					= addslashes($this->input->post('status'));
		$kepentingan_kel		= addslashes($this->input->post('kepentingan_kel'));
		$kepentingan_uraian		= addslashes($this->input->post('kepentingan_uraian'));
				
		$cari					= addslashes($this->input->post('q'));
		$nippegawai=$this->session->userdata('admin_nip');

		//upload config 
		$config['upload_path'] 		= './upload/surat_masuk';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if($mau_ke == "del")
		{
			$id_delete = $this->input->get('delete_id');
			?>
			<script>
			//window.alert('<?php echo $id_delete;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select * from t_oi where id='$id_delete'")->row();	
			$a['page']		= "f_del";
		}
		else if ($mau_ke == "act_del") {
			$id_oi = addslashes($this->input->post('id'));
			$this->db->query("DELETE FROM t_oi WHERE id = '$id_oi'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/oi/');
			
		} 
		else if($mau_ke == "in")
		{
			$id_in = $this->input->get('in_id');;
			?>
			<script>
			//window.alert('<?php echo $id_in;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select * from t_oi where id='$id_in'")->row();	
			$a['page']		= "f_in";
		
		}
		else if ($mau_ke == "act_in") {
			$id_oi = addslashes($this->input->post('id'));
			date_default_timezone_set("Asia/Jakarta");
			$tanggal_in=date("H:i");
			$this->db->query("UPDATE t_oi SET jam_masuk='$tanggal_in' where id='$id_oi'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/oi/');
			
		} 
		else if($mau_ke == "konfirmasi")
		{
			$id_konfirmasi = $this->input->get('konfirmasi_id');;
			?>
			<script>
			//window.alert('<?php echo $id_konfirmasi;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select l.id, l.judul_ind, l.judul_en, l.id_unitkerja, m.unitkerja_ind from t_list_tabel l left join m_unitkerja m on l.id_unitkerja=m.id_unitkerja where l.id='$id_konfirmasi'")->row();	
			$a['page']		= "f_konfirmasi";
		
		}
		else if ($mau_ke == "act_konfirmasi") {
			$id_data = addslashes($this->input->post('id'));
			$catatan = addslashes($this->input->post('catatan'));
			
			//$confirmed_by=$this->session->userdata('admin_nama');
			$this->db->query("UPDATE t_list_tabel SET is_confirm='1', catatan ='$catatan' where id='$id_data'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated</div>");
			redirect('index.php/admin/dda/');
		} 
		else if($mau_ke == "periksa")
		{
			$id_konfirmasi = $this->input->get('konfirmasi_id');;
			?>
			<script>
			//window.alert('<?php echo $id_konfirmasi;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select l.id, l.judul_ind, l.judul_en, l.id_unitkerja, m.unitkerja_ind from t_list_tabel l left join m_unitkerja m on l.id_unitkerja=m.id_unitkerja where l.id='$id_konfirmasi'")->row();	
			$a['page']		= "f_periksa";
		
		}
		else if ($mau_ke == "act_periksa") {
			$id_data = addslashes($this->input->post('id'));
			$catatan_periksa = addslashes($this->input->post('catatan_periksa'));
			
			//$confirmed_by=$this->session->userdata('admin_nama');
			$this->db->query("UPDATE t_list_tabel SET is_confirm='1',catatan='$catatan_periksa' where id='$id_data'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated</div>");
			redirect('index.php/admin/dda/');
		} 
		else if ($mau_ke == "cari") {

			$a['data']		= $this->db->query("SELECT * from t_oi where keterangan_uraian  LIKE '%$cari%'")->result();
			$a['page']		= "l_oi";
			
		} else if ($mau_ke == "add") {
		
			$a['page']		= "f_oi";
			
		} else if ($mau_ke == "edt") {

			$a['datpil']	= $this->db->query("SELECT * from t_oi WHERE id = '$idu'")->row();	
			$a['page']		= "f_oi";
		} 
		else if ($mau_ke == "act_add") {	
				$hariini=date('Y-m-d');
				date_default_timezone_set("Asia/Jakarta");
				$waktusekarang= date("H:i");
			
				$this->db->query("INSERT INTO t_oi VALUES (NULL, '$nippegawai', '$hariini', '$waktusekarang','','T','$kepentingan_kel','$kepentingan_uraian','','','')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
				redirect('index.php/admin/dda');

		}
		else if ($mau_ke == "act_edt") {
			?>
			<script>
			//window.alert('<?php echo $idu;?>');</script>
			<?php
				$this->db->query("UPDATE t_oi SET kepentingan_kel ='$kepentingan_kel', kepentingan_uraian= '$kepentingan_uraian' where id='$idp'");

				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated</div>");
				redirect('index.php/admin/dda/');
		}
		else if ($mau_ke == "kondef") {
			$id_tabel = $this->input->get('id');
			$a['datpil']	= $this->db->query("SELECT * from t_list_tabel WHERE id = '$id_tabel'")->row();	
			$a['page']		= "f_kondef";
		}
		else if ($mau_ke == "kondef_update") {
			$idp					= addslashes($this->input->post('idp'));
			$kondef					= addslashes($this->input->post('kondef'));
			$a['datpil']			= $this->db->query("update t_list_tabel set kondef='$kondef' WHERE id = '$idp'");	
			redirect('index.php/admin/dda/');
		}
		else if ($mau_ke == "view_tabel")
		{
			$id_unitkerja = $this->input->get('id');
			$a['data']		= $this->db->query("SELECT * FROM t_list_tabel where id_unitkerja='$id_unitkerja' and tahun='$ta' ")->result();
			$a['page']		= "l_dda";	
		}
		else if ($mau_ke == "periksa_tabel")
		{
			$id_unitkerja = $this->input->get('id');
			if($this->session->userdata('admin_user') == 'diseminasi'){
			$a['data']		= $this->db->query("SELECT * FROM t_list_tabel where id_unitkerja='$id_unitkerja' and tahun='$ta' LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_dda_periksa";	
			}
			else
			{
			$a['data']		= $this->db->query("SELECT * FROM t_list_tabel where id_unitkerja='$id_unitkerja' and is_confirm='1' and tahun='$ta' LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_dda_periksa";	
			}
		}
		else 
		{
			$unitkerjalogin=$this->session->userdata('admin_unitkerja');
			
			if ($unitkerjalogin == 'bps')
			{
			/* pagination */	
			$total_row		= $this->db->query("SELECT * FROM m_unitkerja")->num_rows();
			$per_page		= 150;
			
			$awal	= $this->uri->segment(4); 
			$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
			
			if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
			$akhir	= $per_page;
			
			$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/dda/p");
			
			if($this->session->userdata('admin_level') == 'Admin'){
				$a['data']		= $this->db->query("SELECT * FROM m_unitkerja order by unitkerja_ind LIMIT $awal, $akhir")->result();
				//$a['datadiperiksa'] = $this->db->query("SELECT * FROM m_unitkerja LIMIT $awal, $akhir")->result();
			}else {
				$user_wali = $this->session->userdata('admin_user') ; 
				$user_level= $this->session->userdata('admin_level') ; 
				if($user_level == 'lo')
				{
				$a['data']		= $this->db->query("SELECT * FROM m_unitkerja where user_wali='$user_wali'")->result();
				}
				else if($user_level == 'spv')
				{
				$a['data'] = $this->db->query("SELECT * FROM m_unitkerja where user_spv='$user_wali'")->result();
				}
			}			 
			 
			 $a['page']		= "l_dda_admin";
			}
			else
			{
			 $a['data']		= $this->db->query("SELECT * FROM t_list_tabel where id_unitkerja='$unitkerjalogin' and tahun='$ta' ")->result();
			 $a['page']		= "l_dda";	
			}
		}
		
		$this->load->view('admin/index', $a);
	}

public function forum_diskusi()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		//Setting Email
		$mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        /*$mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = "ssl://smtp.gmail.com";      // setting GMail as our SMTP server
        $mail->Port       = 465;                   // SMTP port to connect to GMail
        $mail->Username   = "dda.jateng@gmail.com";  // user email address
        $mail->Password   = "dd4j4t3ng3300";            // password in GMail
        $mail->SetFrom('dda.jateng@gmail.com', 'Jawa Tengah Dalam Angka');  //Who is sending the email
        //$mail->AddReplyTo("datakita1612@gmail.com","Data Kita");  //email address that receives the response
        $mail->Subject    = "Forum Diskusi Jawa Tengah Dalam Angka";
        //$mail->Body      = "<h1>Hallo, hanya memastikan saja mas :)</h1>";
        //$mail->AltBody    = "coba coba";
        //$destino = "rizchi.ew@gmail.com"; // Who is addressed the email to
        //$mail->AddAddress($destino, "Cahya Dy");

        //$mail->AddAttachment("");      // some attached files
        //$mail->AddAttachment(""); // as many as you want*/
		
		$mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = "ssl://smtp.bps.go.id";      // setting GMail as our SMTP server
        $mail->Port       = 465;                   // SMTP port to connect to GMail
        $mail->Username   = "ipds3300@bps.go.id";  // user email address
        $mail->Password   = "oapeaa360";            // password in GMail
        $mail->SetFrom('ipds3300@bps.go.id', 'Bidang IPDS BPS. Prov Jawa Tengah');  //Who is sending the email
        $mail->Subject    = "Forum Diskusi Jawa Tengah Dalam Angka";
        if($mail->Send()) {
		$data["message"] = "Message sent correctly!";
        } 
		else {
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
        //$this->load->view('sent_mail.php',$data);
		// end of setting email
		
		$ta = $this->session->userdata('admin_ta');
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM m_unitkerja")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/forum_diskusi/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));
		$unitkerjalogin			= $this->session->userdata('admin_unitkerja');
		$email 					= $this->session->userdata('admin_email');
		$nama 					= $this->session->userdata('admin_nama');
		
		if($mau_ke == 'view_topik')
		{	
			$id_unitkerja = $this->input->get('id');
			if($unitkerjalogin == 'bps' || $this->session->userdata('admin_level') == 'kominfo')
			{
				$a['data']		= $this->db->query("SELECT * FROM m_forumtopic where id_unitkerja='$id_unitkerja' LIMIT $awal, $akhir ")->result();
				$a['page']		= "l_forum_topik";
			}
			else
			{
			  $a['data']		= $this->db->query("SELECT * FROM m_forumtopic where id_unitkerja='$unitkerjalogin' LIMIT $awal, $akhir ")->result();
			  $a['page']		= "l_forum_topik";
			}	
		}
		else if ($mau_ke == 'add_topik')
		{
			$a['page']		= "f_tambah_topik";	
		}
		else if ($mau_ke == 'act_add_topik')
		{
			$hariini=date('Y-m-d');
			$nama_topik					= addslashes($this->input->post('nama_topik'));
			$id_unitkerja				= addslashes($this->input->post('id_unitkerja'));
			$user_id					= $this->session->userdata('admin_user');
			//$id_unitkerjatopik			= $this->input->get('id');
			$dataemail					= $this->db->query("select * from t_admin where id_unitkerja='$id_unitkerja'")->row();
			$emailtopik					=$dataemail->email;
			$namatopik					=$dataemail->nama;
			
			$data_lo				= $this->db->query("select * from m_unitkerja where id_unitkerja='$id_unitkerja'")->row();
			$lo						= $data_lo->user_wali;
			$dataemail_lo			= $this->db->query("select * from t_admin where username='$lo'")->row();
			$email_lo				= $dataemail_lo->email;
			
			$mail->Body      = $nama_topik." telah ditambahkan";
			//$mail->AltBody    = "coba coba";
			//$destino = "rizchi.ew@gmail.com"; // Who is addressed the email to
			$mail->AddAddress($emailtopik,$namatopik);
			$mail->AddCC($email_lo);
			
			$mail->Send();
			$this->db->query("INSERT INTO m_forumtopic VALUES (NULL, '$id_unitkerja','$nama_topik','$user_id','$hariini')");
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Topik Telah Ditambahkan</div>");
			redirect('index.php/admin/forum_diskusi/'.$unitkerjalogin.'');
		}
		else if ($mau_ke == 'view_comment')
		{
			$id_unitkerja 				= $this->input->get('id_unitkerja');
			$id_topik					= $this->input->get('id_topik');
			$a['data']					= $this->db->query("SELECT * FROM forumcomment where id_topik='$id_topik' LIMIT $awal, $akhir ")->result();
			$a['page']					= "l_forum_komen";
		}
		else if ($mau_ke == 'add_comment')
		{
			$hariini=date('Y-m-d');
			$comment					= addslashes($this->input->post('comment'));
			$id_topik					= addslashes($this->input->post('id_topik'));
			$id_unitkerja 				= addslashes($this->input->post('id_unitkerja'));
			$dataemail					= $this->db->query("select * from t_admin where id_unitkerja='$id_unitkerja'")->row();
			$emailcomment				= $dataemail->email;
			$namacomment				= $dataemail->nama;
			
			$data_lo				= $this->db->query("select * from m_unitkerja where id_unitkerja='$id_unitkerja'")->row();
			$lo						= $data_lo->user_wali;
			$dataemail_lo			= $this->db->query("select * from t_admin where username='$lo'")->row();
			$email_lo				= $dataemail_lo->email;
			
			$user_id					= $this->session->userdata('admin_user');
			
			$mail->Body      			= $comment." telah ditambahkan";
			$mail->AddAddress($emailcomment,$namacomment);
			$mail->AddCC($email_lo);
			$mail->Send();
			
			$this->db->query("INSERT INTO forumcomment VALUES (NULL,'$id_topik','$comment','$user_id','$hariini')");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Komentar Telah Ditambahkan</div>");
			redirect('index.php/admin/forum_diskusi/view_comment?id_unitkerja='.$id_unitkerja.'&id_topik='.$id_topik.'');
		}
		else
		{
			if ($unitkerjalogin == 'bps')
			{
				if($this->session->userdata('admin_user') == 'diseminasi'){
					$a['data']		= $this->db->query("SELECT * FROM m_unitkerja LIMIT $awal, $akhir")->result();
				}else {
					$user_wali = $this->session->userdata('admin_user') ; 
					$user_level= $this->session->userdata('admin_level') ; 
					if($user_level == 'lo')
					{
					$a['data']		= $this->db->query("SELECT * FROM m_unitkerja where user_wali='$user_wali'")->result();
					}
					else if($user_level == 'spv')
					{
					$a['data'] = $this->db->query("SELECT * FROM m_unitkerja where user_spv='$user_wali'")->result();
					}
					}	
					$a['page']		= "l_forum";	
			}
			else
			{
			  $a['data']		= $this->db->query("SELECT * FROM m_unitkerja where id_unitkerja='$unitkerjalogin' LIMIT $awal, $akhir ")->result();
			  $a['page']		= "l_forum";	
			}
			if($this->session->userdata('admin_level') == 'kominfo')
			{
			$a['data']		= $this->db->query("SELECT * FROM m_unitkerja LIMIT $awal, $akhir")->result();
			$a['page']		= "l_forum";		
			}
		}
		$this->load->view('admin/index', $a);
	}

	//master_tabel
public function master_tabel() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');$a['list_opd'] = $this->db->query("SELECT * FROM m_unitkerja ORDER BY unitkerja_ind ASC")->result();
		
		$a['list_opd'] = $this->db->query("SELECT * FROM m_unitkerja ORDER BY unitkerja_ind ASC")->result();
		$a['list_tim'] = $this->db->query("SELECT DISTINCT user_wali FROM m_unitkerja WHERE user_wali != '' AND user_wali != '-' ORDER BY user_wali ASC")->result();

		$total_row = $this->db->query("SELECT * FROM t_list_tabel")->num_rows();
        $per_page  = 20;
        
        $awal = $this->uri->segment(4); 
        $awal = (empty($awal) || $awal == 1) ? 0 : $awal;
        if (empty($awal)) { $awal = 0; }
        
        $a['pagi'] = _page($total_row, $per_page, 4, base_url()."index.php/admin/master_tabel/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));
	
		//ambil variabel post
		$idp					= addslashes($this->input->post('idp'));
		$judul_ind				= addslashes($this->input->post('judul_ind'));
		$judul_en				= addslashes($this->input->post('judul_en'));
		$link_tabel				= addslashes($this->input->post('link_tabel'));
		$link_sebelumnya		= addslashes($this->input->post('link_sebelumnya'));
		$id_unitkerja			= addslashes($this->input->post('id_unitkerja'));
		$is_confirm				= '2';
		$catatan				= '-';
		$is_periksa				= '0';
		$catatan_periksa			= '-';
		$kondef					= '-';

		
				
		$cari					= addslashes($this->input->post('q'));
			
		if($mau_ke == "del")
		{
			$id_delete = $this->input->get('delete_id');;
			?>
			<script>
			//window.alert('<?php echo $id_delete;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select * from t_list_tabel where id='$id_delete'")->row();	
			$a['page']		= "f_del_master";
		}
		else if ($mau_ke == "act_del") {
			$id_oi = addslashes($this->input->post('id'));
			$this->db->query("DELETE FROM t_list_tabel WHERE id = '$id_oi'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/master_tabel/');
		} 
		else if ($mau_ke == "cari") {

			$a['data']		= $this->db->query("SELECT t.*,m.* from t_list_tabel t inner join m_unitkerja m on t.id_unitkerja=m.id_unitkerja where tahun='$ta' and (t.judul_ind  LIKE '%$cari%' or m.unitkerja_ind LIKE '%$cari%') ")->result();
			$a['page']		= "l_master_tabel";
			
		} else if ($mau_ke == "add") {
		
			$a['page']		= "f_master_tabel";
			
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * from t_list_tabel WHERE id = '$idu'")->row();	
			$a['page']		= "f_master_tabel";
		} 
		else if ($mau_ke == "act_add") {	
				$hariini=date('Y-m-d');
				date_default_timezone_set("Asia/Jakarta");
				$waktusekarang= date("H:i");
			
				$this->db->query("INSERT INTO t_list_tabel VALUES (NULL, '$judul_ind', '$judul_en', '$link_tabel', '$link_sebelumnya','$id_unitkerja','$is_confirm','$catatan','$is_periksa','$catatan_periksa','$kondef')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil ditambahkan</div>");
				redirect('index.php/admin/master_tabel/');
		}
		else if ($mau_ke == "act_edt") {
				$this->db->query("UPDATE t_list_tabel SET judul_ind ='$judul_ind', judul_en= '$judul_en', link_tabel = '$link_tabel', link_sebelumnya = '$link_sebelumnya', id_unitkerja='$id_unitkerja' where id='$idp'");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil di update</div>");
				redirect('index.php/admin/master_tabel/');
		}
		else 
    	{
		$filter_opd = $this->input->get('filter_opd');
		$filter_bidang = $this->input->get('filter_bidang');
        $a['selected_opd'] = $filter_opd;
		$a['selected_bidang'] = $filter_bidang; 

		$sql_base = "FROM t_list_tabel l 
                 LEFT JOIN m_unitkerja u ON l.id_unitkerja = u.id_unitkerja";
        $sql_where = "WHERE l.tahun='$ta'";

        if (!empty($filter_opd) && $filter_opd != 'all') {
            $sql_where .= " AND l.id_unitkerja = '$filter_opd'";
        }
		else if (!empty($filter_bidang) && $filter_bidang != 'all') {
        $sql_where .= " AND u.user_wali = '$filter_bidang'";
    	}

        $a['data'] = $this->db->query("
            SELECT l.*, u.unitkerja_ind, u.user_wali 
			$sql_base 
			$sql_where 
			ORDER BY l.id ASC 
			LIMIT $awal, $per_page
        ")->result();

        $a['page'] = "l_master_tabel"; 
    }
		$this->load->view('admin/index', $a);
	}
	
	
	//REPORT
	public function report()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/report");
		}
		$jenis_rekap = $this->input->post('jenis_rekap');
		if($jenis_rekap == '0')
		{
			$data_byjk	= $this->db->query("SELECT t.id_unitkerja,u.unitkerja_ind,count(judul_ind) as jumlah_tabel,sum(case when t.is_confirm = 1 then 1 else 0 end ) as terentri FROM `t_list_tabel` t left join m_unitkerja u on  t.id_unitkerja=u.id_unitkerja group by id_unitkerja order by u.id_unitkerja ")->result();
			$a['page']		= "view_report";
		}
	
		$a['page']	= "report";
		$this->load->view('admin/index', $a);
	}

	
	public function master_tabel_opd() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM m_master_tabel_usulan")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/master_tabel_opd/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));
	
		//ambil variabel post
		$idp					= addslashes($this->input->post('idp'));
		$judul_ind				= addslashes($this->input->post('judul_ind'));
		$judul_en				= addslashes($this->input->post('judul_en'));
		$unitkerja				= $this->session->userdata('admin_unitkerja');
		$addby					= $this->session->userdata('admin_user');
		$is_setujui				= '0';
				
		$cari					= addslashes($this->input->post('q'));
		
		//upload config 
		$config['upload_path'] 		= './upload/tabel_usulan/';
		$config['allowed_types'] 	= 'xls|xlsx|doc|docx';
		$config['max_size']			= '10000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';
		$kode						= rand(1,1000);
		$config['file_name'] 		= $kode.'_'.$unitkerja.'_'.$addby; 

		$this->load->library('upload', $config);
		
		if($mau_ke == "del")
		{
			$id_delete = $this->input->get('delete_id');;
			?>
			<script>
			//window.alert('<?php echo $id_delete;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select * from m_master_tabel_usulan where id='$id_delete'")->row();	
			$a['page']		= "f_del_master";
		}
		else if ($mau_ke == "act_del") {
			$id = addslashes($this->input->post('id'));
			$this->db->query("DELETE FROM m_master_tabel_usulan WHERE id = '$id'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/master_tabel_opd/');
		} 
		else if ($mau_ke == "cari") {

			$a['data']		= $this->db->query("SELECT * from t_list_tabel where keterangan_uraian  LIKE '%$cari%' and tahun='$ta'")->result();
			$a['page']		= "l_master_tabel";
		} else if ($mau_ke == "add") {
		
			$a['page']		= "f_add_opd";
			
		} else if ($mau_ke == "edt") {
			
			$a['datpil']	= $this->db->query("SELECT * from m_master_tabel_usulan WHERE id = '$idu'")->row();	
			$a['page']		= "f_add_opd";
		} 
		else if ($mau_ke == "act_add") {	
				$hariini=date('Y-m-d');
				date_default_timezone_set("Asia/Jakarta");
				$waktusekarang= date("H:i");
				if ($this->upload->do_upload('file_tabel')) 
					{
						$up_data	 	= $this->upload->data();
						$this->db->query("INSERT INTO m_master_tabel_usulan VALUES (NULL, '$judul_ind', '$judul_en', '".$up_data['file_name']."','$is_setujui', '$unitkerja', '$addby')");
					} 
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Tabel berhasil ditambahkan</div>");
				redirect('index.php/admin/');
		}
		else if ($mau_ke == "act_edt") {
				$id_unitkerja				= addslashes($this->input->post('id_unitkerja'));
				$this->db->query("UPDATE m_master_tabel_usulan SET is_setujui ='1' where id='$idp'");
				$this->db->query("INSERT INTO t_list_tabel VALUES (NULL, '$judul_ind', '$judul_en', '', '','$id_unitkerja','','','','','-')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil di konfirmasi</div>");
				redirect('index.php/admin/master_tabel_opd/');
		}
		else 
		{
			 $a['data']		= $this->db->query("SELECT l.*,u.unitkerja_ind FROM m_master_tabel_usulan l left join m_unitkerja u on l.id_unitkerja=u.id_unitkerja LIMIT $awal, $akhir ")->result();
			 $a['page']		= "l_master_tabel_opd";	
		}
		$this->load->view('admin/index', $a);
	}
	
	
	public function master_opd() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM m_unitkerja")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/master_opd/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel Postingan
		$idp			= addslashes($this->input->post('idp'));
		$id_unitkerja	= addslashes($this->input->post('id_unitkerja'));
		$unitkerja_ind	= addslashes($this->input->post('unitkerja_ind'));
		$unitkerja_en	= addslashes($this->input->post('unitkerja_en'));
		$user_wali		= addslashes($this->input->post('user_wali'));
		$user_spv		= addslashes($this->input->post('user_spv'));

		
		if ($mau_ke == "del") {
			$this->db->query("DELETE FROM m_unitkerja WHERE id_unitkerja = '$idu'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/master_opd');
		} else if ($mau_ke == "cari") {
			$a['data']		= $this->db->query("SELECT * FROM m_unitkerja WHERE unitkerja_ind LIKE '%$cari%' OR id_unitkerja LIKE '%$cari%' ORDER BY id_unitkerja DESC")->result();
			$a['page']		= "l_unitkerja";
		} else if ($mau_ke == "add") {
			$a['page']		= "f_unitkerja";
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * FROM m_unitkerja WHERE id_unitkerja = '$idu'")->row();	
			$a['page']		= "f_unitkerja";
		} else if ($mau_ke == "act_add") {	
			$cek_user_exist = $this->db->query("SELECT id_unitkerja FROM m_unitkerja WHERE id_unitkerja = '$id_unitkerja'")->num_rows();

			if (strlen($id_unitkerja) < 3) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">ID Unitkerja minimal 4 huruf</div>");
				redirect('index.php/admin/master_opd');
			} else if ($cek_user_exist > 0) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">OPD telah ditambahkan. Ganti yang lain..!</div>");
				redirect('index.php/admin/master_opd');	
			} else {
				$this->db->query("INSERT INTO m_unitkerja VALUES ('$id_unitkerja', '$unitkerja_ind', '$unitkerja_en', '$user_wali', '$user_spv')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			redirect('index.php/admin/master_opd');
		} else if ($mau_ke == "act_edt") {
			 
				$this->db->query("UPDATE m_unitkerja SET id_unitkerja = '$id_unitkerja', unitkerja_ind = '$unitkerja_ind', unitkerja_en = '$unitkerja_en', user_wali = '$user_wali', user_spv = '$user_spv' WHERE id_unitkerja = '$idp'");
			
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated </div>");			
			redirect('index.php/admin/master_opd');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM m_unitkerja LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_unitkerja";
		}
		
		$this->load->view('admin/index', $a);
	}

 public function set_tahun($tahun=null)
    {
        if(!$tahun)
            $tahun=date('Y');
        
        $this->session->set_userdata('admin_ta',$tahun);
        redirect('index.php/admin/index');
    }
	
    public function act_update_isi() {
        $id_tabel = $this->input->post('id_tabel');
        $catatan  = $this->input->post('catatan_periksa');
        
        $data = array(
            'is_periksa' => '1',
            'catatan_periksa' => $catatan
        );
        
        $this->db->where('id', $id_tabel);
        $this->db->update('t_list_tabel', $data);

        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Status berhasil diperbarui!</div>");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function batal_isi($id) {
        $data = array(
            'is_periksa' => '0',
            'catatan_periksa' => '-' 
        );
        $this->db->where('id', $id);
        $this->db->update('t_list_tabel', $data);
        redirect($_SERVER['HTTP_REFERER']);
    }	
}
