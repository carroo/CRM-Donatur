<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Memuat controller lain
        $this->load->model('Api');
    }
    public function label()
    {

        $data = array(
            'title' => 'Master Label',
            'halaman' => $this->load->view('master/label/index', [
                'data' => $this->db->get('tb_mst_labels')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function label_tambah()
    {
        $this->form_validation->set_rules('type', 'Label ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/label'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
                'row_active' => 1
            );

            $this->db->insert('tb_mst_labels', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/label'));
        }
    }
    public function label_update($id)
    {

        $this->form_validation->set_rules('type', 'Label', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/label'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_mst_labels', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/label'));
        }
    }
    public function label_status($row_active, $id)
    {
        $data = array(
            'row_active' => $row_active,
        );
        $this->db->where('id', $id);
        $this->db->update('tb_mst_labels', $data);
        $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
        redirect(base_url('master/label'));
    }
    public function label_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mst_labels');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/label'));
    }
    public function tambah_batch()
    {
        $type = $this->input->post('type');
        $row_active = $this->input->post('row_active');

        $data = array();
        foreach ($type as $key => $value) {
            $aktif = isset($row_active[$key]) && $row_active[$key] == '1' ? 1 : 0;
            $data[] = array(
                'type' => $value,
                'row_active' => $aktif,
            );
        }

        $this->db->insert_batch('tb_mst_labels', $data);

        $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');

        redirect(base_url('master/label'));
    }
    public function kanal_donasi()
    {

        $data = array(
            'title' => 'Master Kanal Donasi',
            'halaman' => $this->load->view('master/kanal_donasi/index', [
                'data' => $this->db->get('tb_mst_kanal_donations')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function kanal_donasi_tambah()
    {
        $this->form_validation->set_rules('type', 'Kanal Donasi ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/kanal_donasi'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );

            $this->db->insert('tb_mst_kanal_donations', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/kanal_donasi'));
        }
    }
    public function kanal_donasi_update($id)
    {

        $this->form_validation->set_rules('type', 'Kanal Donasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/kanal_donasi'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_mst_kanal_donations', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/kanal_donasi'));
        }
    }
    public function kanal_donasi_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mst_kanal_donations');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/kanal_donasi'));
    }
    public function jenis_donasi()
    {

        $data = array(
            'title' => 'Master Jenis Donasi',
            'halaman' => $this->load->view('master/jenis_donasi/index', [
                'data' => $this->db->get('tb_mst_donation_types')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function jenis_donasi_tambah()
    {
        $this->form_validation->set_rules('type', 'Jenis Donasi ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/jenis_donasi'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );

            $this->db->insert('tb_mst_donation_types', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/jenis_donasi'));
        }
    }
    public function jenis_donasi_update($id)
    {

        $this->form_validation->set_rules('type', 'Jenis Donasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/jenis_donasi'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_mst_donation_types', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/jenis_donasi'));
        }
    }
    public function jenis_donasi_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mst_donation_types');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/jenis_donasi'));
    }
    public function kategori()
    {

        $data = array(
            'title' => 'Master Kategori',
            'halaman' => $this->load->view('master/kategori/index', [
                'data' => $this->db->get('tb_mst_categories')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function kategori_tambah()
    {
        $this->form_validation->set_rules('type', 'Kategori ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/kategori'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );

            $this->db->insert('tb_mst_categories', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/kategori'));
        }
    }
    public function kategori_update($id)
    {

        $this->form_validation->set_rules('type', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/kategori'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_mst_categories', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/kategori'));
        }
    }
    public function kategori_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mst_categories');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/kategori'));
    }
    public function metode_pembayaran()
    {

        $data = array(
            'title' => 'Master Metode Pembayaran',
            'halaman' => $this->load->view('master/metode_pembayaran/index', [
                'data' => $this->db->get('tb_mst_payment_methods')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function metode_pembayaran_tambah()
    {
        $this->form_validation->set_rules('type', 'Metode Pembayaran ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/metode_pembayaran'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );

            $this->db->insert('tb_mst_payment_methods', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/metode_pembayaran'));
        }
    }
    public function metode_pembayaran_update($id)
    {

        $this->form_validation->set_rules('type', 'Metode Pembayaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/metode_pembayaran'));
        } else {
            $data = array(
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_mst_payment_methods', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/metode_pembayaran'));
        }
    }
    public function metode_pembayaran_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mst_payment_methods');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/metode_pembayaran'));
    }

    public function api()
    {

        $data = array(
            'title' => 'Master Metode Pembayaran',
            'halaman' => $this->load->view('master/api/index', [
                'data' => $this->db->get('tb_chat_settings')->result(),
            ], TRUE)
        );
        $this->load->view('template', $data);
    }
    public function api_tambah()
    {
        $this->form_validation->set_rules('apikey', 'Metode Pembayaran ', 'required');
        $this->form_validation->set_rules('type', 'Metode Pembayaran ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/api'));
        } else {
            $cek = $this->Api->cekApiKey($this->input->post('apikey'));
            if (!$cek) {
                $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Apikey tidak terdaftar.');
                redirect(base_url('master/api'));
            }
            $data = array(
                'apikey' => $this->input->post('apikey'),
                'type' => $this->input->post('type'),
            );

            $this->db->insert('tb_chat_settings', $data);

            $this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
            redirect(base_url('master/api'));
        }
    }
    public function api_update($id)
    {

        $this->form_validation->set_rules('type', 'Metode Pembayaran', 'required');
        $this->form_validation->set_rules('apikey', 'Metode Pembayaran ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_gagal', 'Data gagal ditambahkan. Silakan lengkapi formulir dengan benar.');
            redirect(base_url('master/api'));
        } else {
            $cek = $this->Api->cekApiKey($this->input->post('apikey'));
            if (!$cek) {
                $this->session->set_flashdata('message_gagal', 'Data gagal diupdate. Apikey tidak terdaftar.');
                redirect(base_url('master/api'));
            }
            $data = array(
                'apikey' => $this->input->posy('apikey'),
                'type' => $this->input->post('type'),
            );
            $this->db->where('id', $id);
            $this->db->update('tb_chat_settings', $data);
            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect(base_url('master/api'));
        }
    }
    public function api_hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_chat_settings');

        $this->session->set_flashdata('message', 'Data berhasil dihapus.');

        redirect(base_url('master/api'));
    }
}
