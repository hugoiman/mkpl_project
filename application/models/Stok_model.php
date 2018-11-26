<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

	var $table = 'stok';
	var $column_order = array('kode_produksi','status','nama_produk','nama_distributor','jumlah', 'harga', 'tanggal_datang', 'tanggal_kadaluarsa','tanggal_jatuh_tempo',null); //set column field database for datatable orderable
	var $column_search = array('stok.kode_produksi','stok.status','produk.nama','distributor.nama','stok.jumlah', 'stok.harga', 'stok.tanggal_datang', 'stok.tanggal_kadaluarsa','stok.tanggal_jatuh_tempo'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_stok' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('stok.id_stok, stok.kode_produksi as kode_produksi,stok.status as status, produk.nama as nama_produk, distributor.nama as nama_distributor, stok.jumlah as jumlah, stok.harga as harga, stok.tanggal_datang as tanggal_datang, stok.tanggal_kadaluarsa as tanggal_kadaluarsa, stok.tanggal_jatuh_tempo as tanggal_jatuh_tempo');
		$this->db->from('stok');
		$this->db->join('produk', 'stok.id_produk = produk.id_produk');
		$this->db->join('distributor', 'stok.id_distributor = distributor.id_distributor');

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

	function mengambilDataStok()
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

	public function get_by_id($id)
	{
		$this->db->select('s.id_stok, s.kode_produksi, s.status, p.id_produk, p.nama as nama_produk, p.satuan as satuan, p.pabrik as pabrik, d.id_distributor, d.nama as nama_distributor, s.jumlah, s.harga, s.tanggal_kadaluarsa, s.tanggal_datang, s.tanggal_jatuh_tempo');
		$this->db->from('stok s');
		$this->db->join('produk p', 's.id_produk = p.id_produk');
		$this->db->join('distributor d', 's.id_distributor = d.id_distributor');
		$this->db->group_by('s.kode_produksi, nama_produk, nama_distributor, ');
		$this->db->where('id_stok',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_by_kode_produksi_id_produk($id_produk, $kode_produksi){
		$this->db->select('id_stok');
		$this->db->from($this->table);
		$this->db->where('id_produk',$id_produk);
		$this->db->where('kode_produksi',$kode_produksi);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataStok($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataStok($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function ubahDataStokTerRetur($id_stok, $jumlah){
		$this->db->set('jumlah', $jumlah, FALSE);
		$this->db->where('id_stok', $id_stok);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	public function ubahDataStokTerJual($id_produk, $jumlah){
		$this->db->set('jumlah', $jumlah, FALSE);
		$this->db->where('id_produk', $id_produk);
		$this->db->where('jumlah > ', '0');
		$this->db->order_by('tanggal_kadaluarsa', 'ASC');
		$this->db->limit(1);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	public function setStokTidakMinus($id_stok){
		$this->db->set('jumlah', '0');
		$this->db->where('id_stok', $id_stok);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}

	public function getStokJumlahMinus(){
		$this->db->from($this->table);
		$this->db->where('jumlah < ', '0');
		$this->db->order_by('tanggal_kadaluarsa', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function hapusDataStok($id)
	{
		$this->db->where('id_stok', $id);
		$this->db->delete($this->table);
	}


}
