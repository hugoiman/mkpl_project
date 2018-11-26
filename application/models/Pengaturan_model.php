<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

	var $table = 'pengaturan';
	var $column_order = array('nama','alamat','nomor_telepon',null); //set column field database for datatable orderable
	var $column_search = array('nama','alamat','nomor_telepon'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_pengaturan' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function mengambilDataPengaturanBungkus()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '1');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanBungkus($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '1'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanKategori()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '2');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanKategori($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '2'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanDokter()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '3');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanDokter($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '3'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanSatuan()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '5');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanSatuan($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '5'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanCatatanStruk()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '4');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanCatatanStruk($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '4'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanIpPrinter()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '6');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanIpPrinter($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '6'));
		return $this->db->affected_rows();
	}

	public function mengambilDataPengaturanPabrik()
	{
		$this->db->from($this->table);
		$this->db->where('id_pengaturan', '8');
		$query = $this->db->get();
		return $query->row();
	}

	public function ubahDataPengaturanPabrik($data)
	{
		$this->db->update($this->table, $data, array('id_pengaturan' => '8'));
		return $this->db->affected_rows();
	}

}
