<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Konsinyasi_model extends CI_Model {

	var $table = 'konsinyasi';
	var $column_order = array('nama_produk', 'nama_distributor', 'tanggal_pengembalian', 'persetujuan',null); //set column field database for datatable orderable
	var $column_search = array('nama_produk', 'nama_distributor', 'tanggal_pengembalian', 'persetujuan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id_transaksi' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select('konsinyasi.id_transaksi, produk.nama as nama_produk, stok.kode_produksi as kode_produksi, distributor.nama as nama_distributor, stok.harga as harga, stok.tanggal_jatuh_tempo as tanggal_jatuh_tempo, konsinyasi.tanggal_pengembalian as tanggal_pengembalian, konsinyasi.waktu_pengembalian as waktu_pengembalian, transaksi.persetujuan as persetujuan');
		$this->db->from('konsinyasi');
		$this->db->join('stok', 'stok.id_stok = konsinyasi.id_stok');
		$this->db->join('transaksi', 'transaksi.id_transaksi = konsinyasi.id_transaksi');
		$this->db->join('produk', 'produk.id_produk = stok.id_produk');
		$this->db->join('distributor', 'distributor.id_distributor = stok.id_distributor');

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

	function mengambilDatakonsinyasi()
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
		$this->db->from($this->table);
		$this->db->where('id_transaksi',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataKonsinyasi($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataKonsinyasi($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
}
