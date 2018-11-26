<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_opname_model extends CI_Model {

	var $table = 'stok_opname';
	var $column_order = array('nama_produk','kode_produksi','jumlah','tanggal','waktu','persetujuan',null); //set column field database for datatable orderable
	var $column_search = array('produk.nama','stok.kode_produksi','stok.jumlah','stok_opname.tanggal','stok_opname.persetujuan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_stok_opname' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('stok_opname.id_stok_opname, produk.nama as nama_produk, stok.kode_produksi as kode_produksi, stok_opname.jumlah as jumlah, stok_opname.tanggal as tanggal, stok_opname.waktu as waktu, stok_opname.persetujuan as persetujuan');
		$this->db->from('stok_opname');
		$this->db->join('stok', 'stok_opname.id_stok = stok.id_stok');
		$this->db->join('produk', 'stok.id_produk = produk.id_produk');

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

	function mengambilDataStokOpname()
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
		$this->db->select('so.id_stok_opname, s.kode_produksi, p.id_produk, p.nama as nama_produk, so.jumlah');
		$this->db->from('stok_opname so');
		$this->db->join('stok s', 's.id_stok = so.id_stok');
		$this->db->join('produk p', 'p.id_produk = s.id_produk');
		$this->db->where('id_stok_opname',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataStokOpname($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataStokOpname($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function hapusDataStokOpname($id)
	{
		$this->db->where('id_stok_opname', $id);
		$this->db->delete($this->table);
	}

}
