<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Memuat controller lain
        $this->load->model('Api');
    }
    public function index()
    {
        redirect(base_url('pesan/kirim'));
    }

    public function kirim()
    {

        $dataGroup = $this->Api->getGroup();
        if (!$dataGroup) {
            $this->session->set_flashdata('message_gagal', 'Server Wa sedang dalam gangguan');
            redirect(base_url(''));
        }
        $data = array(
            'title' => 'Kirim Pesan',
            'halaman' => $this->load->view('pesan/kirim', [
                'donatur' => $this->db->get('tb_donatur')->result(),
                'ultah' => $this->db->where("DATE_FORMAT(tgl_lahir, '%m-%d') BETWEEN DATE_FORMAT(NOW(), '%m-%d') AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), '%m-%d')")->get('tb_donatur')->result(),
                'group' => $this->Api->getGroup(),
                'labels' => $this->db->get('tb_mst_labels')->result(),
                'template' => $this->db->get('tb_chat_templates')->result(),
            ], TRUE)
        );
        //print_r($this->getGroup());
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
    public function kirim_wa($isGroup)
    {
        if ($this->input->post('labels')) {
            foreach ($this->input->post('labels') as $k => $v) {
                $labelgroup = $this->db->get_where('tb_label_group', ['label_id' => $v])->result();
                foreach ($labelgroup as $key => $value) {
                    $donatur = $this->db->get_where('tb_donatur', ['id' => $value->donatur_id])->row();
                    $notelp[] = $donatur->notelp;
                    $nama[] = $donatur->nama;
                }
            }
        } else {
            $notelp = $this->input->post('notelp');
            $nama = $this->input->post('nama');
        }
        $template = $this->db->get_where('tb_chat_templates', ['id' => $this->input->post('template')])->row();
        $setting = $this->Api->getSetting();
        // Device ID yang digunakan untuk mengambil daftar grup
        $device_id = $setting->phonekey;

        foreach ($notelp as $key => $value) {

            $pesan = str_replace("{nama}", $nama[$key], $template->text);

            $data = array(
                'phone_number' => $isGroup ? $value : $this->fixPhoneNumber($value),
                'device_id' => $device_id,
                'message_type' => "text",
            );

            if ($template->image) {
                $data['message_type'] = 'image';
                $data['message'] = base_url() . '/public/fotopesan/' . $template->image;
                $data['caption'] = $pesan;
            } else {
                $data['message'] = $pesan;
            }

            if ($isGroup) {
                $data['is_group_message'] = true;
            }
            if ($template->send_at && strtotime($template->send_at) > time()) {
                $data['send_at'] = date('Y-m-d\TH:i:sP', strtotime($template->send_at));
            }

            $res = $this->Api->kirim($data);

            $dataInput = array(
                'kwid' => $res,
                'receiver' => $nama[$key],
                'message' => $pesan,
                'sender' => $device_id,
                'status' => 0
            );

            $batchData[] = $dataInput;
        }

        $this->db->insert_batch('tb_chat_histories', $batchData);

        $this->session->set_flashdata('message', 'Whatsapp berhasil dikirim. mohon tunggu jika delay.');
        // echo "<pre>";
        // print_r($res);
        // echo "<pre>";
        redirect(base_url('pesan/kirim'));
    }
    public function template()
    {
        $data = array(
            'title' => 'Template Pesan',
            'halaman' => $this->load->view('pesan/template', [
                'template' => $this->db->get('tb_chat_templates')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function template_tambah()
    {
        $this->form_validation->set_rules('judul', 'Judul ', 'required');
        $this->form_validation->set_rules('pesan', 'Pesan ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('pesan/template'));
        } else {
            $data = array(
                'text' => $this->input->post('pesan'),
                'type' => $this->input->post('judul')
            );

            // Check if an image is uploaded
            if ($this->input->post('use_image')) {
                if (!empty($_FILES['image']['name'])) {
                    // Process upload foto
                    $config['upload_path'] = './public/fotopesan';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE; // Rename uploaded file

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message_gagal', 'Gagal mengunggah foto: ' . $error);
                        redirect(base_url('pesan/template'));
                    } else {
                        $upload_data = $this->upload->data();
                        if ($upload_data['file_ext'] === '.jpg') {
                            $new_file_name = $upload_data['raw_name'] . '.jpeg';
                            $new_file_path = $upload_data['file_path'] . $new_file_name;
                            rename($upload_data['full_path'], $new_file_path);

                            // Update the upload data array to reflect the new file name and path
                            $upload_data['file_name'] = $new_file_name;
                            $upload_data['full_path'] = $new_file_path;
                        }
                        $data['image'] = $upload_data['file_name'];
                    }
                }
            }
            if ($this->input->post('use_send_at')) {
                if ($this->input->post('send_at')) {
                    $data['send_at'] = $this->input->post('send_at');
                }
            }

            $this->db->insert('tb_chat_templates', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('pesan/template'));
        }
    }
    public function template_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_chat_templates');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('pesan/template'));
    }
    public function template_update($id)
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('pesan', 'Pesan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal diperbarui. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('pesan/template'));
        } else {
            $data = array(
                'text' => $this->input->post('pesan'),
                'type' => $this->input->post('judul')
            );

            // Check if an image should be uploaded
            if ($this->input->post('use_image')) {
                if (!empty($_FILES['image']['name'])) {
                    // Process upload foto
                    $config['upload_path'] = './public/fotopesan';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE; // Rename uploaded file

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message_gagal', 'Gagal mengunggah foto: ' . $error);
                        redirect(base_url('pesan/template'));
                    } else {
                        $upload_data = $this->upload->data();
                        if ($upload_data['file_ext'] === '.jpg') {
                            $new_file_name = $upload_data['raw_name'] . '.jpeg';
                            $new_file_path = $upload_data['file_path'] . $new_file_name;
                            rename($upload_data['full_path'], $new_file_path);

                            // Update the upload data array to reflect the new file name and path
                            $upload_data['file_name'] = $new_file_name;
                            $upload_data['full_path'] = $new_file_path;
                        }
                        $data['image'] = $upload_data['file_name'];
                    }
                }
            } else {
                $data['image'] = null;
            }

            // Check if send_at should be updated
            if ($this->input->post('use_send_at')) {
                if ($this->input->post('send_at')) {
                    $data['send_at'] = $this->input->post('send_at');
                }
            } else {
                $data['send_at'] = null;
            }

            $this->db->where('id', $id);
            $this->db->update('tb_chat_templates', $data);

            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('pesan/template'));
        }
    }

    public function riwayat()
    {
        $this->Api->updateStatus();
        $data = array(
            'title' => 'Riwayat Pesan',
            'halaman' => $this->load->view('pesan/riwayat', [
                'data' => $this->db->get('tb_chat_histories')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function pengaturan()
    {
        $setting = $this->db->get('tb_chat_settings')->result();
        $kuota = [];
        foreach ($setting as $key => $value) {
            $apikuota = $this->Api->cekApiKuota($value->apikey);
            $kuota[$key]['limit'] = $apikuota['quota']['messages_per_month'];
            $kuota[$key]['terkirim'] = $apikuota['quota']['current_month_message_sent'];
        }
        $data = array(
            'title' => 'Pengaturan Pesan',
            'halaman' => $this->load->view('pesan/pengaturan', [
                'data' => $setting,
                'kuota' => $kuota
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function pengaturan_use($id)
    {
        $this->db->update('tb_chat_settings', array('active' => 0));

        $this->db->where('id', $id);
        $this->db->update('tb_chat_settings', array('active' => 1));

        $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
        redirect(base_url('pesan/pengaturan'));
    }
    public function pengaturan_phone($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tb_chat_settings');
        $setting = $query->row();

        if (!$setting) {
            $this->session->set_flashdata('message_gagal', 'Pastikan setingan telah aktif');
            redirect(base_url('pesan/pengaturan'));
        }
        $ch = curl_init();

        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/devices');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' .  $setting->apikey
        ));

        // Jalankan request
        $response = curl_exec($ch);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            $this->session->set_flashdata('message_gagal', 'Terjadi kesalahan');
            redirect(base_url('pesan/pengaturan'));
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            if (isset($responseData['data'])) {
                //return $responseData['data'];
                $data = array(
                    'title' => 'Pengaturan Pesan',
                    'halaman' => $this->load->view('pesan/akunwa', [
                        'data' => $responseData['data'],
                        'digunakan' => $setting->phonekey,
                        'idset' => $setting->id
                    ], TRUE)
                );
                $this->load->view('template', $data);
            } else {

                //$this->session->set_flashdata('message_gagal', 'Terjadi kesalahan');
                //redirect(base_url('pesan/pengaturan'));
            }
        }
        curl_close($ch);

        //$this->session->set_flashdata('message_gagal', 'Terjadi kesalahan');
        //redirect(base_url('pesan/pengaturan'));
    }
    public function pengaturan_phone_add($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tb_chat_settings');
        $setting = $query->row();

        if (!$setting) {
            $this->session->set_flashdata('message_gagal', 'Pastikan setingan telah aktif');
            redirect(base_url('pesan/pengaturan'));
        }
        $title = $this->input->post('title');
        // Convert to lowercase
        $title = strtolower($title);
        // Replace non-alphanumeric characters with empty string
        $title = preg_replace("/[^a-z0-9\s]/", "", $title);
        // Replace spaces with dashes
        $title = str_replace(" ", "-", $title);

        $data = array(
            'device_id' => $title,
        );
        $json_data = json_encode($data);
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/devices');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $setting->apikey
        ));

        // Jalankan request
        $response = curl_exec($ch);
        //print_r($response);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            // Handle error
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            // Lakukan sesuatu dengan data response jika diperlukan
        }

        // Tutup koneksi cURL
        curl_close($ch);
        $this->session->set_flashdata('message', 'Data berhasil ditambahkan, silahkan scan QR.');
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function pengaturan_phone_qr($setting_id, $device_id)
    {
        $this->db->where('id', $setting_id);
        $query = $this->db->get('tb_chat_settings');
        $setting = $query->row();

        if (!$setting) {
            $this->session->set_flashdata('message_gagal', 'Pastikan setingan telah aktif');
            redirect(base_url('pesan/pengaturan'));
        }
        $ch = curl_init();

        // Set opsi cURL untuk mengambil daftar grup
        curl_setopt($ch, CURLOPT_URL, 'https://api.kirimwa.id/v1/qr?device_id=' . $device_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $setting->apikey
        ));

        // Jalankan request
        $response = curl_exec($ch);

        // Cek apakah request berhasil atau tidak
        if ($response === false) {
            $error = curl_error($ch);
            $this->session->set_flashdata('message_gagal', 'Terjadi kesalahan');
            redirect(base_url('pesan/pengaturan'));
        } else {
            // Handle response
            $responseData = json_decode($response, true);
            if (isset($responseData['image_url'])) {
                //return $responseData['data'];
                redirect($responseData['image_url']);
            } else {
                $this->session->set_flashdata('message_gagal', 'Terjadi kesalahan');
                redirect(base_url('pesan/pengaturan'));
            }
        }
        curl_close($ch);
        //print_r($response);
    }
    public function pengaturan_phone_use($setting_id, $device_id)
    {
        $this->db->where('id', $setting_id);
        $this->db->update('tb_chat_settings', array('phonekey' => $device_id));

        $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
