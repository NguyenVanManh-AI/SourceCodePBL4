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

class ImportDetailController extends Controller
{
    //
    public function allImportDetails(Request $request) {

        // mặc định 
        $col1='id';
        $col2='provider_name';
        $orderb1='ASC';
        $orderb2='ASC';

        $sortlatest = $request->sortlatest;
        $sortname = $request->sortname;
        
        if($sortlatest == 'true' && $sortname == 'true'){
            // Tên z-a 
            $col1='provider_name';
            $col2='id';
            $orderb1='DESC';
        }
        else {
            // Mới nhất 
            if($sortlatest == 'true') $orderb1='DESC';

            // Tên a-z
            if($sortname == 'true'){
                $col1='provider_name';
                $col2='id';
            }
        }

        $from_date = $request->from_date ; 
        $to_date = $request->to_date; 

        $search = $request->search;
        $imports = Import::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('importer_name','LIKE', '%'.$search.'%')
            ->orWhere('provider_name','LIKE', '%'.$search.'%')
            ->orWhere('provider_tax_id','LIKE', '%'.$search.'%');
        })->where(function($query) use($from_date,$to_date) {
            if($from_date) $query->whereDate('import_date','>=', $from_date.' 00:00:00');
            if($to_date) $query->whereDate('import_date', '<=' , $to_date.' 00:00:00');
        })->paginate(20);

        $imports2 = Import::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('importer_name','LIKE', '%'.$search.'%')
            ->orWhere('provider_name','LIKE', '%'.$search.'%')
            ->orWhere('provider_tax_id','LIKE', '%'.$search.'%');
        })->where(function($query) use($from_date,$to_date) { // truyền biến ngoài vào 
            if($from_date) $query->whereDate('import_date','>=', $from_date.' 00:00:00'); // kiểm tra có tồn tại biến
            if($to_date) $query->whereDate('import_date', '<=' , $to_date.' 00:00:00');  // mới thực hiện query 
            // tối ưu hơn so với kiểm tra bằng tay và tránh lặp lại nhiều câu query 
        })->get();

        $import_ids = []; // mảng lưu các id của import được truy xuất ra 

        foreach ($imports as $import) {
            array_push($import_ids,$import->id); 
        }

        $import_details = []; 
        foreach($import_ids as $import_id){ 
            $import_detail = ImportDetail::where('import_id',$import_id)->get(); 
            array_push($import_details,$import_detail); 
        }

        $sum_prices = [];
        foreach($import_details as $import_detail){
            $sum = 0 ;
            foreach($import_detail as $product){
                $sum += $product->quantity*($product->price)*(100 + $product->tax)/100;
            }
            array_push($sum_prices,$sum); 
        }

        $n = count($imports2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all import details successfully !',
            'import' => $imports,
            'import_detail' => $import_details,
            'sum_price' => $sum_prices
        ], 201);

    }

    public function getDetails(Request $request,$id){
        $import_detail = ImportDetail::where('import_id',$id)->get();

        if(count($import_detail) <= 0){
            return response()->json([
                'message' => 'Get import details false or Import Detail Not Found !',
            ], 401);
        }

        $import = Import::find($id);

        $sum_price = [];
        foreach($import_detail as $product){
            $sum = 0 ;
            $sum += $product->quantity*($product->price)*(100 + $product->tax)/100;
            array_push($sum_price,$sum); 
        }

        return response()->json([
            'message' => 'Get import details successfully !',
            'import_detail' => $import_detail,
            'sum_price' => $sum_price,
            'sum' => array_sum($sum_price),
            'import' => $import
        ], 201);
    }

    /*
        - Note : 
            + Không để whereDate chung với tìm kiếm vì như thế nó sẽ orWhere những cái tìm kiếm và lấy ra hết resource 
        
        - Ta có thể tách ra where function khác để làm  
            $imports = Import::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) { (1)
                $query->where('importer_name','LIKE', '%'.$search.'%')
                ->orWhere('provider_name','LIKE', '%'.$search.'%')
                ->orWhere('provider_tax_id','LIKE', '%'.$search.'%');
            })->where(function($query) use($from_date,$to_date) {   (2)
                if($from_date) $query->whereDate('import_date','>=', $from_date.' 00:00:00');
                if($to_date) $query->whereDate('import_date', '<=' , $to_date.' 00:00:00');
            })->paginate(20);
            
            => Từ đây ta mới thấy được sự tối ưu và sức mạnh của eloquent trong laravel 
            => Hoàn toàn có thể sử dụng như các câu lệnh query bình thường 

            (1) : các biến được sử dụng trong function phải được truyền từ ngoài vào 
            (2) : Sức mạng của function trong eloquent -> dùng if else các kiểu vẫn được 
            => giúp ta rất nhiều . 
                + Kiểm tra có tồn tại biến thì mới thực hiện query 
                => So với cách làm trước , phải kiểm tra sự tồn tại của biến bằng tay 
                rồi từ đó phân trường hợp query nào query nào => rất mất thời gian 
                => gây ra việc code rất nhiều dòng trùng nhau . 

    */

}
