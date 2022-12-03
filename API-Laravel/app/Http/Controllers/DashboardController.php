<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Image; 
use App\Models\ImportDetail; 
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct() {

    }

    public function getCategory(){
        $category = Category::get();
        return response()->json([
            'message' => 'Get Category successfully !',
            'category' => $category
        ], 201);
    }

    public function allProducts3(Request $request){
        $search = $request->searchad;
        $category_name = $request->category_name;
        $price_from = $request->price_from;
        $price_to = $request->price_to;

        $sortlatest = "ASC" ; 
        if($request->sortlatest == "true") $sortlatest = "DESC" ; // "sortlatest":"" => vẫn được tính là null nên chú ý để nó không 
        // null thì truyền vào cái gì đó cx đc ví dụ "sortlatest":"abc" 

        $sortprice = "ASC" ; 
        if($request->sortprice == "true") $sortprice = "DESC" ;  // tại client sẽ cho nếu từ cao đến thấp là true , ngược lại là false
        // trong mysql sắp xếp mặt định là từ thấp đến cao (ASC)
        // muốn đổi thành từ cao đến thấp là DESC 

        // Nên sắp xếp theo giá trước rồi đến id product 
        // Vì khi giá thì có thể nhiều sản phẩm cùng giá , nhưng id thì product hoàn toàn khác nhau 
        // Nên khi sắp xếp theo id produc thì hoặc là tăng dần hoặc là giảm dần => nên những cái khác không quan trọng nữa
        // Nếu sắp xếp giá trước thì khi có 2 sản phẩm cùng giá nó sẽ xét tiếp đến id product 

        /*
            {
                "category_name":"Điện thoại",
                "price_from":17000000,
                "price_to":28000000,
                "search":"IPS",
                "sortlatest":"true",
                "sortprice":"true"
            }
        */

        $products = Product::leftJoin('categories', function($join) {
                $join->on('products.category_id', '=', 'categories.id');
            })->orderBy('products.price',$sortprice)->orderBy('products.id',$sortlatest)->where(function($query1) use($category_name,$sortlatest,$sortprice,$search){

            if($category_name) {
                $query1->where('categories.name',$category_name);
            }
            
            if($sortprice) {
                if($sortprice == "true"){
                    $query1->orderBy('price','DESC'); // nếu từ cao đến thấp
                }
                else {
                    $query1->orderBy('price','ASC'); // mặc định sẽ là từ thấp đến cao 
                }
            }

            if($search) {
                $query1->where('products.name','LIKE', '%'.$search.'%')
                ->orWhere('categories.name','LIKE', '%'.$search.'%')
                ->orWhere('quantity','LIKE', '%'.$search.'%')
                ->orWhere('warranty_period','LIKE', '%'.$search.'%')
                ->orWhere('description','LIKE', '%'.$search.'%')
                ->orWhere('price','LIKE', '%'.$search.'%')
                ->orWhere('material','LIKE', '%'.$search.'%')
                ->orWhere('dimension','LIKE', '%'.$search.'%');
            }


        })->where(function($query2) use($price_from,$price_to) {
            if($price_from) $query2->where('price','>=', $price_from);
            if($price_to) $query2->where('price', '<=' , $price_to);
        })->select(
            'products.*','products.id as product_id','products.name as product_name',
            'categories.*','categories.id as category_id','categories.name as category_name'
        )->paginate(20);

        // Thật ra khi dùng paginate rồi thì cũng không cần đếm số resource cần lấy ra nữa 
        // trong data có to,total,per_page,... các kiểu thì total là tổng số resource lấy ra được 
        // ví dụ ->paginate(10); và có total = 21 thì ta được 3 trang . Dùng luông giá trị total này thay vì 
        // đi tính count như những lần trước 

        //img 
        $idps = []; 
        foreach ($products as $product) { 
            array_push($idps,$product->product_id);
        }
        $imgs = []; 
        foreach($idps as $idp){ 
            $image = Image::where('product_id',$idp)->get(); 
            array_push($imgs,$image); 
        }

        return response()->json([
            'message' => 'Get all product successfully !',
            'product' => $products,
            'img' => $imgs,
        ], 201);
        
    }

    public function getProduct(Request $request,$uri){

        $product = Product::leftJoin('categories', function($join) {
            $join->on('products.category_id', '=', 'categories.id');
        })->where('products.uri',$uri)->select(
            'products.*','products.id as product_id','products.name as product_name',
            'categories.*','categories.id as category_id','categories.name as category_name'
        )->first();

        if($product) {
            $imgs = Image::where('product_id',$product->product_id)->get(); 
            // product_id là thuộc tính ta đặt tên ở phía trên ( 'products.id as product_id' )
            return response()->json([
                'message' => 'Get Product successfully !',
                'product' => $product,
                'images' => $imgs
            ], 201);
        }
        else {
            return response()->json([
                'message' => 'Get Product false or product no found !',
            ], 201);
        }
    }
    
}


// Một lưu ý cực kỳ quan trọng là không nên comment trong Postman
// Sẽ dần đến lỗi và không lấy được thuộc tính . 
/*
    {
        "category_name":"Điện thoại"  ////// Nếu chỉ dùng một dòng này thì chỉ để lại một dòng này 
        // "search":"vanmanh"
        // "price_from":0,
        // "price_to":30000,
        // "sortlatest":"true",
        // "sortprice":"true"

        => còn lại xóa đi , không được comment vì sẽ gây lỗi laravel sẽ không nhận giá trị $request được
    }
 */
