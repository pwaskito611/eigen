<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Services\BookService;

class BookController extends Controller
{

    public function create(BookRequest $request) {
        $book = ( new BookService )->create($request);
        return response()->json( $book, 201 );
    }

    public function available() {
        $books = ( new BookService )->checkAvailableBook();
        return response()->json( $books, 200 );
    }

}
