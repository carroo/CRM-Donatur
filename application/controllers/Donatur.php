<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Donatur extends CI_Controller
{

    public function index()
    {
        // Load CodeIgniter's URL helper
        $this->load->helper('url');

        // URL to make the GET request to
        $url = "http://103.179.216.69/apicoba/";

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($curl);

        // Close cURL session
        curl_close($curl);

        $datanama =  json_decode($response,true);
        $donatur = $this->db->get('tb_donatur')->result();
        $datalabel = [];
        foreach ($donatur as $key => $value) {
            $this->db->select('tb_mst_labels.*');
            $this->db->from('tb_label_group');
            $this->db->join('tb_mst_labels', 'tb_label_group.label_id = tb_mst_labels.id');
            $this->db->where('tb_label_group.donatur_id', $value->id);
            $datalabel[$key] = $this->db->get()->result();
        }
        $data = array(
            'title' => 'Donatur',
            'halaman' => $this->load->view('donatur/index', [
                'labels' => $this->db->get_where('tb_mst_labels', ['row_active' => 1])->result(),
                'data' => $donatur,
                'datalabel' => $datalabel,
                'datanama' => $datanama
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function tambah()
    {
        $this->form_validation->set_rules('npwp', 'NPWP', 'required|is_unique[tb_donatur.npwp]');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('kota', 'Kota', 'required');
        $this->form_validation->set_rules('notelp', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('donatur'));
        } else {
            // Proses upload foto
            $config['upload_path'] = './public/donatur';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE; // Rename uploaded file

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('foto')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('message_gagal', 'Gagal mengunggah foto: ' . $error);
                redirect(base_url('donatur'));
            } else {
                $upload_data = $this->upload->data();
                $foto_name = $upload_data['file_name'];

                $label_ids = $this->input->post('label_id');

                $data = array(
                    'npwp' => $this->input->post('npwp'),
                    'nama' => $this->input->post('nama'),
                    'kota' => $this->input->post('kota'),
                    'notelp' => $this->input->post('notelp'),
                    'email' => $this->input->post('email'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'foto' => $foto_name // Simpan nama file foto ke database
                );

                $this->db->insert('tb_donatur', $data);
                $donatur_id = $this->db->insert_id();
                foreach ($label_ids as $label_id) {
                    $data_label_group = array(
                        'donatur_id' => $donatur_id,
                        'label_id' => $label_id
                    );
                    $this->db->insert('tb_label_group', $data_label_group);
                }

                $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
                redirect(base_url('donatur'));
            }
        }
    }

    public function update($id)
    {
        $this->form_validation->set_rules('npwp', 'NPWP', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('kota', 'Kota', 'required');
        $this->form_validation->set_rules('notelp', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal diperbarui. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('donatur'));
        } else {

            $label_ids = $this->input->post('label_id');
            // Check if a file is uploaded
            if (!empty($_FILES['foto']['name'])) {
                // Process upload foto
                $config['upload_path'] = './public/donatur';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE; // Rename uploaded file

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('foto')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message_gagal', 'Gagal mengunggah foto: ' . $error);
                    redirect(base_url('donatur'));
                } else {
                    $upload_data = $this->upload->data();
                    $foto_name = $upload_data['file_name'];

                    // Update data with new foto
                    $data = array(
                        'npwp' => $this->input->post('npwp'),
                        'nama' => $this->input->post('nama'),
                        'kota' => $this->input->post('kota'),
                        'notelp' => $this->input->post('notelp'),
                        'email' => $this->input->post('email'),
                        'tgl_lahir' => $this->input->post('tgl_lahir'),
                        'foto' => $foto_name // Simpan nama file foto ke database
                    );

                    // Update data in database
                    $this->db->where('id', $id);
                    $this->db->update('tb_donatur', $data);

                    // Hapus data label_group lama untuk donatur ini
                    $this->db->where('donatur_id', $id);
                    $this->db->delete('tb_label_group');

                    // Simpan data ke tabel tb_label_group untuk setiap label yang dipilih
                    foreach ($label_ids as $label_id) {
                        $data_label_group = array(
                            'donatur_id' => $id,
                            'label_id' => $label_id
                        );
                        $this->db->insert('tb_label_group', $data_label_group);
                    }

                    $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
                    redirect(base_url('donatur'));
                }
            } else {
                // Update data without changing foto
                $data = array(
                    'npwp' => $this->input->post('npwp'),
                    'nama' => $this->input->post('nama'),
                    'kota' => $this->input->post('kota'),
                    'notelp' => $this->input->post('notelp'),
                    'email' => $this->input->post('email'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                );

                // Update data in database
                $this->db->where('id', $id);
                $this->db->update('tb_donatur', $data);

                // Hapus data label_group lama untuk donatur ini
                $this->db->where('donatur_id', $id);
                $this->db->delete('tb_label_group');

                // Simpan data ke tabel tb_label_group untuk setiap label yang dipilih
                foreach ($label_ids as $label_id) {
                    $data_label_group = array(
                        'donatur_id' => $id,
                        'label_id' => $label_id
                    );
                    $this->db->insert('tb_label_group', $data_label_group);
                }

                $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
                redirect(base_url('donatur'));
            }
        }
    }
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_donatur');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('donatur'));
    }
}
