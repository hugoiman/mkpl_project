<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('distributor_model', 'distributor');
    }

    public function index() {
        if ($this->session->jenis == 'logistik') {
            $this->load->view('header/navigation_logistik');
        } else if ($this->session->jenis == 'supervisor') {
            $this->load->view('header/navigation_supervisor');
        } else if ($this->session->jenis == 'kasir') {
            redirect('/penjualan');
        } else {
            redirect();
        }
        $this->load->view('distributor/distributor_view');
    }

    public function mengambilDistributor() {
        $list = $this->distributor->mengambilDataDistributor();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $distributor) {
            $no++;
            $row = array();
            $row[] = $distributor->nama;
            $row[] = $distributor->alamat;
            $row[] = $distributor->nomor_telepon;
            $row[] = $distributor->faksimili;
            $row[] = $distributor->status_pkp;
            $row[] = $distributor->email;
            $row[] = $distributor->npwp;
            $row[] = $distributor->website;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_distributor(' . "'" . $distributor->id_distributor . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="hapus_distributor(' . "'" . $distributor->id_distributor . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->distributor->count_all(),
            "recordsFiltered" => $this->distributor->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->distributor->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_get_by_nama($nama) {
        $nama = str_replace('%20', ' ', $nama);
        $data = $this->distributor->get_by_nama($nama);
        echo json_encode($data);
    }

    public function tambahDistributor() {

        $status = true;
        $pesan = "";

        $data = array(
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'nomor_telepon' => $this->input->post('nomor_telepon'),
            'faksimili' => $this->input->post('faksimili'),
            'status_pkp' => $this->input->post('status_pkp'),
            'email' => $this->input->post('email'),
            'npwp' => $this->input->post('npwp'),
            'website' => $this->input->post('website'),
        );

        $insert = $this->distributor->tambahDataDistributor($data);
        echo json_encode(array("status" => $status, "pesan" => "sudah tersimpan"));
       
    }

    public function ubahDistributor() {

        $data = array(
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'nomor_telepon' => $this->input->post('nomor_telepon'),
            'faksimili' => $this->input->post('faksimili'),
            'status_pkp' => $this->input->post('status_pkp'),
            'email' => $this->input->post('email'),
            'npwp' => $this->input->post('npwp'),
            'website' => $this->input->post('website'),
        );

        $this->distributor->ubahDataDistributor(array('id_distributor' => $this->input->post('id_distributor')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function hapusDistributor($id) {
        $this->distributor->hapusDataDistributor($id);
        echo json_encode(array("status" => TRUE));
    }

}
