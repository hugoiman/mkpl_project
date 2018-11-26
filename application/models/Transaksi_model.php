<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

	var $table = 'transaksi';
	var $column_order = array('jenis','tanggal','waktu','persetujuan',null); //set column field database for datatable orderable
	var $column_search = array('jenis','tanggal','waktu','persetujuan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_transaksi' => 'desc'); // default order

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

	function mengambilDataTransaksiPenjualan()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where("jenis", "penjualan");
		$query = $this->db->get();
		return $query->result();
	}

	function mengambilDataTransaksiPembelian()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where("jenis", "pembelian");
		$query = $this->db->get();
		return $query->result();
	}

	function mengambilDataTransaksiKonsinyasi()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where("jenis", "konsinyasi");
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_pembelian()
	{
		$this->_get_datatables_query();
		$this->db->where("jenis", "pembelian");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_pembelian()
	{
		$this->db->from($this->table);
		$this->db->where("jenis", "pembelian");
		return $this->db->count_all_results();
	}

	function count_filtered_penjualan()
	{
		$this->_get_datatables_query();
		$this->db->where("jenis", "penjualan");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_penjualan()
	{
		$this->db->from($this->table);
		$this->db->where("jenis", "penjualan");
		return $this->db->count_all_results();
	}

	function count_filtered_konsinyasi()
	{
		$this->_get_datatables_query();
		$this->db->where("jenis", "konsinyasi");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_konsinyasi()
	{
		$this->db->from($this->table);
		$this->db->where("jenis", "konsinyasi");
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_transaksi',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_last_id(){
		$this->db->from($this->table);
		$this->db->order_by('id_transaksi', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();

	}

	public function tambahDataTransaksi($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataTransaksi($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function detailDataTransaksiPembelian($id){
		$this->db->select('produk.nama as nama_produk, stok.kode_produksi as kode_produksi, stok.harga as harga, pembelian.jumlah_beli as jumlah_beli, pembelian.tanggal_pelunasan as tanggal_pelunasan, pembelian.waktu_pelunasan as waktu_pelunasan');
		$this->db->from('transaksi');
		$this->db->join('pembelian', 'transaksi.id_transaksi = pembelian.id_transaksi');
		$this->db->join('stok', 'stok.id_stok =  pembelian.id_stok');
		$this->db->join('produk', 'produk.id_produk = stok.id_produk');
		$this->db->where('transaksi.id_transaksi', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function detailDataTransaksiPenjualan($id){
		$this->db->select('produk.nama as nama_produk, penjualan.jumlah as jumlah');
		$this->db->from('transaksi');
		$this->db->join('penjualan', 'transaksi.id_transaksi = penjualan.id_transaksi');
		$this->db->join('produk', 'produk.id_produk = penjualan.id_produk');
		$this->db->where('transaksi.id_transaksi', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function detailDataTransaksiKonsinyasi($id){
		$this->db->select('produk.nama as nama_produk, distributor.nama as nama_distributor, konsinyasi.jumlah as jumlah');
		$this->db->from('transaksi');
		$this->db->join('konsinyasi', 'transaksi.id_transaksi = konsinyasi.id_transaksi');
		$this->db->join('stok', 'stok.id_stok = konsinyasi.id_stok');
		$this->db->join('produk', 'produk.id_produk = stok.id_produk');
		$this->db->join('distributor', 'distributor.id_distributor = stok.id_distributor');
		$this->db->where('transaksi.id_transaksi', $id);
		$query = $this->db->get();
		return $query->result();
	}

}
