<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('stok_model','stok');
		$this->load->model('pembelian_model','pembelian');
		$this->load->model('konsinyasi_model','konsinyasi');
		$this->load->model('transaksi_model', 'transaksi');
		$this->load->model('produk_model', 'produk');	
		setlocale(LC_MONETARY, 'id_IDR');
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
		$this->load->view('stok/stok_view');
	}

	public function mengambilStok()
	{
		$list = $this->stok->mengambilDataStok();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $stok) {
			$no++;
			$row = array();
			$row[] = $stok->kode_produksi;
			$row[] = $stok->status;
			$row[] = $stok->nama_produk;
			$row[] = $stok->nama_distributor;
			$row[] = $stok->jumlah;
			$row[] = number_format($stok->harga);
		    $row[] = $stok->tanggal_datang;
		    $row[] = $stok->tanggal_kadaluarsa;
		   	$row[] = $stok->tanggal_jatuh_tempo;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_stok('."'".$stok->id_stok."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->stok->count_all(),
						"recordsFiltered" => $this->stok->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->stok->get_by_id($id);
		echo json_encode($data);
	}
        
        public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->produk->get_by_nama($nama);
		echo json_encode($data);
	}

	public function tambahStok()
	{
		$id_produk = explode("|", $this->input->post('id_produk'));
		$id_distributor = explode("|", $this->input->post('id_distributor'));
		$jumlah = $this->input->post('jumlah');
		$status = $this->input->post('status');

		if($status == 'KONSINYASI' ){
			$data = array(
					'jenis' => 'konsinyasi',
					'tanggal' => date('Y-m-d'),
					'waktu' => date('h:i:s'),
					'persetujuan' => 'belum'
			);
		}else{
			$data = array(
					'jenis' => 'pembelian',
					'tanggal' => date('Y-m-d'),
					'waktu' => date('h:i:s'),
					'persetujuan' => 'belum'
			);
		}


		$insert = $this->transaksi->tambahDataTransaksi($data);

		$transaksi = $this->transaksi->get_last_id();

		$id_transaksi = $transaksi->id_transaksi;

		$data = array(
				'kode_produksi' =>  $this->input->post('kode_produksi'),
				'status' =>  $this->input->post('status'),
				'id_produk' => $id_produk[0],
				'id_distributor' => $id_distributor[0],
				'jumlah' => $this->input->post('jumlah'),
				'harga' => $this->input->post('harga'),
				'tanggal_datang' => $this->input->post('tanggal_datang'),
				'tanggal_kadaluarsa' => $this->input->post('tanggal_kadaluarsa'),
				'tanggal_jatuh_tempo' => $this->input->post('tanggal_jatuh_tempo'),
			);
		$insert = $this->stok->tambahDataStok($data);

		$list = $this->stok->get_by_kode_produksi_id_produk($id_produk[0], $this->input->post('kode_produksi'));

		$id_stok = $list->id_stok;

		if($status == 'KONSINYASI'){
			$data = array(
				'id_transaksi' => $id_transaksi,
				'id_stok' => $id_stok,
				'jumlah' => $jumlah,
				'tanggal_pengembalian' => ''
			);	

			$insert = $this->konsinyasi->tambahDataKonsinyasi($data);
		}else{
			$data = array(
				'id_transaksi' => $id_transaksi,
				'id_stok' => $id_stok,
				'jumlah_beli' => $jumlah,
				'tanggal_pelunasan' => '',
				'waktu_pelunasan' => ''
			);

			$insert = $this->pembelian->tambahDataPembelian($data);
		}
		
		echo json_encode(array("status" => TRUE));
	}

	public function ubahStok()
	{
		if($this->session->jenis == 'supervisor'){
			$id_produk = explode("|", $this->input->post('id_produk'));
			$id_distributor =  explode("|", $this->input->post('id_distributor'));

			$data = array(
					'kode_produksi' => $this->input->post('kode_produksi'),
					'status' =>  $this->input->post('status'),
					'id_produk' => $id_produk[0],
					'id_distributor' => $id_distributor[0],
					'jumlah' => $this->input->post('jumlah'),
					'harga' => $this->input->post('harga'),
					'tanggal_datang' => $this->input->post('tanggal_datang'),
					'tanggal_kadaluarsa' => $this->input->post('tanggal_kadaluarsa'),
					'tanggal_jatuh_tempo' => $this->input->post('tanggal_jatuh_tempo'),
				);
			$this->stok->ubahDataStok(array('id_stok' => $this->input->post('id_stok')), $data);
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => TRUE));
		}
	}

	public function hapusStok($id)
	{
		$this->stok->hapusDataStok($id);
		echo json_encode(array("status" => TRUE));
	}

}
