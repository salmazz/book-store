<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBook;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Response;

class BooksController extends Controller
{
    public function index()
    {
        return response()->json(["books" => BookResource::collection(Book::all())]);
    }

    public function store(StoreBook $request)
    {
        Book::create($request->all());
        return response(['message' => 'created'], 201);
    }

    public function create()
    {
        return view('book_creation');
    }

    public function delete($id){
        Book::destroy($id);
        return \response('',Response::HTTP_NO_CONTENT);
    }
}
