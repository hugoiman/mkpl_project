<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('produk_model','produk');
	}

	public function index()
	{
		if($this->session->jenis == 'logistik'){
			$this->load->view('header/navigation_logistik');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('produk/produk_view');
}

	public function mengambilProduk()
	{
		$list = $this->produk->mengambilDataProduk();
		$data = array();

		$no = $_POST['start'];
		foreach ($list as $produk) {
			$no++;
			$row = array();
			$row[] = $produk->nama;
			$row[] = $produk->alamat_rak;
			$row[] = $produk->kategori;
			$row[] = $produk->satuan;
			$row[] = $produk->stok_kritis;
			$row[] = $produk->laba;
			$row[] = $produk->ppn;
			$row[] = $produk->pabrik;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_produk('."'".$produk->id_produk."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="hapus_produk('."'".$produk->id_produk."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->produk->count_all(),
						"recordsFiltered" => $this->produk->count_filtered(),
						"data" => $data,
				);

		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->produk->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->produk->get_by_nama($nama);
		echo json_encode($data);
	}

	public function tambahProduk()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat_rak' => $this->input->post('alamat_rak'),
				'kategori' => $this->input->post('kategori'),
				'satuan' => $this->input->post('satuan'),
				'stok_kritis' => $this->input->post('stok_kritis'),
				'laba' => $this->input->post('laba'),
				'ppn' => $this->input->post('ppn'),
				'pabrik' => $this->input->post('pabrik'),
			);
		$insert = $this->produk->tambahDataProduk($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahProduk()
	{
		$data = array(
				'nama' => $this->input->post('nama'),
				'alamat_rak' => $this->input->post('alamat_rak'),
				'kategori' => $this->input->post('kategori'),
				'satuan' => $this->input->post('satuan'),
				'stok_kritis' => $this->input->post('stok_kritis'),
				'laba' => $this->input->post('laba'),
				'ppn' => $this->input->post('ppn'),
				'pabrik' => $this->input->post('pabrik'),
			);
		$this->produk->ubahDataProduk(array('id_produk' => $this->input->post('id_produk')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function hapusProduk($id)
	{
		$this->produk->hapusDataProduk($id);
		echo json_encode(array("status" => TRUE));
	}

}
