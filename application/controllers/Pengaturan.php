<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengaturan_model','pengaturan');
	}

	public function index()
	{
		if($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('pengaturan/pengaturan_view');
	}

	public function mengambilPengaturanBungkus()
	{
		$output = $this->pengaturan->mengambilDataPengaturanBungkus();
		echo json_encode($output);
	}

	public function ubahPengaturanBungkus()
	{
		
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanBungkus($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanDokter()
	{
		$output = $this->pengaturan->mengambilDataPengaturanDokter();
		echo json_encode($output);
	}

	public function ubahPengaturanDokter()
	{
		
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanDokter($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanKategori()
	{
		$output = $this->pengaturan->mengambilDataPengaturanKategori();
		echo json_encode($output);
	}

	public function ubahPengaturanKategori()
	{
		
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanKategori($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanCatatanStruk()
	{
		$output = $this->pengaturan->mengambilDataPengaturanCatatanStruk();
		echo json_encode($output);
	}

	public function ubahPengaturanCatatanStruk()
	{
		
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanCatatanStruk($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanSatuan()
	{
		$output = $this->pengaturan->mengambilDataPengaturanSatuan();
		echo json_encode($output);
	}

	public function ubahPengaturanSatuan()
	{
		
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanSatuan($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanIpPrinter()
	{
		$output = $this->pengaturan->mengambilDataPengaturanIpPrinter();
		echo json_encode($output);
	}

	public function ubahPengaturanIpPrinter()
	{
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanIpPrinter($data);
		echo json_encode(array('status' => TRUE));
	}

	public function mengambilPengaturanPabrik()
	{
		$output = $this->pengaturan->mengambilDataPengaturanPabrik();
		echo json_encode($output);
	}

	public function ubahPengaturanPabrik()
	{
		$data = array( 
			'keterangan' => $this->input->post('data')
		);
		$output = $this->pengaturan->ubahDataPengaturanPabrik($data);
		echo json_encode(array('status' => TRUE));
	}

}
