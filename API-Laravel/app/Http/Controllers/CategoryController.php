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
        $search = $request->search;
        $categorys = Category::where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%');
        })->paginate(21);

        $categorys2 = Category::where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%');
        })->get();

        $n = count($categorys2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all categorys successfully !',
            'category' => $categorys
        ], 201);
    }
}
