<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

$routes->post('login', 'Home::pros_log');
$routes->get('lupa_password/(:any)', 'Home::forgot_pass/$1');
$routes->get('lupa_password', 'Home::pageEmail');
$routes->post('lupa_password/sendEmail', 'Home::sendEmail');
$routes->post('lupa_password/send', 'Home::sendReset');
$routes->post('regis', 'Home::reg');

$routes->get('member/login', 'Home::login');
$routes->get('member/register', 'Home::register');
$routes->get('member/logout', 'Home::logout');

$routes->get('portofolio/(:any)/detail', 'Home::detail_porto/$1');
$routes->get('portofolio', 'Home::portofolio');


$routes->get('artikel', 'Home::artikel');
$routes->get('artikel/(:any)/detail', 'Home::d_artikel/$1');
$routes->get('artikel/kategori/(:any)', 'Home::kategori/$1');
$routes->get('detail-promo/(:any)', 'Home::d_promo/$1');
$routes->get('kontak', 'Home::hubungi');
$routes->get('tentang-kami', 'Home::tentang_kami');
$routes->get('simulasi-kpr', 'Home::simulasi');

// $routes->group('',['filter' => 'auth'], function ($routes) {
    $routes->get('order', 'Home::order');
    $routes->post('kategori', 'Home::getKategori');
    $routes->post('getHarga', 'Home::getHarga');
    $routes->post('order/desain', 'Home::order_desain');
    $routes->post('order/no_desain', 'Home::order_non');
    $routes->get('order/sukses', 'Home::order_sukses');

    $routes->get('member/akun', 'Home::akun');
    $routes->get('member/edit_profile', 'Home::edit_profile');
    $routes->post('member/update_profile', 'Home::update_profile');
    $routes->get('member/tentang-mitra', 'Home::tentang_mitra');
    $routes->get('member/riwayat', 'Home::riwayat_projek');
    $routes->get('member/ubah_password', 'Home::changePass');
    $routes->post('member/change_password', 'Home::updPass');
    $routes->get('member/qa', 'Home::qa');
    $routes->get('member/syarat', 'Home::snk');
// });

$routes->group('api', function ($routes) {
    $routes->group('v1', function ($routes) {
       
//  $routes->post('merawat', 'Api\SimulasiKpr::merawat');
        $routes->group('auth', function ($routes) {
            $routes->post('login', 'Api\LoginController::login');
            $routes->post('register', 'Api\LoginController::register');
            $routes->post('reset_pass', 'Api\LoginController::resetPass_luar');
        });
        
        $routes->post('sendEmailReset', 'Api\LoginController::send_email_reset');
        
        $routes->post('fcm_update', 'Api\LoginController::updateFcm');
        $routes->group('material', function ($routes) {
            $routes->get('/', 'Api\MaterialController::index');
            $routes->get('(:any)/detail', 'Api\MaterialController::show/$1');
        });

        $routes->group('artikel', function ($routes) {
            $routes->get('/', 'Api\ArtikelController::index');
            $routes->get('(:num)/detail', 'Api\ArtikelController::show/$1');
            $routes->get('category/(:num)', 'Api\ArtikelController::category_detail/$1');
            $routes->get('category', 'Api\ArtikelController::category');
        });

        $routes->group('kategori', function ($routes) {
            $routes->get('/', 'Api\Home::KategoriJasa');
            $routes->get('(:num)/detail', 'Api\Home::subKategori/$1');
        });
            
        $routes->group('promo', function ($routes) {
            $routes->get('/', 'Api\Home::PromoAll');
            $routes->get('(:num)/detail', 'Api\Home::showPromo/$1');
        });

        $routes->group('order', function ($routes) {
            $routes->post('/', 'Api\TransaksiController::orderProjekDesain');
            $routes->get('type_order', 'Api\ProjectController::type_order');
            $routes->get('tipe_rumah', 'Api\TransaksiController::tipe_rumah');
            $routes->post('desain', 'Api\TransaksiController::orderProjekDesain');
        });

        $routes->group('kpr', function ($routes) {
            $routes->get('snk', 'Api\TentangController::snk_kpr');            
            $routes->post('simulasi', 'Api\SimulasiKpr::perhitungan_kpr');            
            $routes->post('pengajuan', 'Api\SimulasiKpr::create');            
        });

        $routes->get('projek/spek', 'Api\ProjectController::spek');
        $routes->get('type_order', 'Api\ProjectController::type_order');
        $routes->get('Area', 'Api\SimulasiKpr::index_area');            
        $routes->get('Prov', 'Api\SimulasiKpr::province');            
        $routes->get('rules', 'Api\TentangController::RulesOrder');            
        $routes->get('tentang-kami', 'Api\TentangController::about');
        $routes->get('tanya-jawab', 'Api\QaController::index');
        $routes->get('syarat-ketentuan-aplikasi', 'Api\TentangController::syarat');
        $routes->get('testimoni', 'Api\Testimoni::index');
        $routes->post('notifmidtrans', 'Api\TransaksiController::paymentstatus');        
        $routes->post('notifiris', 'Api\TransaksiController::irisnotif');        

        $routes->group('', ['filter' => 'token'], function ($routes) {
            $routes->group('auth', function ($routes) {
                
                $routes->get('profile', 'Api\LoginController::profile');
                $routes->post('editProfile', 'Api\LoginController::uploadImage');
                $routes->post('reset_password', 'Api\LoginController::resetPass');
                $routes->post('logout', 'Api\LoginController::signout');
            });

            $routes->group('pemberitauan', function ($routes) {
                $routes->get('/', 'Api\NotifikasiController::index');
            });            

            $routes->group('cart', function ($routes) {
                $routes->get('/', 'Api\TransaksiController::index');
                $routes->post('add', 'Api\TransaksiController::addcart'); 
                $routes->post('add_qty', 'Api\TransaksiController::add_by_qty'); 
                $routes->post('del_qty', 'Api\TransaksiController::del_by_qty'); 
            });
            
            $routes->post('bayar', 'Api\TransaksiController::payment');
            // $routes->post('notifmidtrans', 'Api\TransaksiController::paymentstatus');           

            $routes->group('projek', function ($routes) {
                $routes->get('/', 'Api\ProjectController::index');
                $routes->get('done', 'Api\ProjectController::projectDone');
                $routes->get('progres', 'Api\ProjectController::projectProgres');
                $routes->get('(:num)/detail', 'Api\ProjectController::detail/$1');
            });
            
            $routes->group('chat', function ($routes) {
                $routes->post('/', 'Api\QaController::detail_chat');
                $routes->get('list', 'Api\QaController::listChat');
                $routes->post('kirim', 'Api\QaController::store_chat');
            });           
        });
    });
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
