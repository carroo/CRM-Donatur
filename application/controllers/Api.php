<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function getSetting()
    {
        $this->db->where('active', 1);
        $query = $this->db->get('tb_chat_settings');
        $setting = $query->row();

        if (!$setting) {
            $this->session->set_flashdata('message_gagal', 'Pastikan setingan telah aktif');
            redirect(base_url('pesan/pengaturan'));
        }

        return $setting;
    }
    public function getRiwayat($status = null, $startKey = null,  &$allData = [])
    {
        $setting = $this->getSetting();
        $bearertoken = $setting->apikey;

        // Inisialisasi cURL
        $ch = curl_init();

        // Buat query string untuk menambahkan start_key jika tersedia
        $url = 'https://api.kirimwa.id/v1/messages';
        $query = [];
        if ($startKey !== null) {
            $query[] = 'start_key=' . $startKey;
        }
        if ($status !== null) {
            $query[] = 'status=' . $status;
        }
        if (!empty($query)) {
            $url .= '?' . implode('&', $query);
        }


        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $bearertoken
        ));

        // Jalankan request
        $response = curl_exec($ch);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            return [];
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            if (isset($responseData['data'])) {
                // Menggabungkan data ke dalam array $allData
                $allData = array_merge($allData, $responseData['data']);

                // Jika ada last_key, panggil kembali fungsi untuk mendapatkan data berikutnya
                if (isset($responseData['meta']['last_key'])) {
                    $startKey = $responseData['meta']['last_key'];
                    $this->getRiwayat($status, $startKey,  $allData);
                }
            }
        }
        curl_close($ch);

        return $allData;
    }
    public function getAllRiwayat()
    {
        $datasuccess = $this->getRiwayat('success');
        $datapending = $this->getRiwayat('pending');
        $datafail = $this->getRiwayat('fail');

        // Menggabungkan semua data menjadi satu array
        $allData = array_merge($datasuccess, $datapending, $datafail);

        // Mengurutkan array berdasarkan 'created_at'
        usort($allData, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });
        return $allData;
    }
}
