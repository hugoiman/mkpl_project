<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('dokter_model','dokter');
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
		$this->load->view('dokter/dokter_view');
}

	public function mengambilDokter()
	{
		$list = $this->dokter->mengambilDataDokter();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $dokter) {
			$no++;
			$row = array();
			$row[] = $dokter->nama;
			$row[] = $dokter->alamat;
			$row[] = $dokter->kategori;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_dokter('."'".$dokter->id_dokter."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="hapus_dokter('."'".$dokter->id_dokter."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->dokter->count_all(),
						"recordsFiltered" => $this->dokter->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->dokter->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->dokter->get_by_nama($nama);
		echo json_encode($data);
	}

	public function tambahDokter()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'kategori' => $this->input->post('kategori'),
			);
		$insert = $this->dokter->tambahDataDokter($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahdokter()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'kategori' => $this->input->post('kategori'),
			);
		$this->dokter->ubahDatadokter(array('id_dokter' => $this->input->post('id_dokter')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function hapusDokter($id)
	{
		$this->dokter->hapusDataDokter($id);
		echo json_encode(array("status" => TRUE));
	}

}
