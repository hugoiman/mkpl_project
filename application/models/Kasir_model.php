<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_model extends CI_Model {

	var $table = 'kasir';
	var $column_order = array('jenis','logam_100','logam_200','logam_500','logam_1000','kertas_1000','kertas_2000','kertas_5000','kertas_10000','kertas_20000','kertas_50000','kertas_100000','tanggal','waktu','persetujuan',null);
	var $column_search = array('jenis','logam_100','logam_200','logam_500','logam_1000','kertas_1000','kertas_2000','kertas_5000','kertas_10000','kertas_20000','kertas_50000','kertas_100000','tanggal','waktu','persetujuan');
	var $order = array('id_kasir' => 'desc'); // default order

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

	function mengambilDataKasir()
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
		$this->db->where('id_kasir',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function tambahDataKasir($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function ubahDataKasir($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

}
