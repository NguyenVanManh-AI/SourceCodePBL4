<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Image; // xóa product đi thì xóa ảnh của nó đi luôn (không giữ lại nặng máy)
use App\Models\ImportDetail; // xóa product đi thì cũng set cho product_id của import_detail về null
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:customer_api', ['except' => ['getAllProduct', 'getAll','addp','verycode','upfile','loginGoogle','getfile',
        // 'testGet','testPost','realTime']]);
        $this->middleware('auth:admin_api', ['except' => ['upfile']]);
    }

    public function getCategory(Request $request){
        $search = $request->search;
        $category = Category::where('name','LIKE', '%'.$search.'%')->get();
        return response()->json([
            'message' => 'Get Category successfully !',
            'category' => $category
        ], 201);
    }

    public function add(Request $request)
    {
        // if($request->category_id == "null") $request->category_id = null;
        
        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'warranty_period'=>'required|date',
            'description'=>'required|string',
            'category_id'=>'numeric|nullable', // hoặc là có thể null hoặc là phải là số "6" hoặc 6 đều được 
            'price'=>'required|numeric',  
            'material'=>'required|string',
            'dimension'=>'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $bytes = random_bytes(10);
        $transaction_id = (bin2hex($bytes)); // hash (random ra uri)

        $product = Product::create(array_merge(
            $validator->validated(),
            ['quantity' => 0,'uri' => $transaction_id] // tạo mới sản phẩm (chưa nhập kho thì cho số lượng là 0)  
        ));

        return response()->json([
            'message' => 'Add Product successfully !',
            'product' => $product
        ], 201);
    }

    // thực tế thì dưới client có 10 cái ảnh thì mình phải gọi 10 lệnh api upfile từng cái ảnh một 
    public function upfile(Request $request) {// photo là dưới client gửi lên 
        $pathToFile = $request->file('photo')->store('products','public'); // lưu vào folder products
        DB::table('images')->insert([
            'product_id'=>$request->id,
            'image_path'=> 'storage/'.$pathToFile
        ]);
        return response()->json([
            "link"=> 'storage/'.$pathToFile
        ],200);
    } 

    public function delete($id)
    {
        try {
            $product =  Product::find($id);
            if ($product) {
                ImportDetail::where("product_id",$id)->update(['product_id'=>null]);  // set null cho ImportDetail
                $imgs = Image::where("product_id",$id)->get(); // với ảnh thì không set null mà xóa hết các ảnh liên quan của product đó
                if($imgs){ // lấy ra các img có product_id đó (nếu có) và lặp qua từng cái 
                    foreach($imgs as $img){
                        File::delete($img->image_path); // xóa ảnh 
                        $i = Image::find($img->id);     // xóa hàng dữ liệu đó 
                        $i->delete();
                    }
                }
                $product->delete(); // xong hết rồi mới xóa product đó 
                return response()->json([
                    'message' => 'Delete Product successfully !',
                ], 201);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Delete Product false !',
            ], 400);
        }
    }

    public function allProducts(Request $request) {

        // mặc định 
        $col1='products.id';
        $col2='products.name';
        $orderb1='ASC';
        $orderb2='ASC';

        $sortlatest = $request->sortlatest;
        $sortname = $request->sortname;
        
        if($sortlatest == 'true' && $sortname == 'true'){
            // Tên z-a 
            $col1='products.name';
            $col2='products.id';
            $orderb1='DESC';
        }
        else {
            // Mới nhất 
            if($sortlatest == 'true') $orderb1='DESC';

            // Tên a-z
            if($sortname == 'true'){
                $col1='products.name';
                $col2='products.id';
            }
        }

        $search = $request->search;
        $products = Product::leftJoin('categories', function($join) {
            $join->on('products.category_id', '=', 'categories.id');
          })->orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('products.name','LIKE', '%'.$search.'%')
            ->orWhere('categories.name','LIKE', '%'.$search.'%')
            ->orWhere('quantity','LIKE', '%'.$search.'%')
            ->orWhere('warranty_period','LIKE', '%'.$search.'%')
            ->orWhere('description','LIKE', '%'.$search.'%')
            ->orWhere('price','LIKE', '%'.$search.'%')
            ->orWhere('material','LIKE', '%'.$search.'%')
            ->orWhere('dimension','LIKE', '%'.$search.'%');
        })->select(
            'products.*','products.id as product_id','products.name as product_name',
            'categories.*','categories.id as category_id','categories.name as category_name'
        )->paginate(10);

        // vì products và categories đều có các cột id và name nên ta dùng AS 

        $products2 = Product::leftJoin('categories', function($join) {
            $join->on('products.category_id', '=', 'categories.id');
          })->orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('products.name','LIKE', '%'.$search.'%')
            ->orWhere('categories.name','LIKE', '%'.$search.'%')
            ->orWhere('quantity','LIKE', '%'.$search.'%')
            ->orWhere('warranty_period','LIKE', '%'.$search.'%')
            ->orWhere('description','LIKE', '%'.$search.'%')
            ->orWhere('price','LIKE', '%'.$search.'%')
            ->orWhere('material','LIKE', '%'.$search.'%')
            ->orWhere('dimension','LIKE', '%'.$search.'%');
        })->select(
            'products.*','products.id as product_id','products.name as product_name',
            'categories.*','categories.id as category_id','categories.name as category_name'
        )->get();

        // vừa là chưa được phân loại nhưng trong những cái chưa được phân loại category đó
        // ta cũng phải search các thông tin của product được  
        if($request->unclassified == 'true'){ 
            $products = Product::leftJoin('categories', function($join) {
                $join->on('products.category_id', '=', 'categories.id');
            })->orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('products.name','LIKE', '%'.$search.'%')
            ->orWhere('quantity','LIKE', '%'.$search.'%')
            ->orWhere('warranty_period','LIKE', '%'.$search.'%')
            ->orWhere('description','LIKE', '%'.$search.'%')
            ->orWhere('price','LIKE', '%'.$search.'%')
            ->orWhere('material','LIKE', '%'.$search.'%')
            ->orWhere('dimension','LIKE', '%'.$search.'%');
            })->whereNull('products.category_id')
            ->select(
                'products.*','products.id as product_id','products.name as product_name',
                'categories.*','categories.id as category_id','categories.name as category_name'
            )->paginate(10);
    
            $products2 = Product::leftJoin('categories', function($join) {
                $join->on('products.category_id', '=', 'categories.id');
            })->orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
                $query->where('products.name','LIKE', '%'.$search.'%')
                ->orWhere('quantity','LIKE', '%'.$search.'%')
                ->orWhere('warranty_period','LIKE', '%'.$search.'%')
                ->orWhere('description','LIKE', '%'.$search.'%')
                ->orWhere('price','LIKE', '%'.$search.'%')
                ->orWhere('material','LIKE', '%'.$search.'%')
                ->orWhere('dimension','LIKE', '%'.$search.'%');
            })->whereNull('products.category_id')
            ->select(
                'products.*','products.id as product_id','products.name as product_name',
                'categories.*','categories.id as category_id','categories.name as category_name'
            )->get();
        }
        // XỬ LÝ LẤY RA CÁC BỘ ẢNH TƯƠNG ỨNG 
        $idps = []; // mảng lưu các giá trị product_id 

        foreach ($products as $product) { // lượt qua các product của resource 
            // echo $product->product_id;
            array_push($idps,$product->product_id); // lấy ra các product_id của resource đó và thêm vào arr 
        }
        // print_r($arr);

        // CHÚ Ý : product_id là cột ta dùng AS để tự đặt tên ở phía trên (có hiệu lực trong resource) và bây giờ 
        // lấy ra dùng vẫn được  

        // $images = Image::whereIn('product_id',$arr)->get();

        $imgs = []; // mảng lưu các bộ ảnh 
        foreach($idps as $idp){ // lặp qua mảng id các sản phẩm 
            $image = Image::where('product_id',$idp)->get(); // ứng với mỗi id của product thì có bộ ảnh product đó 
            array_push($imgs,$image); // thêm bộ ảnh vào mảng ảnh 
        }
        // XỬ LÝ LẤY RA CÁC BỘ ẢNH TƯƠNG ỨNG 


        $n = count($products2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all providers successfully !',
            'product' => $products,
            // 'image' => $images,
            // 'arr' => $arr,
            'img' => $imgs,
        ], 201);

        // imgs là mảng của mảng các ảnh : 
        /*
    "img": [
        [
            {
                "id": 1,
                "product_id": 1,
                "image_path": "storage/products/asus_so_ba.jpg",
                "created_at": "2022-10-18T09:34:02.000000Z",
                "updated_at": "2022-10-28T09:34:02.000000Z"
            },
            {
                "id": 5,
                "product_id": 1,
                "image_path": "storage/products/asus_so_mot.jpg",
                "created_at": "2022-10-25T09:37:49.000000Z",
                "updated_at": "2022-10-10T09:37:49.000000Z"
            },
            {
                "id": 6,
                "product_id": 1,
                "image_path": "storage/products/asus_so_hai.jpg",
                "created_at": "2022-10-26T09:38:12.000000Z",
                "updated_at": "2022-10-10T09:38:12.000000Z"
            }
        ],
        [
            {
                "id": 2,
                "product_id": 2,
                "image_path": "storage/products/macbook_so_ba.jpg",
                "created_at": "2022-10-16T09:35:08.000000Z",
                "updated_at": "2022-10-28T09:35:08.000000Z"
            },
            {
                "id": 3,
                "product_id": 2,
                "image_path": "storage/products/macbook_so_hai.jpg",
                "created_at": "2022-10-12T09:36:05.000000Z",
                "updated_at": "2022-10-31T09:36:05.000000Z"
            },
            {
                "id": 4,
                "product_id": 2,
                "image_path": "storage/products/macbook_so_mot.png",
                "created_at": "2022-10-25T09:36:54.000000Z",
                "updated_at": "2022-10-13T09:36:54.000000Z"
            }
        ]
    ]

        */
    }

    
    // public function getAll()
    // {
    //     return Product::all();
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|min:10',
    //         'content' => 'required|string|min:6',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $article = Product::create($request->all());


    //     return response()->json([
    //         'message' => 'User successfully registered',
    //         'user' => $article
    //     ], 201);
    // }



    // public function show($id)
    // {
    //     try {
    //         $data = Product::find($id);
    //         if ($data) {
    //             return sendSuccessResponse($data);
    //         } else {
    //             return sendErrorResponse([], 'Data Not found!', 404);
    //         }
    //     } catch (QueryException $e) {
    //         return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
    //     }
    // }
    // public function update($data = [], $id)
    // {
    //     try {
    //         $data = Product::find($id)->update($data);
    //         return sendSuccessResponse($data, 'Data Updated Successfully!');
    //     } catch (QueryException $e) {
    //         return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
    //     }
    // }
    // public function delete($id)
    // {
    //     try {
    //         $product =  Product::find($id);
    //         if ($product) {
    //             $product->delete();
    //             return sendSuccessResponse([], 'Data Deleted Successfully!', 200);
    //         }
    //     } catch (QueryException $e) {
    //         return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
    //     }
    // }
    // public function getDataWithPagination()
    // {
    //     Product::latest()->paginate(5);
    // }
}