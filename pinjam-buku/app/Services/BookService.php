<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrow;

class BookService {

    public function create($request) {
        $book = new Book;
        $book->code = $request->code;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->stock = $request->stock;
        $book->save();
        return $book;
    }

    public function checkAvailableBook() {
        $books = Book::get();
        $borrow = Borrow::where('return_at', null)->get();
        $return = [];
        $i =0;

        foreach ($books as $book) {
            $totalBorrowed = 0;

            foreach($borrow as $item) {
                if($item->book_id == $book->id) {
                    $totalBorrowed++;
                }
            }

            $books[$i]->stock -= $totalBorrowed;
            
            if($books[$i]->stock > 0) {
                array_push($return, (object)[
                    'code' => $books[$i]->code,
                    'author' =>  $books[$i]->author,
                    'title' =>  $books[$i]->title,
                    'stock' =>  $books[$i]->stock
                ]);
            }

            $i++;
        }

        return $return;
    }

}