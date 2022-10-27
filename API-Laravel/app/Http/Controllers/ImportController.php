<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Provider;
use App\Models\ImportDetail; 
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportController extends Controller
{
    /* Note : 
        + Trong trường hợp post lên một hay nhiều bảng thì cũng phải cùng một import_id 
        + Mỗi lần post để cho chắc chắn cả 3 bảng đều được thêm dữ liệu thì validated tất cả các trường để đảm bảo 
        không xảy ra việc chỉ thêm được import còn lại import_detail vs product thì không thêm được do lỗi 

        + Tại client , khởi tạo biến  import_id = 0 sau đó cho lặp qua các bảng để post lên 
            + bảng nào lỗi thì báo lỗi ngay và không thêm được gì (vì như đã nói ở trên là validate tất cả)
                + import_id tại client vẫn giữ nguyên giá trị là 0 
            
            + trong tất cả các bảng được lặp qua chỉ cần có một bảng thành công thì đổi import_id bằng giá trị 
                import_id mà json trả về . 

            + để gán import_id tại client thì kiểm tra nếu như import_id != 0 (tức là chưa thành công lần nào)
            mới được gán . Còn ví dụ đã là import_id = 27 chẳng hạn => đã thành công 
            => cứ thế mà post lên các bảng không lỗi khác . 

        + Dữ liệu ở bảng Import và ImportDetail chỉ có thêm vào chứ không thể xóa và chỉnh sửa .  

    */
    /*

        CHÚ Ý : 
        Vì gặp trục trặc ở client nên ta thay đổi cách thức . 

        MỖI LẦN NHẤN NÚT IMPORT 
        Tại client có 2 giai đoạn  
            1. Lấy ra id lớn nhất của bảng import , ví dụ lấy được id = 70 , trả về client id_import_max = 70 => 
                tương lai sẽ có 71 nên 70 + 1 = 71  
            2. Sau đó http://127.0.0.1:8000/api/imports/add?import_id=71 
                + Lặp qua từng phần tử và post lên 

            Bảng import thì kiểm tra có id 71 rồi thì không thêm nữa . 
            Bảng import_detail thì cứ lấy trực tiếp import_id = 71 mà thêm vào . 

        Note : 
            + Ta sẽ không lo lỗi vì bảng import không xóa hay sửa gì cả . chỉ thêm vào . 
            => Nên ta không lo trường hợp id = ...,60,70 => max hiện tại là 70 nhưng xóa dòng 70 đi thì còn 60 
            => lần sau lại post lên 61 => sai 
            => id max sẽ mãi là cố định . Chỉ có tăng thôi . 

    */

    public function getIdmax(){
        $id_import_max = DB::table('imports')->max('id');
        if($id_import_max == null) $id_import_max = 0 ; // lần nhập kho đầu tiên nên trong data chưa có gì 
        return response()->json([
            'id_import_max' => $id_import_max
        ], 201);
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'importer_name'=>'required|string',
            'product_id'=>'required|integer',
            'product_name'=>'required|string',
            'provider_id' => 'required|integer',
            'provider_name' => 'required|string',
            'provider_tax_id'=>'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'tax' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $import_id = $request->import_id; // lấy biến import_id ra từ request 
        $import = Import::find($import_id); // tìm import có id = import_id 
        // (phòng trường không tạo thêm bảng mới thì vẫn có cái để cho vào json để trả về) => để chắc chắn luôn có $import
        // để trả về json 

        if(empty($import)){ // nếu như không tồn tại bảng đó -> đây là lần post thành công đầu tiên -> tạo bảng 
            // $import_date = Carbon::now(); // lấy ngày hiện tại (của nước ngoài)
            $import_date = Carbon::now('Asia/Ho_Chi_Minh'); // lấy ngày hiện tại của việt nam 
            $import_date->toDateTimeString();
            $import = Import::create(array_merge(
                $validator->validated(),
                ['import_date' => $import_date]// sau khi validated thì thêm một dữ liệu nữa nhưng nó lại sinh lỗi 
            ));// fix bằng cách setnull trường import_date . Vì nó báo lỗi là phải có import_date  
            // trong khi nó chưa đọc tới ['import_date' => $import_date] 
        }

        // tương ứng với lần nhập kho đó sẽ có các hàng dữ liệu nhập sản phẩm vào ,... 
        // nó tương tự với phiếu mượn và chi tiết phiếu mượn ở thư viện 

        $import_details = ImportDetail::create(array_merge(
            $validator->validated(),['import_id'=>$import_id] 
        ));

        $product = Product::find($request->product_id);
        $quantity = $product->quantity;
        $product->update(['quantity'=>$quantity + $request->quantity]); // thêm số lượng vào bảng product 

        return response()->json([
            'message' => 'Import Product successfully ',
            'import' => $import,
            'import_id' => $import_id, // import_id nằm trong $import nhưng để riêng ra như thế này cũng được 
            'import_details' => $import_details,
            'product' => $product
        ], 201);
    }

    // thật ra chỉ nên tìm theo tên là được rồi , nếu thích thì tìm thêm cũng được , vì đôi lúc tìm 
    // nhiều trường quá thì nó sẽ làm chậm hệ thống 
    public function getProduct(Request $request){ 
        $search = $request->search;
        $product = Product::where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%')
            ->orWhere('description','LIKE', '%'.$search.'%')
            ->orWhere('price','LIKE', '%'.$search.'%')
            ->orWhere('material','LIKE', '%'.$search.'%')
            ->orWhere('dimension','LIKE', '%'.$search.'%');
        })->select('id','name')->get(); // không lấy ra cả bảng, chỉ lấy ra id và name cho nhẹ 
        return response()->json([
            'message' => 'Get Product successfully !',
            'product' => $product
        ], 201);
    }

    public function getProvider(Request $request){ 
        $search = $request->search;
        $provider = Provider::where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%')
            ->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%')
            ->orWhere('phone','LIKE', '%'.$search.'%')
            ->orWhere('tax_id_number','LIKE', '%'.$search.'%');
        })->select('id','name','tax_id_number')->get();
        return response()->json([
            'message' => 'Get Provider successfully !',
            'provider' => $provider
        ], 201);
    }

    // CŨ KHÔNG DÙNG NỮA , VÌ giá trị import_id không thay đổi sau mỗi lần gán trong .then của post nên không thực hiện theo cách này được 
    // public function add(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'importer_name'=>'required|string',
    //         'product_id'=>'required|integer',
    //         'product_name'=>'required|string',
    //         'provider_id' => 'required|integer',
    //         'provider_name' => 'required|string',
    //         'provider_tax_id'=>'required|string',
    //         'price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //         'tax' => 'required|numeric'
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $import_id = $request->import_id; // lấy biến import_id ra từ request 
    //     $import = Import::find($import_id); // tìm import có id = import_id 
    //     // (phòng trường không tạo thêm bảng mới thì vẫn có cái để cho vào json để trả về) => để chắc chắn luôn có $import
    //     // để trả về json 

    //     if($import_id==0){ // nếu như import_id = 0 tức là đây là bảng thành công đầu tiên -> tạo mới import 
    //         $import_date = Carbon::now(); // lấy ngày hiện tại 
    //         $import_date->toDateTimeString();
    //         $import = Import::create(array_merge(
    //             $validator->validated(),
    //             ['import_date' => $import_date]// sau khi validated thì thêm một dữ liệu nữa nhưng nó lại sinh lỗi 
    //         ));// fix bằng cách setnull trường import_date . Vì nó báo lỗi là phải có import_date  
    //         // trong khi nó chưa đọc tới ['import_date' => $import_date] 
    //         $import_id = $import->id; // sau khi import thành công thì có id của import đó (của lần nhập kho đó)
    //     }

    //     // tương ứng với lần nhập kho đó sẽ có các hàng dữ liệu nhập sản phẩm vào ,... 
    //     // nó tương tự với phiếu mượn và chi tiết phiếu mượn ở thư viện 

    //     $import_details = ImportDetail::create(array_merge(
    //         $validator->validated(),['import_id'=>$import_id] 
    //     ));

    //     $product = Product::find($request->product_id);
    //     $quantity = $product->quantity;
    //     $product->update(['quantity'=>$quantity + $request->quantity]); // thêm số lượng vào bảng product 

    //     return response()->json([
    //         'message' => 'Import Product successfully ',
    //         'import' => $import,
    //         'import_id' => $import_id, // import_id nằm trong $import nhưng để riêng ra như thế này cũng được 
    //         'import_details' => $import_details,
    //         'product' => $product
    //     ], 201);
    // }
    
}
