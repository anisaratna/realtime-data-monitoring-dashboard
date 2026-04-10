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

}	
	
	
