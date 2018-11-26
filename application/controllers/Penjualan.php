
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('penjualan_model','penjualan');
		$this->load->model('produk_model','produk');
		$this->load->model('resep_model','resep');
		$this->load->model('stok_model','stok');
		$this->load->model('transaksi_model','transaksi');
		$this->load->model('keuangan_model','keuangan');
	}

	public function index()
	{
		if($this->session->jenis == 'kasir')
		{
			$this->load->view('header/navigation_kasir');
		}
		else if ($this->session->jenis == 'logistik')
		{	
			redirect('/stok');
		}
		else if($this->session->jenis == 'supervisor')
		{
			$this->load->view('header/navigation_supervisor');
		}	
		else
		{
			redirect();
		}
		$this->load->view('penjualan/penjualan_view');
	}

	public function ajax_get_by_id_pasien_id_dokter_nomor_resep_to_detail($id_pasien, $id_dokter, $nomor_resep)
	{
		$data = $this->penjualan->get_by_id_pasien_id_dokter_nomor_to_detail($id_pasien, $id_dokter, $nomor_resep);
		echo json_encode($data);
	
	}		

	public function ajax_get_by_id_pasien_id_dokter_nomor_resep_to_produk($id_pasien, $id_dokter, $nomor_resep)
	{
		$data = $this->penjualan->get_by_id_pasien_id_dokter_nomor_to_produk($id_pasien, $id_dokter, $nomor_resep);
		echo json_encode($data);
	}
	
	public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->penjualan->get_by_nama($nama);
		echo json_encode($data);
	}
	
	public function ajax_get_by_nama_dokter($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->penjualan->get_by_nama_dokter($nama);
		echo json_encode($data);	
	}
	
	public function ajax_get_by_nama_pasien($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->penjualan->get_by_nama_pasien($nama);
		echo json_encode($data);	
	}

	public function setPersetujuan()
	{
		if($this->session->jenis == 'supervisor')
		{
			$this->transaksi->ubahDataTransaksi(array('id_transaksi' => $this->input->post('id_transaksi')), $data);
			$update = $this->stok->ubahDataStokTerJual($id_produk, $jumlah);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function tambahPenjualan()
	{
		$bebas = array();
		$resep = array();
		$tarik_resep = array();

		$debit = array();
		$kredit = array();

		$bebas = $this->input->post('bebas');
		$resep = $this->input->post('resep');
		$tarik_resep = $this->input->post('tarik_resep');
		
		$tunai = $this->input->post('tunai');
		$debit = $this->input->post('debit');
		$kredit = $this->input->post('kredit');

		$jenis = $this->input->post('jenis');

		$index_pengurangan_produk = 0;
		$pengurangan_produk = array();

		$data = array(
			'jenis' => 'penjualan',
			'tanggal' => date('Y-m-d'),
			'waktu' => date('h:i:s'),
			'persetujuan' => 'belum'
		);

		$insert = $this->transaksi->tambahDataTransaksi($data);

		$id_transaksi = $this->transaksi->get_last_id()->id_transaksi;	

		$data = array(
			'jenis' => 'masuk',
			'sumber' => 'penjualan',
			'tanggal' => date('Y-m-d'),
			'waktu' => date('h:i:s'),
			'persetujuan' => 'belum'
		);

		$insert = $this->keuangan->tambahDataKeuangan($data);

		$id_keuangan = $this->keuangan->get_last_id()->id_keuangan;

		if($debit[0] != ''){
			$data = array(
				'id_keuangan' => $id_keuangan,
				'alat_transaksi' => 'debit',
				'jumlah_uang' => $debit[0],
				'kartu' => $debit[2],
				'nomor_transaksi' => $debit[1],
				'nomor_kartu' => $debit[3],
			);
			$insert = $this->keuangan->tambahDataKeuanganMasuk($data);
		}	

		if($kredit[0] != ''){
			$data = array(
				'id_keuangan' => $id_keuangan,
				'alat_transaksi' => 'kredit',
				'jumlah_uang' => $kredit[0],
				'kartu' => $kredit[2],
				'nomor_transaksi' => $kredit[1],
				'nomor_kartu' => $kredit[3],
			);
			$insert = $this->keuangan->tambahDataKeuanganMasuk($data);
		}

		if($tunai != ''){
			$data = array(
				'id_keuangan' => $id_keuangan,
				'alat_transaksi' => 'tunai',
				'jumlah_uang' => $tunai,
				'kartu' => '',
				'nomor_transaksi' => '',
				'nomor_kartu' => '',
			);
			$insert = $this->keuangan->tambahDataKeuanganMasuk($data);
		}

		for($i = 0; $i < count($resep); $i++){
			
			if($resep[$i]['jenis'] == ''){
				continue;
			}else{
				$data = array(
					'jenis' => $resep[$i]['jenis'],
					'nomor' => $resep[$i]['nomor'],
					'id_pasien' => $resep[$i]['id_pasien'],
					'id_dokter' => $resep[$i]['id_dokter'],
					'tarik' => '1',
					'bungkus' => $resep[$i]['bungkus'],
					'jumlah_bungkus' => $resep[$i]['jumlah_bungkus'],
					'cara_minum' => $resep[$i]['cara_minum'],
					'harga' => $resep[$i]['harga'],
					'tanggal' => date('Y-m-d')
				);

				$insert = $this->resep->tambahDataResep($data);

				$id_resep = $this->resep->get_last_id()->id_resep;

				for($j = 0; $j < count($resep[$i]['produk_jumlah']); $j++){
					$produk_jumlah = $resep[$i]['produk_jumlah'];

					$data = array(
						'id_resep' => $id_resep,
						'id_produk' => 	$produk_jumlah[$j][0],
						'jumlah' => 	$produk_jumlah[$j][3],
					);

					$insert = $this->resep->tambahDataDetailResep($data);

					$pengurangan_produk[$index_pengurangan_produk] = array('id_produk' => $produk_jumlah[$j][0], 'jumlah' => $produk_jumlah[$j][3]);
					$index_pengurangan_produk++;
				}
			}
		}

		for($i = 0; $i < count($tarik_resep); $i++){
			
			if($tarik_resep[$i]['id_resep'] == '0'){
				continue;
			}else{	
				$insert = $this->resep->tambahDataTarikResep($tarik_resep[$i]['id_resep'], date('Y-m-d'));
				$data_tarik_resep = $this->resep->detailDataResep($tarik_resep[$i]['id_resep']);

				foreach($data_tarik_resep as $data){
					$pengurangan_produk[$index_pengurangan_produk] = array('id_produk' => $data->id_produk, 'jumlah' => $data->jumlah);
				    $index_pengurangan_produk++;
				}
			}
		}

		for($i = 0; $i < count($bebas); $i++){

			if($bebas[$i]['id_produk'] == '0'){
				continue;
			}else{
				$pengurangan_produk[$index_pengurangan_produk] = array('id_produk' => $bebas[$i]['id_produk'], 'jumlah' => $bebas[$i]['jumlah']);
				$index_pengurangan_produk++;
			}
		}

		for($i = 0; $i < count($pengurangan_produk); $i++){
			$data = array(
				'id_transaksi' => $id_transaksi,
				'id_produk' => $pengurangan_produk[$i]['id_produk'],
				'jumlah' => $pengurangan_produk[$i]['jumlah']
			);
			$insert = $this->penjualan->tambahDataPenjualan($data);
		}

		$id_stok = "";
		$id_produk = "";
		$jumlah = "";

		$status = true;
		
		for($i = 0; $i < count($pengurangan_produk); $i++){
			$jumlah = 'jumlah - '.$pengurangan_produk[$i]['jumlah'];
			$update = $this->stok->ubahDataStokTerJual($pengurangan_produk[$i]['id_produk'], $jumlah);
			
			$status = true;

			while($status){
				$minus = $this->stok->getStokJumlahMinus();
				if(isset($minus)){
					$id_stok = $minus->id_stok;
					$id_produk = $minus->id_produk;
					$jumlah = 'jumlah '.$minus->jumlah;

					$update = $this->stok->setStokTidakMinus($id_stok);
					$update = $this->stok->ubahDataStokTerJual($id_produk, $jumlah);
				}else{
					$status = false;
				}
			}
		}

		echo json_encode(array('status' => TRUE));
	}
}		

?>
	