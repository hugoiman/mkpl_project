<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan_model extends CI_Model {

	var $table = 'keuangan';
	var $column_order = array('jenis','sumber', 'tanggal', 'waktu', 'persetujuan',null); //set column field database for datatable orderable
	var $column_search = array('jenis','sumber', 'tanggal', 'waktu', 'persetujuan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_keuangan' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{

				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function mengambilDatakeuanganKeluar()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where("jenis", "keluar");
		$query = $this->db->get();
		return $query->result();
	}

	function mengambilDatakeuanganMasuk()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where("jenis", "masuk");
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_masuk()
	{
		$this->_get_datatables_query();
		$this->db->where("jenis", "masuk");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_masuk()
	{
		$this->db->from($this->table);
		$this->db->where("jenis", "masuk");
		return $this->db->count_all_results();
	}

	function count_filtered_keluar()
	{
		$this->_get_datatables_query();
		$this->db->where("jenis", "keluar");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_keluar()
	{
		$this->db->from($this->table);
		$this->db->where("jenis", "keluar");
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_keuangan',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_last_id()
	{
		$this->db->from($this->table);
		$this->db->order_by('id_keuangan', 'DESC');		
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataKeuangan($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataKeuangan($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function tambahDataKeuanganMasuk($data){
		$this->db->insert('masuk', $data);
		return $this->db->insert_id();
	}
	
	public function tambahDataKeuanganKeluar($data){
		$this->db->insert('keluar', $data);
		return $this->db->insert_id();
	}

	public function detailDatakeuanganMasuk($id){
		$this->db->select('masuk.alat_transaksi, masuk.jumlah_uang, masuk.kartu, masuk.nomor_kartu, masuk.nomor_transaksi');
		$this->db->from('keuangan');
		$this->db->join('masuk', 'masuk.id_keuangan = keuangan.id_keuangan');
		$this->db->where('keuangan.id_keuangan', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function detailDatakeuanganKeluar($id){
		$this->db->select('keluar.alat_transaksi, keluar.jumlah_uang, keluar.bank_lawan_transaksi, keluar. nomor_rekening_bank_lawan_transaksi');
		$this->db->from('keuangan');
		$this->db->join('keluar', 'keluar.id_keuangan = keuangan.id_keuangan');
		$this->db->where('keuangan.id_keuangan', $id);
		$query = $this->db->get();
		return $query->result();
	}

}
