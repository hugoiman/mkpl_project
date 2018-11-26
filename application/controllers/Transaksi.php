<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaksi_model','transaksi');
	}

	public function transaksi_pembelian()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			$this->load->view('header/navigation_logistik');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('transaksi/transaksi_pembelian_view');
	}

	public function transaksi_konsinyasi()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			$this->load->view('header/navigation_logistik');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('transaksi/transaksi_konsinyasi_view');
	}

	public function transaksi_penjualan()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			$this->load->view('header/navigation_logistik');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('transaksi/transaksi_penjualan_view');
	}

	public function mengambilTransaksiPenjualan()
	{
		$list = $this->transaksi->mengambilDataTransaksiPenjualan();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $transaksi) {
			$no++;
			$row = array();
			$row[] = $transaksi->jenis;
			$row[] = $transaksi->tanggal;
		    $row[] = $transaksi->waktu;
		    $row[] = $transaksi->persetujuan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_transaksi('."'".$transaksi->id_transaksi."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->transaksi->count_all_penjualan(),
						"recordsFiltered" => $this->transaksi->count_filtered_penjualan(),
						"data" => $data,
				);

		echo json_encode($output);
	}

	public function mengambilTransaksiPembelian()
	{
		$list = $this->transaksi->mengambilDataTransaksiPembelian();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $transaksi) {
			$no++;
			$row = array();
			$row[] = $transaksi->jenis;
			$row[] = $transaksi->tanggal;
		    $row[] = $transaksi->waktu;
		    $row[] = $transaksi->persetujuan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_transaksi('."'".$transaksi->id_transaksi."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->transaksi->count_all_pembelian(),
						"recordsFiltered" => $this->transaksi->count_filtered_pembelian(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function mengambilTransaksiKonsinyasi()
	{
		$list = $this->transaksi->mengambilDataTransaksiKonsinyasi();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $transaksi) {
			$no++;
			$row = array();
			$row[] = $transaksi->jenis;
			$row[] = $transaksi->tanggal;
		    $row[] = $transaksi->waktu;
		    $row[] = $transaksi->persetujuan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_transaksi('."'".$transaksi->id_transaksi."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_transaksi('."'".$transaksi->id_transaksi."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->transaksi->count_all_konsinyasi(),
						"recordsFiltered" => $this->transaksi->count_filtered_konsinyasi(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function setujuiTransaksi($id, $data)
	{
		if($this->session->jenis == 'supervisor'){
			$this->transaksi->ubahDataTransaksi(array('id_transaksi' => $id), array('persetujuan' => $data));
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => FALSE));
		}
	}

	public function detailTransaksiPembelian($id){
		$data = $this->transaksi->detailDataTransaksiPembelian($id);
		echo json_encode($data);
	}

	public function detailTransaksiPenjualan($id){
		$data = $this->transaksi->detailDataTransaksiPenjualan($id);
		echo json_encode($data);
	}

	public function detailTransaksiKonsinyasi($id){
		$data = $this->transaksi->detailDataTransaksiKonsinyasi($id);
		echo json_encode($data);
	}
}
?>