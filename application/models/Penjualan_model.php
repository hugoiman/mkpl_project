<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {

	var $table = 'penjualan';
	var $column_order = array('id_transaksi','id_produk','harga','jumlah',null); //set column field database for datatable orderable
	var $column_search = array('id_transaksi','id_produk','harga','jumlah'); //set column field database for datatable searchable just firstname , lastname , address are searchable
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

	function mengambilDataPenjualan()
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

	public function get_by_nama($nama)
	{
		$this->db->select('produk.id_produk, produk.nama, produk.satuan, produk.pabrik, sum(stok.jumlah) as jumlah, (stok.harga + (stok.harga*produk.laba/100) + (stok.harga*produk.ppn/100)) as harga');
		$this->db->from('produk');
		$this->db->join('stok', 'stok.id_produk = produk.id_produk');
		$this->db->group_by('produk.id_produk');
		$this->db->like('produk.nama', $nama);
		$this->db->limit(5);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_nama_pasien($nama)
	{
		$this->db->select('pasien.id_pasien, pasien.nama, pasien.alamat, pasien.nomor_telepon');
		$this->db->from('pasien');
		$this->db->like('pasien.nama', $nama);
		$this->db->limit(5);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_nama_dokter($nama)
	{
		$this->db->select('dokter.id_dokter, dokter.nama, dokter.alamat, dokter.kategori');
		$this->db->from('dokter');
		$this->db->like('dokter.nama', $nama);
		$this->db->limit(5);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_id_pasien_id_dokter_nomor_to_produk($id_pasien, $id_dokter, $nomor){
		$this->db->select('produk.id_produk as id_produk, produk.nama as nama_produk, resep_produk.jumlah as jumlah');
		$this->db->from('resep');
		$this->db->join('resep_produk', 'resep.id_resep = resep_produk.id_resep');
		$this->db->join('produk', 'produk.id_produk = resep_produk.id_produk');
		$this->db->where('resep.id_pasien',$id_pasien);
		$this->db->where('resep.id_dokter',$id_dokter);
		$this->db->where('resep.nomor',$nomor);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_by_id_pasien_id_dokter_nomor_to_detail($id_pasien, $id_dokter, $nomor){
		$this->db->select('resep.id_resep as id_resep, resep.jenis as jenis, resep.nomor as nomor, resep.tarik as tarik, resep.bungkus as bungkus, resep.jumlah_bungkus as jumlah_bungkus, resep.cara_minum as cara_minum, resep.harga as harga, resep.tanggal as tanggal');
		$this->db->from('resep');
		$this->db->where('resep.id_pasien',$id_pasien);
		$this->db->where('resep.id_dokter',$id_dokter);
		$this->db->where('resep.nomor',$nomor);
		$query = $this->db->get();
		return $query->result();
	}

	public function tambahDataPenjualan($data)
	{	
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
}
