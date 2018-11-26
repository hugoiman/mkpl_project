<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','user');
		$this->load->model('user_log_model','user_log');
	}

	public function index()
	{
		if($this->session->jenis == 'logistik'){
			redirect('/stok');
		}
		else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}
		else if($this->session->jenis == 'supervisor'){
			redirect('/user');
		}
		else{
			$this->load->view('authentication/login_view');
		}
	}

	public function login()
	{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$user = $this->user->get_by_user_password($username, $password);

			if($user == null){
				$data = array(
					'status' => '*user dan password tidak cocok'
				);
				
				echo json_encode(array("status" => FALSE, "pesan" => "user dan password tidak cocok !!!"));
			}else{
				$userdata = array(
					'id_user' => $user->id_user,
					'user' => $user->user,
					'jenis' => $user->jenis
				);
				$this->session->set_userdata($userdata);

				$data = array(
					'id_user' => $this->session->id_user,
					'aktifitas' => 'login',
					'tanggal' => date('Y-m-d'),
					'waktu' => date('h:i:s')
				);

				$insert = $this->user_log->tambahDataUserLog($data);
				echo json_encode(array("status" => TRUE));
		}
	}

	public function logout(){

		$data = array(
			'id_user' => $this->session->id_user,
			'aktifitas' => 'logout',
			'tanggal' => date('Y-m-d'),
			'waktu' => date('h:i:s')
		);

		$insert = $this->user_log->tambahDataUserLog($data);

		$userdata = array('id_user', 'user', 'jenis');
		$this->session->unset_userdata($userdata);
		redirect();
	}
}
