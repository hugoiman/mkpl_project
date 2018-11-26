<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_Print extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengaturan_model','pengaturan');
	}

	public function cobaPrint()
	{
		$this->load->library('escpos','receipt_print');
		$ip_printer = $this->pengaturan->mengambilDataPengaturanIpPrinter()->keterangan;
		try {
			$connector = new Escpos\PrintConnectors\WindowsPrintConnector($ip_printer);
			$printer = new Escpos\Printer($connector);
			$printer->text("Hello World!\n");
			$printer->feed();
			$printer->feed();
			$printer->cut();
			
			$printer -> close();
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
	}

	public function printNota(){

		$this->load->library('escpos','receipt_print');
		$total_tagihan = $this->input->post('total_tagihan');
		$kembalian = $this->input->post('kembalian');
		$data_nota = $this->input->post('data_nota');

		$bayar = ((int) $total_tagihan) + ((int) $kembalian);

		$ip_printer = $this->pengaturan->mengambilDataPengaturanIpPrinter()->keterangan;
		$catatan = $this->pengaturan->mengambilDataPengaturanCatatanStruk()->keterangan;

		$connector = new Escpos\PrintConnectors\WindowsPrintConnector($ip_printer);
		$printer = new Escpos\Printer($connector);

		try {
			$output = "Produk -- Jumlah -- Sub Total";
			$printer->text($output);
	    	$printer->feed();
	    	$printer->feed();

			for($i = 0; $i < count($data_nota); $i++) {
				$output = $data_nota[$i][1]." -- ".$data_nota[$i][3]."x -- Rp.".$data_nota[$i][4];
				$printer->text($output);
		    	$printer->feed();
			}
			
	    	$printer->feed();

	    	$printer->setJustification(2);
			$output = "Total Tagihan    Rp.".$total_tagihan;
			$printer->text($output);
		    $printer->feed();

	    	$printer->setJustification(2);
			$output = "Bayar    Rp.".$bayar;
			$printer->text($output);
		    $printer->feed();

	    	$printer->setJustification(2);
			$output = "Kembalian    Rp.".$kembalian;
			$printer->text($output);
		    $printer->feed();
		    $printer->feed();
			$printer->text("Catatan : ".$catatan);
		    $printer->feed();
		    $printer->feed();
		    $printer->feed();

			$printer->text("-----Semoga Lekas Sembuh-----");
			$printer->cut();
			$printer->close();

		} catch (Exception $e) {
		  log_message("error", "Error: Could not print. Message ".$e->getMessage());
		 $printer->close_after_exception();
		}

		echo json_encode(array("status" => TRUE));
	}

}
