<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\ProviderController; 
use App\Http\Controllers\ImageController; 
use App\Http\Controllers\ImportController; 
use App\Http\Controllers\ImportDetailController; 
use App\Http\Controllers\OrderDetailController; 
use App\Http\Controllers\ResetPasswordController; 
use App\Http\Controllers\SendEmailController; 
use App\Http\Controllers\ShippingAddressController; 
use App\Http\Controllers\UserOrderController;
use App\Models\ImportDetail;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Other (Product,Category,...)  
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/', 'allProducts');
        Route::post('/getwarehouse', 'allProducts2');
        Route::get('/getcategory', 'getCategory');
        Route::get('/{uri}', 'getProduct');
        Route::post('/add', 'add');
        Route::post('/upfile', 'upfile');
        Route::patch('update/{id}', 'update'); // thật ra ở đây nên truyền vào id thì tốt hơn , truyền vào uri chỉ là làm màu thôi 
        Route::delete('/{id}', 'delete');
    });
    /* 
        CHÚ Ý : 
            Route::get('/getcategory', 'getCategory');
            Route::get('/{uri}', 'getProduct');

            get : /getcategory phải để trước get: /{uri} để nó đọc qua file api.php có phải là /getcategory không đã rồi mới đến 
            /{uri} , ví dụ /asdfghjkl1234567890 . 
            => cùng một phương thức GET và mặc khác uri được hash ra ngẫu nhiên nên không thể là /getcategory
            => nên cùng một phương thức GET , cùng một api được gửi lên 
            http://127.0.0.1:8000/api/products/getcategory
            http://127.0.0.1:8000/api/products/asdfghjkl1234567890
            => nên phải để nó đọc trước có phải là getcategory thì gọi hàm getCategory
            còn lại thì gọi hàm getProduct

            nói chung đã có GET : /{uri} -> thì để ý mấy cáci get khác nếu không sẽ gây lỗi 

    */

    Route::middleware('auth:customer_api')->group(function () {
        Route::get('/', 'getAllProduct');
        Route::get('/{id}', 'show');
    });
});

// Category 
Route::prefix('categorys')->controller(CategoryController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/', 'allCategorys');
        Route::post('/add', 'add');
        Route::patch('edit/{id}', 'edit');
        Route::delete('/{id}', 'delete');
    });

    Route::get('/allcategory', 'getall');

    // Route::middleware('auth:customer_api')->group(function () {
    //     Route::get('/', 'getAllProduct');
    //     Route::get('/{id}', 'show');
    // });
});

// Provider 
Route::prefix('providers')->controller(ProviderController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/', 'allProviders');
        Route::post('/add', 'add');
        Route::patch('edit/{id}', 'edit');
        Route::delete('/{id}', 'delete');
    });
});

// Import Product (Nhập kho) , Thật ra nhập kho một lô hàng viết vào Import hay ImportDetail đều được
// nhưng nên viết vào Import

Route::prefix('imports')->controller(ImportController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/add', 'add');
        Route::get('/idmax', 'getIdmax');
        Route::get('/getproduct', 'getProduct');
        Route::get('/getprovider', 'getProvider');
    });
});

Route::prefix('importdetails')->controller(ImportDetailController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/', 'allImportDetails');
        Route::get('/{id}', 'getDetails');
    });
});

Route::get('get-all', 'App\Http\Controllers\ProductController@getAll');
Route::post('add', 'App\Http\Controllers\ProductController@addp');

// Mặc định trong file api là localhost:8000/api/...
// Admin
Route::prefix('admin')->controller(AuthController::class)->group(function () { // các api có chung admin vào chung => /api/admin/...
    Route::post('login', 'login'); // localhost:8000/api/admin/login 

    Route::post('reset-password', 'App\Http\Controllers\ResetPasswordController@sendMail');// quên mật khẩu 
    Route::put('reset-password/{token}', 'App\Http\Controllers\ResetPasswordController@reset');

    Route::middleware('auth:admin_api')->group(function () { // cứ nằm trong middleware là cần 
        Route::post('register', 'register');// supper admin đăng kí 
        Route::post('logout', 'logout');// đăng xuất 
        Route::post('me', 'me');// thông tin cá nhân 
        Route::post('change-password', 'changePassword');// vẫn cần token , vẫn phải thông qua admin_api
        Route::patch('update-profile', 'updateProfile');// cạp nhật thông tin cá nhân 
        Route::delete('{id}', 'deleteAdmin');// supper admin xóa account or supper admin 
        Route::get('all-admin', 'allAdmins');// lấy tất cả admin 
        Route::patch('edit-role', 'editRole');// lấy tất cả admin 
        Route::get('all-user', 'allUsers'); // admin lấy tất cả user 
        Route::patch('edit-status', 'editStatus'); // admin block hoặc unblock user , status : 1 là ok còn 0 là bị block 
        Route::post('upfile', 'upfile'); 
    });
});

// Customer 
Route::prefix('customer')->controller(CustomerAuthController::class)->group(function () {

    Route::post('login', 'login');
    Route::post('register', 'register');

    Route::post('reset-password', 'App\Http\Controllers\ResetPasswordController@sendMail2');
    Route::put('reset-password/{token}', 'App\Http\Controllers\ResetPasswordController@reset2');

    Route::middleware('auth:customer_api')->group(function () {
        Route::post('logout', 'logout');
        Route::post('change-password', 'changePassword');// vẫn cần token , vẫn phải thông qua customer_api
        Route::patch('update-profile', 'updateProfile');// cạp nhật thông tin cá nhân 
        Route::post('me', 'me');
    });
});