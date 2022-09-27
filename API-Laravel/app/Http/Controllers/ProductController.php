<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:customer_api', ['except' => ['getAllProduct', 'getAll','addp','verycode','upfile','loginGoogle','getfile',
        'testGet','testPost','realTime']]);
    }

    public function getAllProduct()
    {
        return Product::all();
    }

    
    public function getAll()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:10',
            'content' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $article = Product::create($request->all());


        return response()->json([
            'message' => 'User successfully registered',
            'user' => $article
        ], 201);
    }

    public function addp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:10',
            'content' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $article = Product::create($request->all());


        return response()->json([
            'message' => 'User successfully registered',
            'user' => $article
        ], 201);
    }

    public function show($id)
    {
        try {
            $data = Product::find($id);
            if ($data) {
                return sendSuccessResponse($data);
            } else {
                return sendErrorResponse([], 'Data Not found!', 404);
            }
        } catch (QueryException $e) {
            return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
        }
    }
    public function update($data = [], $id)
    {
        try {
            $data = Product::find($id)->update($data);
            return sendSuccessResponse($data, 'Data Updated Successfully!');
        } catch (QueryException $e) {
            return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
        }
    }
    public function delete($id)
    {
        try {
            $product =  Product::find($id);
            if ($product) {
                $product->delete();
                return sendSuccessResponse([], 'Data Deleted Successfully!', 200);
            }
        } catch (QueryException $e) {
            return sendErrorResponse("Something Went Wrong!", $e->getMessage(), 500);
        }
    }
    public function getDataWithPagination()
    {
        Product::latest()->paginate(5);
    }
}