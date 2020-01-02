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
class Booking extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Bangkok");

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        $id = $this->get('id');
        $id_user = $this->get('id_user');
        $ket = $this->get('ket');
        if (!empty($id)) {
            $data = $this->db->where('booking.id_booking', $id)->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data by id booking',
                'data'      => $data
            ]);
        }elseif(!empty($id_user)){
            if($ket == 'pending'){
                $data = $this->db->where('booking.keterangan', 'pending')->where('booking.id_user', $id_user)->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengambil data by user pending',
                    'data'      => $data
                ]);
            }elseif($ket == 'disetujui'){
                $data = $this->db->where('booking.keterangan', 'disetujui')->where('booking.id_user', $id_user)->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengambil data by user disetujui',
                    'data'      => $data
                ]);
            }elseif($ket == 'ditolak'){
                $data = $this->db->where('booking.keterangan', 'ditolak')->where('booking.id_user', $id_user)->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengambil data by user ditolak',
                    'data'      => $data
                ]);
            }else{
                $data = $this->db->where('booking.id_user', $id_user)->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
                $this->set_response([
                    'success'   => true,
                    'message'   => 'Berhasil mengambil data by user all ket',
                    'data'      => $data
                ]);
            }
        }elseif($ket == 'pending'){
            $data = $this->db->where('booking.keterangan', 'pending')->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data by pending',
                'data'      => $data
            ]);
        }elseif($ket == 'disetujui'){
            $data = $this->db->where('booking.keterangan', 'disetujui')->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data by disetujui',
                'data'      => $data
            ]);
        }elseif($ket == 'ditolak'){
            $data = $this->db->where('booking.keterangan', 'ditolak')->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data by ditolak',
                'data'      => $data
            ]);
        }
        else{

            $data = $this->db->join('users', 'users.id_user = booking.id_user' )->join('ruangan', 'ruangan.id_ruangan = booking.id_ruangan')->join('jam', 'jam.id_jam = booking.id_jam')->get('booking')->result();
            $this->set_response([
                'success'   => true,
                'message'   => 'Berhasil mengambil data all',
                'data'      => $data
            ]);
            // $data = array('success' => 1, 'message'=>'Berhasil' );
                // echo json_encode($data);
            // $data=json_encode($data);
            // echo json_encode(array($data));
        }
    }

    public function index_post()
    {
        $id_user   = $this->post('id_user');
        $id_ruangan   = $this->post('id_ruangan');
        $id_jam   = $this->post('id_jam');
        $tgl   = $this->post('tgl');
        $keterangan = "pending";
        if ($id_user != NULL && $id_ruangan != NULL && $id_jam != NULL && $tgl != NULL) {
            $cek = $this->db->where('keterangan !=', 'ditolak')->where('id_user', $id_user)->where('id_ruangan', $id_ruangan)->where('id_jam', $id_jam)->where('tgl', $tgl)->get('booking')->result();
            if (count($cek) == 0) {
                $data = array(
                        'id_user'   => $id_user,
                        'id_ruangan'   => $id_ruangan,
                        'id_jam'   => $id_jam,
                        'tgl' =>$tgl,
                        'keterangan'   => $keterangan,
                    );
                if ($this->db->insert('booking',$data)) {
                    $this->set_response([
                        'success'   => true,
                        'message'   => 'Berhasil menambah booking'
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->set_response([
                        'success'   => false,
                        'message'   => 'Gagal menambah booking'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }else{
                $this->set_response([
                        'success'   => false,
                        'message'   => 'Anda tidak dapat membooking ruangan ini pada jam dan tanggal tersebut'
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
        $id_ruangan = $this->put('id_ruangan');
        $id_jam = $this->put('id_jam');
        $tgl = $this->put('tgl');
        $lastupdate = date("Y-m-d H:i:s");
        $aksi = $this->put('aksi');
        if ($id != NULL && $id_ruangan != NULL && $id_jam != NULL && $tgl != NULL && $aksi == NULL) {
            $cek = $this->db->where('keterangan','disetujui')->where('id_booking', $id)->get('booking')->result();
            if (count($cek) == 0) {
                $data = array(
                        'id_ruangan'   => $id_ruangan,
                        'id_jam'   => $id_jam,
                        'tgl'   => $tgl,
                    );
                $this->db->where('id_booking', $id);
                if ($this->db->update('booking',$data)) {
                    $this->set_response([
                        'success'   => true,
                        'message'   => 'Berhasil mengubah booking '.@$aksi
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->set_response([
                        'success'   => false,
                        'message'   => 'Gagal mengubah booking'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }else{
                $this->set_response([
                        'success'   => false,
                        'message'   => 'Anda tidak bisa mengubah data yang sudah disetujui'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        }elseif($aksi != NULL){
                if ($aksi=='menyetujui') {
                    $data = array(
                        'keterangan'   => 'disetujui'
                    );    
                }elseif($aksi=='menolak'){
                    $data = array(
                        'keterangan'   => 'ditolak'
                    );
                }
                $this->db->where('id_booking', $id);
                if ($this->db->update('booking',$data)) {
                    $this->set_response([
                        'success'   => true,
                        'message'   => 'Berhasil mengubah booking '.@$aksi
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->set_response([
                        'success'   => false,
                        'message'   => 'Gagal mengubah booking'
                    ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
        else{
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
            $cek = $this->db->where('id_booking', $id)->where('keterangan','disetujui')->get('booking')->result();
            if (count($cek) == 0) {
                if ($this->db->where('id_booking', $id)->delete('booking')) {
                    $this->set_response([
                        'success'   => true,
                        'message'   => 'Berhasil menghapus booking'
                    ], REST_Controller::HTTP_OK);
                }else{

                    $this->set_response([
                            'success'   => false,
                            'message'   => 'Gagal menghapus booking'
                        ], REST_Controller::HTTP_NOT_FOUND);
                }
            }else{
                $this->set_response([
                            'success'   => false,
                            'message'   => 'Tidak bisa menghapus booking yang sudah disetujui'
                        ], REST_Controller::HTTP_NOT_FOUND);
                    
            }
        }
    }

}
