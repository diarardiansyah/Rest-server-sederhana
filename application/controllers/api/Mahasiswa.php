<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Mahasiswa extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model', 'mahasiswa');
        $this->methods['index_get']['limit'] = 500;
        $this->methods['index_delete']['limit'] = 500;
        $this->methods['index_post']['limit'] = 500;
        $this->methods['index_put']['limit'] = 500;
    }

    public function index_get()
    {   
        $id_mahasiswa = $this->get('id_mahasiswa'); // <- Untuk mengambil data mahasiswa berdasarkan ID

        if ( $id_mahasiswa === null ) {
            $mahasiswa = $this->mahasiswa->getAllDataMhs();  
        } else {
            $mahasiswa = $this->mahasiswa->getAllDataMhs($id_mahasiswa);
        }
        
        if ( $mahasiswa ) {
            $this->response([
                'status' => TRUE,
                'data' => $mahasiswa
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'id mahasiswa not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id_mahasiswa = $this->delete('id_mahasiswa');

        if ( $id_mahasiswa === null ) {
            $this->response([
                'status' => FALSE,
                'message' => 'provide an id mahasiswa!.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ( $this->mahasiswa->deleteMhs($id_mahasiswa) > 0 ) {
                // Jalankan fungsi delete
                $this->response([
                    'status' => TRUE,
                    'id_mahasiswa' => $id_mahasiswa,
                    'message' => 'data mahasiswa deleted.'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                // Jika id mahasiswa yang dikirimkan tidak ada di database
                $this->response([
                    'status' => FALSE,
                    'message' => 'id mahasiswa not found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
            'nim' => $this->post('nim'),
            'jurusan' => $this->post('jurusan'),
            'tempat_tinggal' => $this->post('tempat_tinggal'),
            'jenis_kelamin' => $this->post('jenis_kelamin')
        ];

        if ( $this->mahasiswa->createMhs($data) > 0 ) {
            $this->response([
                'status' => TRUE,
                'message' => 'data mahasiswa successfully created.',
                'data' => $data
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to create data mahasiswa'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id_mahasiswa = $this->put('id_mahasiswa');

        $data = [
            'nama' => $this->put('nama'),
            'nim' => $this->put('nim'),
            'jurusan' => $this->put('jurusan'),
            'tempat_tinggal' => $this->put('tempat_tinggal'),
            'jenis_kelamin' => $this->put('jenis_kelamin')
        ];

        if ( $this->mahasiswa->updateMhs($data, $id_mahasiswa) > 0 ) {
            $this->response([
                'status' => TRUE,
                'message' => 'data mahasiswa has been updated.',
                'data' => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to update data mahasiswa.!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

    }

}