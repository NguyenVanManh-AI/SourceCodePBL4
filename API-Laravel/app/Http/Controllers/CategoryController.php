<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    //
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories', // phải là categories (nằm trong database chứ không phải là category)
        ]);// trong bảng categories chỉ tồn tại một tên category ví dụ có "Đồ điện" rồi thì không được thêm tiếp nữa .  

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $category = Category::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Add Name Category successfully ',
            'category' => $category
        ], 201);
    }

    public function edit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories',  
        ]);
        // ví dụ id = 1 , nếu cái hiện tại id=1 là laptop mà gửi lên lại thì nó cũng báo lỗi => không ảnh hưởng gì cả 
        // nếu id = 2 , là pc đã tồn tại mà id = 1 đổi thành pc cũng không được 

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Category::where("id",$id)->update(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Edit Name Category successfully ',
            'id category'
        ], 201);
    }

    public function delete($id)
    {
        try {
            $category =  Category::find($id);
            if ($category) {
                Product::where("category_id",$id)->update(['category_id'=>null]); // cập nhật lại cho các product của catrgory đó có category_id null hết sau đó xóa mới được .  
                $category->delete();
                return response()->json([
                    'message' => 'Delete Category successfully',
                ], 201);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Delete Category false ',
            ], 400);
        }
    }

    public function allCategorys(Request $request) {

        // LƯU Ý : Ở client gửi lên biến true,false (kiểu bolean) nhưng lên tới server thì toàn bộ điều chuyển về string hết 
        // vì thế nếu gửi lên ?a='false' => sau đó ta $a = $request->a 
        // rồi đem so sánh if($a == true) => nó sẽ đúng vì $a lúc này không phải là giá trị false mà là 'false' 
        // 'false' là một chuỗi string => tồn tại => truthy (khác falsy : 0,null,... là được)
        // vì thế ta không được đem so sánh với false mà phải đem so sánh với 'false' hay 'true'  

        // mặc định 
        $col1='id';
        $col2='name';
        $orderb1='ASC';
        $orderb2='ASC';

        $sortlatest = $request->sortlatest;
        $sortname = $request->sortname;
        
        if($sortlatest == 'true' && $sortname == 'true'){
            // Tên z-a 
            $col1='name';
            $col2='id';
            $orderb1='DESC';
        }
        else {
            // Mới nhất 
            if($sortlatest == 'true') $orderb1='DESC';

            // Tên a-z
            if($sortname == 'true'){
                $col1='name';
                $col2='id';
            }
        }


        // Để khỏi phải viết nhiều câu lệnh thì ta tạo biến vào truyền vào cho khỏe . 

        $search = $request->search;
        $categorys = Category::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%');
        })->paginate(21);

        $categorys2 = Category::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%');
        })->get();

        $n = count($categorys2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all categorys successfully !',
            'category' => $categorys
        ], 201);
    }
    // Ta có 2 loại sắp xếp . Sắp xếp theo : Mới nhất và Tên 
    // Mới nhất thì ta sắp xếp theo id 
    // Tên thì theo name . 
    // Nhắt lại kiến thức cũ về mysql . 
    // Nếu trong một câu lệnh có orderby nó sẽ ưu tiên orderby nào đứng trước . 
    // Vì thế nếu như người dùng click chọn sắp xếp theo cả Mới nhất và Tên mà nếu ta để : orderBy('id','DESC')->orderBy('name','ASC')
    // thì nó không khác gì orderBy('id','ASC') bởi vì ưu tiên sắp xếp cái order nào đứng trước -> id trước nên sắp 
    // xếp theo id . MÀ TRONG BẢNG MỖI CATEGORY chỉ có một id nên chỉ sắp xếp theo id . 
    // Ví dụ một bảng có id trùng nhau thì mới tạo ra sự khác biệt so với id 
    // Ví dụ : 
    // stt id name 
    // 1   1  D
    // 2   2  C
    // 3   1  E
    // 4   3  D
    // 5   4  A
    // 6   2  B

    // orderBy('id','DESC')->orderBy('name','ASC') sẽ được 
    // stt id name 
    // 1   1  D
    // 3   1  E
    // 6   2  B
    // 2   2  C
    // 4   3  D
    // 5   4  A

    // mặc định 
    // $col1='id';
    // $col2='name';
    // $orderb1='ASC';
    // $orderb2='ASC';

    // // Mới nhất 
    // $col1='id';
    // $col2='name';
    // $orderb1='DESC';
    // $orderb2='ASC';

    // // Tên a-z
    // $col1='name';
    // $col2='id';
    // $orderb1='ASC';
    // $orderb2='ASC';

    // Tên z-a
    // $col1='name';
    // $col2='id';
    // $orderb1='DESC';
    // $orderb2='ASC';

    // MẶC KHÁC name của ta cũng là duy nhất 
    // => Nên ngoài việc sắp xếp tăng dần hay giảm dần thì :
    // id->name sẽ giống với id
    // name->id sẽ giống với name 
    // => NÊN SẼ KHÔNG CÓ TH Mới nhất và Tên 

    // NHƯ Ở PHÍA TRÊN CŨNG HỌP LÝ : 
    // ban đầu chưa có gì thì để mặc định 
    // click vào mới nhất thì là mới nhất (sắp xếp từ dưới lên)
    // click vào tên (không có mới nhất thì a-z)
    // click vào tên rồi thêm cả mới nhất -> tên cộng sắp xếp từ dưới lên = z-a => HỢP LÝ 

    public function getall(){
        $allcategorys = Category::all();
        $n = count($allcategorys);
        $m = ceil($n/5); 
        $categorys = [];
        $arr = [];
        for($i=0;$i<$n;$i++){
            if($i!=0 && $i%5==0) {
                array_push($categorys,$arr);
                $arr = [];
            }
            array_push($arr,$allcategorys[$i]);
            if($i==$n-1) array_push($categorys,$arr); // những phần tử còn lại (nhỏ hơn 5)
        }

        return response()->json([
            'message' => 'Get all categorys successfully !',
            'category' => $categorys
        ], 201);
    }
}
