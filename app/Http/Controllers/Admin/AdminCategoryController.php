<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Category;
use App\Http\Requests\CategoryFormRequest;
use Helper;

class AdminCategoryController extends Controller
{

    public $module_title = 'Categories';
    public $listing_view = 'admin.category.index';
    public $add_edit_view = 'admin.category.add_edit';
    public $show_view = 'admin.category.show';

    public function index(){

        //checking edit permission
        if( ! Category::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['title'] = $this->module_title;
        // $data['roles'] = Role::getPortalRoles();
        $data['add_url'] = route('admin.category.create');
        $data['listing_fetch_url'] = route('admin.category.fetch');
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
        $query = Category::where('id','<>',0);

        if( ($request->status != null) && ($request->status != '') ){
            $query->where('status',$request->status);
        }

        $query = $query->with($with);

        return $query;
    }

    private function getArrangedData($id=null) {
        // $data['roles'] = Role::getPortalRoles();
        if($id){
            $data['title'] = 'Update Category';
            $data['user'] = Category::getByEid($id);
            $data['action_url'] = route('admin.category.update',['id'=>$data['user']->e_id]);         
        } else{
            $data['title'] = 'Add New Category';
            $data['user'] = null;
            $data['action_url'] = route('admin.category.store');         
        }
        return $data;
    }

    public function create(){

        if( ! Category::canAdd() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData() );
    }

    public function store(CategoryFormRequest $request){
        try{
            $user = Category::create($request->input());

            if( $request->hasFile('image') ){
                $path = $request->file('image')->store('category', 'public');
                $storage_path = $path;
                // $data['image'] = $storage_path; 
                $user->update(['image'=>$storage_path]);
            }

            Helper::successToast('Category has been added successfully');
            return redirect()->route('admin.category');
        } catch(\Exception $e){
            Helper::errorToast($e);
            return back();
        }
    }

    public function edit($id){

        //checking edit permission
        if( ! Category::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        return view( $this->add_edit_view , $this->getArrangedData($id) );
    }

    public function update(CategoryFormRequest $request,$id){
        try{
            $role = Category::getByEid($id);
            $data = $request->input();

            if( $request->hasFile('image') ){
                $path = $request->file('image')->store('category', 'public');
                $storage_path = $path;
                $data['image'] = $storage_path; 
            }

            $role->update($data);

            Helper::successToast('Category has been updated successfully');
            return redirect()->route('admin.category');
        } catch(\Exception $e){
            Helper::errorToast();
            return back();
        }
    }

    public function show($id) {
        $data['user'] = Category::getByEid($id);
        $data['title'] = 'Category Details';
        $data['action_url'] = route('admin.category');
        return view($this->show_view, $data);
    }

}
