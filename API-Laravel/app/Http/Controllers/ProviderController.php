<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Import;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;


class ProviderController extends Controller
{
    //
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string', 
            'address' => 'required|string', 
            'phone'   => 'required|min:9|numeric', 
            'email'   => 'required|email|string', // nếu để email thì nó cũng tự động unique:provider cho mình luôn, mỗi ncc chỉ có 1 email duy nhất 
            'tax_id_number' => 'required|string', 
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $provider = Provider::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Add Provider successfully ',
            'provider' => $provider
        ], 201);
    }

    public function edit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string', 
            'address' => 'required|string', 
            'phone'   => 'required|min:9|numeric', 
            'email'   => 'required|email|string', 
            'tax_id_number' => 'required|string', 
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Provider::where("id",$id)->update(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Edit Provider successfully ',
        ], 201);
    }

    public function delete($id)
    {
        try {
            $provider =  Provider::find($id);
            if ($provider) {
                Import::where("provider_id",$id)->update(['provider_id'=>null]);
                $provider->delete();
                return response()->json([
                    'message' => 'Delete Provider successfully !',
                ], 201);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Delete Provider false !',
            ], 400);
        }
    }

    public function allProviders(Request $request) {

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

        $search = $request->search;
        $providers = Provider::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%')
            ->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%')
            ->orWhere('phone','LIKE', '%'.$search.'%')
            ->orWhere('tax_id_number','LIKE', '%'.$search.'%');
        })->paginate(10);

        $providers2 = Provider::orderBy($col1,$orderb1)->orderBy($col2,$orderb2)->where(function($query) use($search) {
            $query->where('name','LIKE', '%'.$search.'%')
            ->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%')
            ->orWhere('phone','LIKE', '%'.$search.'%')
            ->orWhere('tax_id_number','LIKE', '%'.$search.'%');
        })->get();

        $n = count($providers2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all providers successfully !',
            'provider' => $providers
        ], 201);
    }
}
