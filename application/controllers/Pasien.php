<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pasien_model','pasien');
	}

	public function index()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else{
			redirect();
		}
		$this->load->view('pasien/pasien_view');
}

	public function mengambilPasien()
	{
		$list = $this->pasien->mengambilDataPasien();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pasien) {
			$no++;
			$row = array();
			$row[] = $pasien->nama;
			$row[] = $pasien->alamat;
			$row[] = $pasien->nomor_telepon;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_pasien('."'".$pasien->id_pasien."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="hapus_pasien('."'".$pasien->id_pasien."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->pasien->count_all(),
						"recordsFiltered" => $this->pasien->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->pasien->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->pasien->get_by_nama($nama);
		echo json_encode($data);
	}

	public function tambahPasien()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'nomor_telepon' => $this->input->post('nomor_telepon'),
			);
		$insert = $this->pasien->tambahDataPasien($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahPasien()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'nomor_telepon' => $this->input->post('nomor_telepon'),
			);
		$this->pasien->ubahDatapasien(array('id_pasien' => $this->input->post('id_pasien')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function hapusPasien($id)
	{
		$this->pasien->hapusDataPasien($id);
		echo json_encode(array("status" => TRUE));
	}

}
