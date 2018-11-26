<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tagihan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tagihan_model','tagihan');
		$this->load->model('pembelian_model','pembelian');
		$this->load->model('keuangan_model','keuangan');
	}

	public function index()
	{
		if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else{
			redirect();
		}
		$this->load->view('tagihan/tagihan_view');
	}

	public function mengambilTagihan()
	{
		$list = $this->tagihan->mengambilDataTagihan();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tagiahan) {
			
			$no++;
			$row = array();
			$row[] = $tagiahan->nama_produk;
			$row[] = $tagiahan->kode_produksi;
			$row[] = $tagiahan->nama_distributor;
			$row[] = number_format($tagiahan->tagihan);
			$row[] = $tagiahan->tanggal_jatuh_tempo;
			$row[] = $tagiahan->tanggal_pelunasan;
			$row[] = $tagiahan->waktu_pelunasan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="total_tagihan('."'".$tagiahan->id_transaksi."'".')"><i class="glyphicon glyphicon-pencil"></i> Bayar</a>
			';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->tagihan->count_all(),
						"recordsFiltered" => $this->tagihan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_get_by_id($id)
	{
		$data = $this->tagihan->get_by_id($id);
		echo json_encode($data);
	}

	public function bayarTagihan($id)
	{
		$tunai = '';
		$bank = array();

		$tunai = $this->input->post('tunai');
		$bank = $this->input->post('transfer');
		
		$data = array(
			'tanggal_pelunasan' => date('Y-m-d'),
			'waktu_pelunasan' => date('h:i:s')
		);

		$this->pembelian->ubahDataPembelian(array('id_transaksi' => $id), $data);
		
		$data = array(
			'jenis' => 'keluar',
			'sumber' => 'pembelian',
			'tanggal' => date('Y-m-d'),
			'waktu' => date('h:i:s'),
			'persetujuan' => 'belum'
		);

		$insert = $this->keuangan->tambahDataKeuangan($data);
		$id_keuangan = $this->keuangan->get_last_id()->id_keuangan;

		if($tunai != ''){
			$data = array(
				'id_keuangan' => $id_keuangan,
				'alat_transaksi' => 'tunai',
				'jumlah_uang' => $tunai,
				'bank_lawan_transaksi' => '',
				'nomor_rekening_bank_lawan_transaksi' => '',
			);
			$insert = $this->keuangan->tambahDataKeuanganKeluar($data);
		}

		if($bank[0] != ''){
			$data = array(
				'id_keuangan' => $id_keuangan,
				'alat_transaksi' => 'transfer',
				'jumlah_uang' => $bank[0],
				'bank_lawan_transaksi' => $bank[1],
				'nomor_rekening_bank_lawan_transaksi' => $bank[2],
			);
			$insert = $this->keuangan->tambahDataKeuanganKeluar($data);
		}

		echo json_encode(array("status" => TRUE));
	}

}
