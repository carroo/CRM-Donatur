<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		// Memuat controller lain
		$this->load->model('Api');
	}
	public function index()
	{
		$this->check_ulta();
		$labels = $this->db->get('tb_mst_labels')->result();
		$donaturChart = [];
		foreach ($labels as $key => $value) {
			$donaturChart[$key]['name'] = $value->type;
			$donaturChart[$key]['value'] = $this->db->get_where('tb_label_group', ['label_id' => $value->id])->num_rows();
		}
		// Ambil tahun saat ini
		$year = date('Y');

		// Query untuk mengambil data dari tahun ini
		$this->db->select('MONTH(time) as month, status, COUNT(*) as total');
		$this->db->where('YEAR(time)', $year);
		$this->db->group_by('MONTH(time), status');
		$query = $this->db->get('tb_chat_histories');

		$result = $query->result_array();

		// Buat array untuk menyimpan data berdasarkan bulan
		$data_points = array();

		// Inisialisasi array untuk setiap bulan
		for ($i = 1; $i <= 12; $i++) {
			$data_points[$i] = array(
				'Success' => 0,
				'Fail' => 0,
				'pending' => 0
			);
		}

		// Tambahkan data ke dalam array berdasarkan bulan
		foreach ($result as $row) {
			$month = $row['month'];
			$status = $row['status'];
			$total = $row['total'];

			// Ubah status integer menjadi label
			if ($status == 0) {
				$status_label = 'Pending';
			} elseif ($status == 1) {
				$status_label = 'Success';
			} elseif ($status == -1) {
				$status_label = 'Fail';
			} else {
				continue; // Skip jika status tidak valid
			}

			// Tambahkan jumlah sesuai status dan bulan
			$data_points[$month][$status_label] = $total;
		}

		// Ubah nomor bulan menjadi nama bulan
		$month_names = array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		);

		// Ubah struktur array sesuai dengan yang diinginkan
		$formatted_data = array();
		foreach ($data_points as $month => $data) {
			$formatted_data[] = array(
				'bulan' => $month_names[$month],
				'data' => $data
			);
		}
		$data = array(
			'title' => 'Dashboard',
			'halaman' => $this->load->view('dashboard', [
				'labels' => $this->db->get('tb_mst_labels')->num_rows(),
				'categories' => $this->db->get('tb_mst_categories')->num_rows(),
				'donation_types' => $this->db->get('tb_mst_donation_types')->num_rows(),
				'kanal_donations' => $this->db->get('tb_mst_kanal_donations')->num_rows(),
				'payment_methods' => $this->db->get('tb_mst_payment_methods')->num_rows(),
				'donaturChart' => json_encode($donaturChart),
				'historyChart' => json_encode($formatted_data),
			], TRUE)
		);
		$this->load->view('template', $data);
	}
	public function fixPhoneNumber($number)
	{
		// Menghapus karakter "-" dari nomor telepon
		$number = str_replace('-', '', $number);

		// Jika nomor telepon dimulai dengan "0", ganti dengan "62"
		if (substr($number, 0, 1) === '0') {
			$number = '62' . substr($number, 1);
		}

		// Jika nomor telepon dimulai dengan "+62", hilangkan tanda "+"
		if (substr($number, 0, 3) === '+62') {
			$number = '62' . substr($number, 3);
		}

		return $number;
	}
	public function check_ulta()
	{
		$check = $this->db->get_where('tb_check_ulta', array('tanggal' => date('Y-m-d')))->row();
		if (!$check) {
			$todayDate = date('m-d');
			$birthdayDonors = $this->db->get_where('tb_donatur', "DATE_FORMAT(tgl_lahir, '%m-%d') = '$todayDate'")->result();
			if ($birthdayDonors) {
				$setting = $this->Api->getSetting();
				$device_id = $setting->phonekey;
				$pesan = "Selamat ulang tahun yang penuh berkah! Hari ini, saat kita merayakan langkah baru dalam perjalanan hidupmu, marilah kita bersyukur kepada Allah SWT yang telah memberikan kita kesempatan untuk menghargai nikmat hidup ini. Semoga setiap tahun yang berlalu menjadikanmu semakin dekat dengan-Nya, semakin kuat dalam iman, dan semakin bersyukur atas segala yang telah diberikan.
				Dalam setiap langkah yang kau ambil, ingatlah bahwa Allah selalu bersamamu. Jadikanlah setiap usaha dan perjuanganmu sebagai bentuk ibadah kepada-Nya. Percayalah bahwa di balik setiap ujian dan rintangan, ada hikmah yang Allah sediakan untukmu. Kekuatanmu bukanlah hanya datang dari dirimu sendiri, tetapi dari kepercayaanmu kepada-Nya.
				Hari ini adalah momen untuk merenungkan perjalanan hidupmu, untuk mensyukuri setiap nikmat yang telah diberikan, dan untuk berjanji kepada dirimu sendiri dan kepada Allah untuk terus berusaha menjadi pribadi yang lebih baik. Semoga setiap doa yang kau panjatkan terdengar oleh Allah SWT, dan setiap langkahmu diiringi oleh-Nya.
				Selamat ulang tahun, sahabatku. Semoga umurmu bertambah menjadi berkah, imanmu semakin kokoh, dan cinta-Nya senantiasa mengalir dalam hatimu. Teruslah berjuang, teruslah berdoa, dan percayalah bahwa Allah selalu mengatur yang terbaik untukmu. Amin.";
				foreach ($birthdayDonors as $key => $value) {
					$data = array(
						'phone_number' => $this->fixPhoneNumber($value->notelp),
						'message' => $pesan,
						'device_id' => $device_id,
						'message_type' => "text",
					);
					$res = $this->Api->kirim($data);

					$dataInput = array(
						'kwid' => $res,
						'receiver' => $value->nama,
						'message' => $pesan,
						'sender' => $device_id,
						'status' => 0
					);

					$batchData[] = $dataInput;
				}
				
				$this->db->insert_batch('tb_chat_histories', $batchData);
			}
			$this->db->insert('tb_check_ulta', array('tanggal' => date('Y-m-d')));
		}
		return;
	}
}
