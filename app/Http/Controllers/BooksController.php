<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBook;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function store(StoreBook $request){
        Book::create($request->all());
        return response(['message' => 'created'],201);
    }

    public function create(){
        return view('book_creation');
    }
}
