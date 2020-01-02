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
class Users extends REST_Controller {

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
            $data = $this->db->where('id_user', $id)->get('users')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data',
                'data'      => $data
            ]);
        }else{

            $data = $this->db->get('users')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data',
                'data'      => $data
            ]);
        }
    }

    public function index_post()
    {
        $username   = $this->post('username');
        $password   = md5(md5(md5(md5(md5(md5($this->post('password')))))));
        $level      = $this->post('level');
        $nama_user  = $this->post('nama_user');
        if ($username != NULL && $this->post('password') != NULL && $level != NULL && $nama_user != NULL) {
            $data = array(
                    'username'   => $username,
                    'password'   => $password,
                    'level'      => $level,
                    'nama_user'  => $nama_user
                );
            if ($this->db->insert('users',$data)) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil menambah user'
                ], REST_Controller::HTTP_OK);
            }else{
                $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal menambah user'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data user tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put()
    {
        $id   = $this->put('id');
        $username   = $this->put('username');
        $password   = md5(md5(md5(md5(md5(md5($this->put('password')))))));
        $level      = $this->put('level');
        $nama_user  = $this->put('nama_user');
        if ($id != NULL && $username != NULL && $level != NULL && $nama_user != NULL) {
            if ($this->put('password') != NULL) {
                $data = array(
                    'username'   => $username,
                    'password'   => $password,
                    'level'      => $level,
                    'nama_user'  => $nama_user
                );
            }else{
                $data = array(
                    'username'   => $username,
                    'level'      => $level,
                    'nama_user'  => $nama_user
                );
            }
            $this->db->where('id_user', $id);
            if ($this->db->update('users',$data)) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah user'
                ], REST_Controller::HTTP_OK);
            }else{
                $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal mengubah user'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data = array(
                    'username'   => $username,
                    'password'   => $password,
                    'level'      => $level,
                    'nama_user'  => $nama_user
                );
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data user tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $_GET['id'];

        if ($id == NULL) {
            $this->set_response([
                    'success'   => false,
                    'message'   => 'Data user tidak lengkap'
                ], REST_Controller::HTTP_NOT_FOUND);
        }else{
            $this->db->where('id_user', $id);
            if ($this->db->delete('users')) {
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil menghapus user'
                ], REST_Controller::HTTP_OK);
            }else{

            $this->set_response([
                    'success'   => false,
                    'message'   => 'Gagal menghapus user'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
