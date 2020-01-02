<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Jam extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        $id = $this->get('id');
        if (!empty($id)) {
            $data = $this->db->where('id_jam', $id)->get('jam')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data',
                'data'      => $data
            ]);
        }else{

            $data = $this->db->get('jam')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data',
                'data'      => $data
            ]);
        }
    }

    public function index_post()
    {
        $urai_jam   = $this->post('urai_jam');
        if ($urai_jam != NULL) {
            $data = array(
                    'urai_jam'   => $urai_jam,
                );
            if ($this->db->insert('jam',$data)) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil menambah jam'
                ], REST_Controller::HTTP_OK);
            }else{
                $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal menambah jam'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put()
    {
        $id   = $this->put('id');
        $urai_jam   = $this->put('urai_jam');
        if ($id != NULL && $urai_jam != NULL) {
            $this->db->where('id_jam', $id);
            $data = array(
                    'urai_jam'   => $urai_jam,
                );
            if ($this->db->update('jam',$data)) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah jam'
                ], REST_Controller::HTTP_OK);
            }else{
                $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal mengubah jam'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $_GET['id'];

        if ($id == NULL) {
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }else{
            $this->db->where('id_jam', $id);
            if ($this->db->delete('jam')) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil menghapus jam'
                ], REST_Controller::HTTP_OK);
            }else{

            $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal menghapus jam'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
