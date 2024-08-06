<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ResponseFormatter;

    public function index()
    {
        try {
            $data = Product::all();

            $response = self::arrayResponse(200, 'success', $data);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }

    public function show($id)
    {
        try {
            $data = Product::find($id);
            if (!$data) {
                throw new \Exception("Data not found", 404);
            }

            $response = self::arrayResponse(200, 'success', $data);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|string',
                'description' => 'string|nullable',
                'price' => 'required|numeric'
            ];

            $messages = [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'description.string' => 'Description must be a string',
                'price.required' => 'Price is required',
                'price.numeric' => 'Price must be a number'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 400);
            }

            $products = new Product();
            $products->name = $request->name;
            $products->description = $request->description;
            $products->price = $request->price;

            if (!$products->save()) {
                throw new \Exception("Failed to create products", 500);
            }

            DB::commit();
            $response = self::arrayResponse(200, 'success', null);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            DB::rollBack();
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $rules = [
                'name' => 'required|string',
                'description' => 'string|nullable',
                'price' => 'required|numeric'
            ];

            $messages = [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'description.string' => 'Description must be a string',
                'price.required' => 'Price is required',
                'price.numeric' => 'Price must be a number'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 400);
            }
            $products = Product::find($id);
            if ($products == null) {
                throw new \Exception("Failed to update product not found", 404);
            }
            $products->name = $request->name;
            $products->description = $request->description;
            $products->price = $request->price;

            if (!$products->save()) {
                throw new \Exception("Failed to update products", 500);
            }
            DB::commit();

            $response = self::arrayResponse(200, 'success', null);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            DB::rollBack();
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $products = Product::find($id);
            if ($products == null) {
                throw new \Exception("Failed to delete product not found", 404);
            }

            $products->delete();

            DB::commit();

            $response = self::arrayResponse(200, 'success', null);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            DB::rollBack();
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }
}
