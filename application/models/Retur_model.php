<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_model extends CI_Model {

	var $table = 'retur';
	var $stok = 'stok s';
	var $produk = 'produk p';
	var $distributor = 'distributor d';
	var $column_order = array('jenis','nama_produk','nama_disrtibutor','jumlah','keterangan','tanggal','persetujuan',null); //set column field database for datatable orderable
	var $column_search = array('retur.jenis','produk.nama','distributor.nama','retur.jumlah','retur.keterangan','retur.tanggal','retur.persetujuan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_retur' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('retur.id_retur, produk.nama as nama_produk , distributor.nama as nama_distributor, retur.jumlah as jumlah, retur.keterangan as keterangan, retur.tanggal as tanggal, retur.persetujuan as persetujuan');
		$this->db->from('retur');
		$this->db->join('stok', 'retur.id_stok = stok.id_stok');
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

	function mengambilDataReturPembelian()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('retur.jenis', 'pembelian');
		$query = $this->db->get();

		return $query->result();
	}

	function mengambilDataReturPenjualan()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('retur.jenis', 'penjualan');
		$query = $this->db->get();

		return $query->result();
	}

	function mengambilDataReturKonsinyasi()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('retur.jenis', 'konsinyasi');
		$query = $this->db->get();

		return $query->result();
	}

	function count_filtered_retur_pembelian()
	{
		$this->_get_datatables_query();
		$this->db->where('retur.jenis', 'pembelian');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_filtered_retur_konsinyasi()
	{
		$this->_get_datatables_query();
		$this->db->where('retur.jenis', 'konsinyasi');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_filtered_retur_penjualan()
	{
		$this->_get_datatables_query();
		$this->db->where('retur.jenis', 'penjualan');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_retur_pembelian()
	{
		$this->db->from($this->table);
		$this->db->where('retur.jenis', 'pembelian');
		return $this->db->count_all_results();
	}

	public function count_all_retur_penjualan()
	{
		$this->db->from($this->table);
		$this->db->where('retur.jenis', 'penjualan');
		return $this->db->count_all_results();
	}

	public function count_all_retur_konsinyasi()
	{
		$this->db->from($this->table);
		$this->db->where('retur.jenis', 'konsinyasi');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('re.id_retur, p.id_produk as id_produk, d.nama as nama_distributor, re.jumlah, re.keterangan, re.tanggal, re.persetujuan');
		$this->db->from('retur re');
		$this->db->join($this->stok, 's.id_stok = re.id_stok');
		$this->db->join($this->produk, 'p.id_produk = s.id_produk');
		$this->db->join($this->distributor, 'd.id_distributor = s.id_distributor');
		$this->db->where('id_retur',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataRetur($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataRetur($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

}
