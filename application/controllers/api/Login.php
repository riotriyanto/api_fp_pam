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
class Login extends REST_Controller {

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

    public function index_post()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $input['username'];
        $pas = md5(md5(md5(md5(md5(md5($input['password']))))));
        // $cek = $this->db->where('username', $user)->('password', $pas)->get('users')->result();
        $data = $this->db->where('username', $user)->where('password', $pas)->get('users')->result();
        if (count($data) > 0) {
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data',
                'data'      => $data
            ]);
        }else{
            $this->set_response([
                'success'   => false,
                'message'   => 'gagal',
                'data'      => $data
            ]);
        }
            // echo json_encode($data);
        // echo json_encode($user);
        // echo json_encode($data[0]['username']);
    }


}
