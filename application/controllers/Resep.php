<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('resep_model','resep');
	}

	public function index()
	{
		if($this->session->jenis == 'logistik'){
			redirect('/penjualan');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else{
			redirect();
		}
		$this->load->view('resep/resep_view');
	}

	public function mengambilResep()
	{
		$list = $this->resep->mengambilDataResep();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $resep) {
			$no++;
			$row = array();
			$row[] = $resep->jenis;
			$row[] = $resep->nomor;
			$row[] = $resep->nama_pasien;
			$row[] = $resep->nama_dokter;
		    $row[] = $resep->tarik;
		    $row[] = $resep->bungkus;
		   	$row[] = $resep->jumlah_bungkus;
		   	$row[] = $resep->cara_minum;
		   	$row[] = number_format($resep->harga);
		   	$row[] = $resep->tanggal;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_resep('."'".$resep->id_resep."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->resep->count_all(),
						"recordsFiltered" => $this->resep->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function detailResep($id)
	{
		$data = $this->resep->detailDataResep($id);
		echo json_encode($data);
	}
      
    public function tambahResep($jenis)
	{
		$insert = $this->resep->tambahDataresep($data);

		$resep = $this->resep->get_last_id();
		$id_resep = $resep->id_resep;

		$detail = $this->input->post('data');

		for($i = 0; $i < count($detail); $i++){

			$id_produk = $detail[$i][0];
			$jumlah = $detail[$i][1];


			$data = array(
					'id_resep' => $id_resep,
					'id_produk' => $id_produk,
					'jumlah' => $jumlah
			);

			$insert = $this->penjualan->tambahDataDetailResep($data);
		}

		echo json_encode(array("status" => TRUE));
	}

	public function hapusresep($id)
	{
		$this->resep->hapusDataresep($id);
		echo json_encode(array("status" => TRUE));
	}

}
