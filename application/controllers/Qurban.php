<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qurban extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Memuat controller lain
        $this->load->model('Api');
    }

    public function index()
    {
        $donatur = $this->db->get('tb_donatur')->result();
        $datalabel = [];
        foreach ($donatur as $key => $value) {
            $this->db->select('tb_mst_labels.*');
            $this->db->from('tb_label_group');
            $this->db->join('tb_mst_labels', 'tb_label_group.label_id = tb_mst_labels.id');
            $this->db->where('tb_label_group.donatur_id', $value->id);
            $datalabel[$key] = $this->db->get()->result();
            $pesan[$key] = $this->db->get_where('tb_qurban_tracker', ['donatur_id' => $value->id])->row();
        }
        $data = array(
            'title' => 'Label Qurban Tracking',
            'halaman' => $this->load->view('qurban/index', [
                'labels' => $this->db->get_where('tb_mst_labels', ['row_active' => 1])->result(),
                'data' => $donatur,
                'datalabel' => $datalabel,
                'pesan' => $pesan,
                'template' => $this->db->get('tb_chat_templates')->result(),
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
    public function kirim($id)
    {
        $donatur = $this->db->get_where('tb_donatur', ['id' => $id])->row();
        $setting = $this->Api->getSetting();
        // Device ID yang digunakan untuk mengambil daftar grup
        $device_id = $setting->phonekey;

        $usetemplate = $this->input->post('use_template');
        $data = [
            'phone_number' => $this->fixPhoneNumber($donatur->notelp),
            'device_id' => $device_id,
            'message_type' => 'text',
        ];

        if ($usetemplate) {
            $template = $this->db->get_where('tb_chat_templates', ['id' => $this->input->post('template')])->row();
            $pesan = str_replace("{nama}", $donatur->nama, $template->text);

            if ($template->image) {
                $data['message_type'] = 'image';
                $data['message'] = base_url() . '/public/fotopesan/' . $template->image;
                $data['caption'] = $pesan;
            } else {
                $data['message'] = $pesan;
            }

            if ($template->send_at && strtotime($template->send_at) > time()) {
                $data['send_at'] = date('Y-m-d\TH:i:sP', strtotime($template->send_at));
            }
        } else {
            $pesan = $this->input->post('text');

            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './public/fotopesan';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE; // Rename uploaded file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('foto')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message_gagal', 'Gagal mengunggah foto: ' . $error);
                    redirect(base_url('qurban'));
                    return;
                } else {
                    $upload_data = $this->upload->data();

                    // Check if the file extension is .jpg and rename it to .jpeg
                    if ($upload_data['file_ext'] === '.jpg') {
                        $new_file_name = $upload_data['raw_name'] . '.jpeg';
                        $new_file_path = $upload_data['file_path'] . $new_file_name;
                        rename($upload_data['full_path'], $new_file_path);

                        // Update the upload data array to reflect the new file name and path
                        $upload_data['file_name'] = $new_file_name;
                        $upload_data['full_path'] = $new_file_path;
                    }

                    $foto_name = $upload_data['file_name'];
                    $data['message_type'] = 'image';
                    $data['message'] = base_url() . '/public/fotopesan/' . $foto_name;
                    $data['caption'] = $pesan;
                }
            } else {
                $data['message'] = $pesan;
            }
        }

        $res = $this->Api->kirim($data);

        $dataInput = array(
            'kwid' => $res,
            'receiver' => $donatur->nama,
            'message' => $pesan,
            'sender' => $device_id,
            'status' => 0
        );
        $this->db->insert('tb_chat_histories', $dataInput);

        $check = $this->db->get_where('tb_qurban_tracker', ['donatur_id' => $donatur->id])->row();

        if ($check) {
            if (!$check->pesan1) {
                // Update pesan1 menjadi true
                $this->db->where('donatur_id', $donatur->id);
                $this->db->update('tb_qurban_tracker', ['pesan1' => true]);
            } elseif (!$check->pesan2) {
                // Update pesan2 menjadi true
                $this->db->where('donatur_id', $donatur->id);
                $this->db->update('tb_qurban_tracker', ['pesan2' => true]);
            } elseif (!$check->pesan3) {
                // Update pesan3 menjadi true
                $this->db->where('donatur_id', $donatur->id);
                $this->db->update('tb_qurban_tracker', ['pesan3' => true]);
            }
        } else {
            // Insert data baru dengan nilai pesan1 true
            $this->db->insert('tb_qurban_tracker', ['donatur_id' => $donatur->id, 'pesan1' => true]);
        }

        $this->session->set_flashdata('message', 'Whatsapp berhasil dikirim. mohon tunggu jika delay.');
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        redirect(base_url('qurban'));
    }
}
