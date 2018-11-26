<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Resep_model extends CI_Model {

	var $table = 'resep';
	var $column_order = array('jenis','nomor','nama_pasien', 'nama_dokter', 'tarik','bungkus', 'jumlah_bungkus', 'cara_minum','harga','tanggal',null); //set column field database for datatable orderable
	var $column_search = array('resep.jenis','resep.nomor','pasien.nama', 'dokter.nama', 'resep.tarik', 'resep.bungkus','resep.jumlah_bungkus', 'resep.cara_minum','resep.harga','resep.tanggal'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_resep' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('resep.id_resep, resep.jenis as jenis, resep.nomor as nomor, pasien.nama as nama_pasien, dokter.nama as nama_dokter, resep.tarik as tarik, resep.bungkus as bungkus, resep.jumlah_bungkus as jumlah_bungkus, resep.cara_minum as cara_minum, resep.harga as harga, resep.tanggal as tanggal');
		$this->db->from('resep');
		$this->db->join('pasien', 'resep.id_pasien = pasien.id_pasien');
		$this->db->join('dokter', 'resep.id_dokter = dokter.id_dokter');

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

	function mengambilDataResep()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_last_id(){
		$this->db->from($this->table);
		$this->db->order_by('id_resep', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_resep',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function detailDataResep($id)
	{
		$this->db->select('produk.id_produk, produk.nama as nama_produk, resep_produk.jumlah as jumlah');
		$this->db->from('resep');
		$this->db->join('resep_produk', 'resep.id_resep = resep_produk.id_resep');
		$this->db->join('produk', 'produk.id_produk = resep_produk.id_produk');
		$this->db->where('resep.id_resep',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function tambahDataResep($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function tambahDataDetailResep($data)
	{
		$this->db->insert('resep_produk', $data);
		return $this->db->insert_id();
	}

	public function tambahDataTarikResep($id_resep, $tanggal){
		$this->db->set('tarik', 'tarik + 1', FALSE);
		$this->db->set('tanggal', $tanggal);
		$this->db->where('id_resep', $id_resep);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	public function ubahDataResep($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

}
