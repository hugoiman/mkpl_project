<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','user');
	}

	public function index()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('user/user_view');
}

	public function mengambilUser()
	{
		$list = $this->user->mengambilDataUser();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$row = array();
			$row[] = $user->jenis;
			$row[] = $user->user;
			$row[] = $user->password;
			$row[] = $user->nama;
	        $row[] = $user->jenis_kelamin;
	        $row[] = $user->tanggal_lahir;
	        $row[] = $user->jabatan;
	        $row[] = $user->email;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_user('."'".$user->id_user."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="hapus_user('."'".$user->id_user."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->user->count_all(),
						"recordsFiltered" => $this->user->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user->get_by_id($id);
		echo json_encode($data);
	}

	public function tambahUser()
	{
		$data = array(
				'jenis' => $this->input->post('jenis'),
				'user' => $this->input->post('user'),
				'password' => md5($this->input->post('password')),
				'nama' => $this->input->post('nama'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
				'jabatan' => $this->input->post('jabatan'),
				'email' => $this->input->post('email'),
			);
		$insert = $this->user->tambahDataUser($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahUser()
	{
		$data = array(
				'jenis' => $this->input->post('jenis'),
				'user' => $this->input->post('user'),
				'password' => $this->input->post('password'),
				'nama' => $this->input->post('nama'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
				'jabatan' => $this->input->post('jabatan'),
				'email' => $this->input->post('email'),
			);
		$this->user->ubahDataUser(array('id_user' => $this->input->post('id_user')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function hapusUser($id)
	{
		$this->user->hapusDatauser($id);
		echo json_encode(array("status" => TRUE));
	}

}
