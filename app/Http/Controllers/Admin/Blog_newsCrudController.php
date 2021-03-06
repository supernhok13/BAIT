<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog_news;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Blog_newsRequest as StoreRequest;
use App\Http\Requests\Blog_newsRequest as UpdateRequest;
use Illuminate\Support\Facades\DB;
use App\API\URLCreator;
use App\API\excelSpout;
use Illuminate\Support\Facades\Storage;

class Blog_newsCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Blog_news');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog_news');
        $this->crud->setEntityNameStrings('blog_news', 'blog_news');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

//        $this->crud->setFromDb();
        $this->crud->setListView("backpack::crud.list-bait");
        $this->crud->setEditView("backpack::crud.edit-bait");
        $this->crud->setCreateView("backpack::crud.create-bait");

        // ------ CRUD FIELDS
         $this->crud->addField(
             ['name'  => 'id', // DB column name (will also be the name of the input)
             'label' => 'ID', // the human-readable label for the input
             'type'  => 'text'], 'create');
         $this->crud->addFields([
             ['name'  => 'title', // DB column name (will also be the name of the input)
                 'label' => 'Tiêu đề', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'content', // DB column name (will also be the name of the input)
                 'label' => 'Nội dung', // the human-readable label for the input
                 'type'  => 'ckeditor'],
             ['name'  => 'description', // DB column name (will also be the name of the input)
                 'label' => 'Mô tả', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'main_image', // DB column name (will also be the name of the input)
                 'label' => 'Hình ảnh', // the human-readable label for the input
                 'upload' => true,
                 'type'=>'image',
                 'crop' => false, // set to true to allow cropping, false to disable
                 'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
                 'prefix' => 'book_image/'],
             ['name'  => 'author', // DB column name (will also be the name of the input)
                 'label' => 'Tác giả', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'breaking', // DB column name (will also be the name of the input)
                 'label' => 'Nổi bật', // the human-readable label for the input
                 'type'        => 'radio',
                 'options'     => [ // the key will be stored in the db, the value will be shown as label;
                     0 => "sai",
                     1 => "đúng"]
             ],
         ], 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
         $this->crud->addColumns([
             ['name'  => 'id', // DB column name (will also be the name of the input)
                 'label' => 'ID', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'title', // DB column name (will also be the name of the input)
                 'label' => 'Tiêu đề', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'content', // DB column name (will also be the name of the input)
                 'label' => 'Nội dung', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'description', // DB column name (will also be the name of the input)
                 'label' => 'Mô tả', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'main_image', // DB column name (will also be the name of the input)
                 'label' => 'Hình ảnh', // the human-readable label for the input
                 'type'  => 'image',
                 'prefix'=>'book_image/',
                 'width'=>'100px',
                 'height'=>'150px'],
             ['name'  => 'author', // DB column name (will also be the name of the input)
                 'label' => 'Tác giả', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'breaking', // DB column name (will also be the name of the input)
                 'label' => 'Nổi bật', // the human-readable label for the input
                 'type'  => 'text'
             ],
             ['name'  => 'created_at', // DB column name (will also be the name of the input)
                 'label' => 'Created At', // the human-readable label for the input
                 'type'  => 'text'],
             ['name'  => 'updated_at', // DB column name (will also be the name of the input)
                 'label' => 'Updated At', // the human-readable label for the input
                 'type'  => 'text']
         ]); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function ExportExcelAction()
    {
        excelSpout::exportExcel(['id','title','content','description','author','breaking','url','created_at','updated_at']
            ,"blog-news","blog_news");
    }

    public function ImportExcelAction()
    {
        return excelSpout::importExcelXLSX($_FILES['excelFile']
            ,['id','title','content','description','author','breaking']
            ,'books',function($row){
                Book::where("id",$row[0])->update([
                    "title"=>$row[1],
                    "content"=>$row[2],
                    'description'=>$row[3],
                    "author"=>$row[4],
                    'breaking'=>$row[5],
                    'url_blog'=>URLCreator::htaccess_String("blog_news","url_blog",$row[1],"update")
                ]);
            },function($row){
                Book::create([
                    "id"=>$row[0],
                    "title"=>$row[1],
                    "content"=>$row[2],
                    'description'=>$row[3],
                    "author"=>$row[4],
                    'breaking'=>$row[5],
                ]);

                Book::where("id",$row[0])->update([
                    "url_blog"=>URLCreator::htaccess_String("blog_news","url_blog",$row[1],"create")
                ]);
            });
    }

    public function store(StoreRequest $request)
    {
        DB::statement("ALTER TABLE blog_news AUTO_INCREMENT=1");
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Blog_news::where("title",$request->input("title"))->update([
            "url_blog" => URLCreator::htaccess_String("blog_news","url_blog",$request->input("title"),"create")
        ]);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $image = DB::table("blog_news")->select("main_image")->where("id",$request->input("id"))->get();
        if($image[0]->main_image!=null && $image[0]->main_image!=basename($request->input("main_image")))
            Storage::disk("public")->delete("book_image/".$image[0]->main_image);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Blog_news::where("id",$request->input("id"))->update([
            "url_blog" => URLCreator::htaccess_String("blog_news","url_blog",$request->input("title"),"update")
        ]);
        return $redirect_location;
    }
}
