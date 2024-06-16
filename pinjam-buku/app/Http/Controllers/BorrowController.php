<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BorrowRequest;
use App\Services\BorrowService;

class BorrowController extends Controller
{
    public function borrow(BorrowRequest $request) {
        $service = new BorrowService;
        $borrow = $service->borrow($request->book_id, $request->member_id);
        return response()->json( $borrow, ( isset($borrow->id) ? 201 : 422) );
    }

    public function returnBook(BorrowRequest $request) {
        $service = new BorrowService;
        $return = $service->returnBook($request->book_id, $request->member_id);
        return response()->json( $return, ( isset($return->id) ? 201 : 422) );
    }
}
