<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Api extends CI_Model
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

    public function getGroup()
    {

        $setting = $this->getSetting();
        // Device ID yang digunakan untuk mengambil daftar grup
        $device_id = $setting->phonekey;
        // Bearertoken yang diperlukan untuk otorisasi
        $bearertoken = $setting->apikey;

        // Inisialisasi cURL
        $ch = curl_init();

        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/groups?device_id=' . $device_id);
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
                return $responseData['data'];
            } else {
                return [];
            }
        }
        curl_close($ch);
        return [];
    }

    public function kirim($data)
    {
        $setting = $this->getSetting();
        $bearertoken = $setting->apikey;
        // Konversi data menjadi format JSON
        $json_data = json_encode($data);

        // Inisialisasi cURL
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $bearertoken
        ));

        // Jalankan request
        $response = curl_exec($ch);
        //print_r($response);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);

            return $error;
            // Handle error
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            // Lakukan sesuatu dengan data response jika diperlukan
            return $responseData['id'];
        }

        // Tutup koneksi cURL
        curl_close($ch);

        return $response;
    }

    public function updateStatus()
    {
        $setting = $this->getSetting();
        // Bearertoken yang diperlukan untuk otorisasi
        $bearertoken = $setting->apikey;
        $history = $this->db->get_where('tb_chat_histories', ['status' => 0])->result();
        foreach ($history as $key => $value) {

            // Inisialisasi cURL
            $ch = curl_init();

            // Set opsi cURL untuk mengambil daftar grup
            curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/messages/' . $value->kwid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $bearertoken
            ));

            // Jalankan request
            $response = curl_exec($ch);

            // Cek apakah request berhasil atau tidak
            if ($response === false) {
                $error = curl_error($ch);
            } else {
                // Handle response
                $responseData = json_decode($response, true);
                if ($responseData['status'] == 'success') {
                    $this->db->where('id', $value->id);
                    $this->db->update('tb_chat_histories', ['status' => 1]);
                } else if ($responseData['status'] == 'fail') {
                    $this->db->where('id', $value->id);
                    $this->db->update('tb_chat_histories', ['status' => -1, 'note' => $responseData['message']]);
                }
            }
            curl_close($ch);
        }
        return;
    }

    public function cekApiKey($apikey)
    {
        $ch = curl_init();

        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/quotas');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $apikey
        ));

        // Jalankan request
        $response = curl_exec($ch);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            return false;
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            if (isset($responseData['user_id'])) {
                return true;
            } else {
                return false;
            }
        }
        curl_close($ch);
        return false;
    }
    public function cekApiKuota($apikey)
    {
        $ch = curl_init();

        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/quotas');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $apikey
        ));

        // Jalankan request
        $response = curl_exec($ch);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            return false;
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            if (isset($responseData['user_id'])) {
                return $responseData;
            } else {
                return false;
            }
        }
        curl_close($ch);
        return false;
    }
}
