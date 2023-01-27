<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Http\Requests\ProductFormRequest;
use Helper;

class AdminProductController extends Controller
{
    
    public $module_title = 'Products';
    public $listing_view = 'admin.products.index';
    public $add_edit_view = 'admin.products.add_edit';
    public $show_view = 'admin.products.show';

    public function index(){

        //checking edit permission
        if( ! Product::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['title'] = $this->module_title;
        $data['category'] = Category::getActive();
        $data['add_url'] = route('admin.product.create',['category_id'=>1]);
        $data['listing_fetch_url'] = route('admin.product.fetch');
        return view( $this->listing_view , $data );
    }

    public function fetch(Request $request) {
        
        $query = $this->getModelCollection($request);

        return Datatables::of($query)
        ->addColumn('edit_btn', function($row){
            return $row->getEditBtnHtml();
        })
        // ->addColumn('view_btn', function($row){
        //     return $row->getViewBtnHtml();
        // })
        ->addColumn('status_html', function($row){
            return $row->getStatusHtml();
        })
        ->rawColumns(['edit_btn','view_btn','status_html'])
        ->make(true);
    }

    private function getModelCollection($request, $with=[]){
        $with = array_merge([], $with);
        $query = Product::where('id','<>',0);

        if( ($request->status != null) && ($request->status != '') ){
            $query->where('status',$request->status);
        }

        $query = $query->with($with);

        return $query;
    }

    private function getArrangedData($id=null,$categoryId=null) {
        if($id){
            $data['title'] = 'Update Product';
            $data['product'] = Product::getByEid($id);
            $data['category'] = $data['product']->category;
            $data['action_url'] = route('admin.product.update',['id'=>$data['product']->e_id]);         
        } else{
            $data['title'] = 'Add New Product';
            $data['product'] = null;
            $data['category'] = Category::findOrFail($categoryId);
            $data['action_url'] = route('admin.product.store');         
        }
        return $data;
    }

    public function create($categoryId){

        if( ! Product::canAdd() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData(null,$categoryId) );
    }

    public function store(ProductFormRequest $request){
        try{

            $product = Product::create([
                'category_id' => $request->category_id,
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'price' => $request->price,
                'min_increment' => $request->min_increment,
                'max_increment' => $request->max_increment,
                'end_datetime' => $request->end_datetime,
                'short_desc' => $request->short_desc,
                'terms' => $request->terms,
                'status' => $request->status,
            ]);
            
            if( $product ){
                $attrKeys = array_keys($request->product_attributes);
                $attrData = $request->product_attributes;
                $attrLabel = $request->product_attributes_label;
                foreach( $attrKeys as $attrKey ){
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'key' => $attrKey,
                        'value' => $attrData[$attrKey],
                        'label' => $attrLabel[$attrKey],
                    ]);
                }
                // dd($attrKeys);
            }
            // dd($product);
            
            Helper::successToast('Product has been added successfully');
            return redirect()->route('admin.products');
        } catch(\Exception $e){
            Helper::errorToast($e);
            return back();
        }
    }

    public function edit($id){

        //checking edit permission
        if( ! Product::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData($id) );
    }

    public function update(ProductFormRequest $request,$id){
        try{
            $product = Product::getByEid($id);
            // $role->update($request->input());
            Helper::successToast('Product has been updated successfully');
            return redirect()->route('admin.product');
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

    public function show($id) {
        $data['product'] = Product::getByEid($id);
        $data['title'] = 'Product Details';
        $data['action_url'] = route('admin.product');
        return view($this->show_view, $data);
    }

}
