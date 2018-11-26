<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('kasir_model','kasir');
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
		$this->load->view('kasir/kasir_view');
}

	public function mengambilKasir()
	{
		$list = $this->kasir->mengambilDataKasir();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $kasir) {
			$no++;
			$row = array();
			$row[] = $kasir->jenis;
			$row[] = $kasir->logam_100;
			$row[] = $kasir->logam_200;
			$row[] = $kasir->logam_500;
			$row[] = $kasir->logam_1000;
			$row[] = $kasir->kertas_1000;
			$row[] = $kasir->kertas_2000;
			$row[] = $kasir->kertas_5000;
			$row[] = $kasir->kertas_10000;
			$row[] = $kasir->kertas_20000;
			$row[] = $kasir->kertas_50000;
			$row[] = $kasir->kertas_100000;
			$row[] = $kasir->tanggal;
			$row[] = $kasir->waktu;
			$row[] = $kasir->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_kasir('."'".$kasir->id_kasir."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_kasir('."'".$kasir->id_kasir."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_kasir('."'".$kasir->id_kasir."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->kasir->count_all(),
						"recordsFiltered" => $this->kasir->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->kasir->get_by_id($id);
		echo json_encode($data);
	}

	public function tambahPembukaanKasir()
	{

		$data = array(
				'jenis' => 'pembukaan',
				'logam_100' => $this->input->post('logam_100'),
				'logam_200' => $this->input->post('logam_200'),
				'logam_500' => $this->input->post('logam_500'),
				'logam_1000' => $this->input->post('logam_1000'),
				'kertas_1000' => $this->input->post('kertas_1000'),
				'kertas_2000' => $this->input->post('kertas_2000'),
				'kertas_5000' => $this->input->post('kertas_5000'),
				'kertas_10000' => $this->input->post('kertas_10000'),
				'kertas_20000' => $this->input->post('kertas_20000'),
				'kertas_50000' => $this->input->post('kertas_50000'),
				'kertas_100000' => $this->input->post('kertas_100000'),
				'tanggal' => date("Y-m-d"),
				'waktu' => date("h:i:s"),
				'persetujuan' => 'belum',
			);
		$insert = $this->kasir->tambahDataKasir($data);
		echo json_encode(array("status" => TRUE));
	}

		public function tambahPenutupanKasir()
		{
			$data = array(
					'jenis' => 'penutupan',
					'logam_100' => $this->input->post('logam_100'),
					'logam_200' => $this->input->post('logam_200'),
					'logam_500' => $this->input->post('logam_500'),
					'logam_1000' => $this->input->post('logam_1000'),
					'kertas_1000' => $this->input->post('kertas_1000'),
					'kertas_2000' => $this->input->post('kertas_2000'),
					'kertas_5000' => $this->input->post('kertas_5000'),
					'kertas_10000' => $this->input->post('kertas_10000'),
					'kertas_20000' => $this->input->post('kertas_20000'),
					'kertas_50000' => $this->input->post('kertas_50000'),
					'kertas_100000' => $this->input->post('kertas_100000'),
					'tanggal' => date("Y-m-d"),
					'waktu' => date("h:i:s"),
					'persetujuan' => 'belum',
				);
			$insert = $this->kasir->tambahDataKasir($data);
			echo json_encode(array("status" => TRUE));
		}

		public function setujuiKasir($id, $data)
		{
			if($this->session->jenis == 'supervisor'){
				$this->kasir->ubahDataKasir(array('id_kasir' => $id), array('persetujuan' => $data));
				echo json_encode(array("status" => TRUE));
			}else{
				echo json_encode(array("status" => FALSE));
			}
		}

		public function ubahKasir()
		{
			$data = array(
					'logam_100' => $this->input->post('logam_100'),
					'logam_200' => $this->input->post('logam_200'),
					'logam_500' => $this->input->post('logam_500'),
					'logam_1000' => $this->input->post('logam_1000'),
					'kertas_1000' => $this->input->post('kertas_1000'),
					'kertas_2000' => $this->input->post('kertas_2000'),
					'kertas_5000' => $this->input->post('kertas_5000'),
					'kertas_10000' => $this->input->post('kertas_10000'),
					'kertas_20000' => $this->input->post('kertas_20000'),
					'kertas_50000' => $this->input->post('kertas_50000'),
					'kertas_100000' => $this->input->post('kertas_100000'),
					'tanggal' => date("Y-m-d"),
					'waktu' => date("h:i:s"),
					'persetujuan' => 'belum',
				);
			$this->kasir->ubahDataKasir(array('id_kasir' => $this->input->post('id_kasir')), $data);
			echo json_encode(array("status" => TRUE));
		}
}
