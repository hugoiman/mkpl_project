<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan_produk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('permintaan_produk_model', 'permintaan_produk');
        $this->load->model('produk_model', 'produk');
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
        $this->load->view('permintaan_produk/permintaan_produk_view');
    }

    public function mengambilPermintaanProduk() {
        $list = $this->permintaan_produk->mengambilDataPermintaanProduk();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $permintaan_produk) {
            $no++;
            $row = array();
            $row[] = $permintaan_produk->nama;
            $row[] = $permintaan_produk->jumlah;
            $row[] = number_format($permintaan_produk->perkiraan_harga);
            $row[] = $permintaan_produk->status;
            $row[] = $permintaan_produk->tanggal;
            $row[] = $permintaan_produk->waktu;
            $row[] = $permintaan_produk->persetujuan;
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_permintaan_produk(' . "'" . $permintaan_produk->id_permintaan_produk . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_permintaan_produk(' . "'" . $permintaan_produk->id_permintaan_produk . "'," . "'setuju'" . ')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_permintaan_produk(' . "'" . $permintaan_produk->id_permintaan_produk . "'," . "'tidak'" . ')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->permintaan_produk->count_all(),
            "recordsFiltered" => $this->permintaan_produk->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->permintaan_produk->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_get_by_nama($nama) {
        $nama = str_replace('%20', ' ', $nama);
        $data = $this->produk->get_by_nama($nama);
        echo json_encode($data);
    }

    public function tambahPermintaanProduk() {
        $status = true;
        $pesan = "";
        $data = array(
            'nama' => $this->input->post('nama'),
            'jumlah' => $this->input->post('jumlah'),
            'perkiraan_harga' => $this->input->post('perkiraan_harga'),
            'status' => $this->input->post('status'),
            'tanggal' => date("Y-m-d"),
            'waktu' => date("h:i:s"),
            'persetujuan' => 'belum',
        );

        if ($data['nama'] == "") {
            $pesan .= "nama harus terisi\n";
            $status = false;
        }
        if ($data["jumlah"] == "") {
            $pesan .= "jumlah harus terisi\n";
            $status = false;
        }
        if ($data["perkiraan_harga"] == "") {
            $pesan .= "perkiraan harga harus terisi\n";
            $status = false;
        }
        if ($data["status"] == "") {
            $pesan .= "status harus terisi";
            $status = false;
        }
        if ($status) {
            $insert = $this->permintaan_produk->tambahDataPermintaanProduk($data);
            echo json_encode(array("status" => $status, "pesan" => "sukses menyimpan"));
        }else{
            echo json_encode(array("status" => $status, "pesan" => $pesan));
        }
    }

    public function ubahPermintaanProduk() {
        $id_permintaan_produk = $this->input->post('id_permintaan_produk');

        $data = array(
            'nama' => $this->input->post('nama'),
            'jumlah' => $this->input->post('jumlah'),
            'perkiraan_harga' => $this->input->post('perkiraan_harga'),
            'status' => $this->input->post('status'),
            'tanggal' => date("Y-m-d"),
            'waktu' => date("h:i:s"),
            'persetujuan' => 'belum',
        );

        $update = $this->permintaan_produk->ubahDataPermintaanProduk(array('id_permintaan_produk' => $id_permintaan_produk), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function setujuiPermintaanProduk($id, $data) {
        if ($this->session->jenis == 'supervisor') {
            $this->permintaan_produk->ubahDataPermintaanProduk(array('id_permintaan_produk' => $id), array('persetujuan' => $data));
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

}
