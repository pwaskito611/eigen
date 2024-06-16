<?php

namespace App\Services;

use App\Models\Borrow;
use App\Models\Book;
use DateTime;

class BorrowService {

    public function borrow($book_id, $member_id) {
        if(!$this->isBookStillAvailable($book_id)) {
            return;
        }

        if(!$this->isMemberDidntBorrowBook($member_id)) {
            return;
        }

        if($this->isMemberPenaltilized($member_id)) {
            return;
        }

        $borrow = new borrow;
        $borrow->member_id = $member_id;
        $borrow->book_id = $book_id;
        $borrow->borrow_at = date('Y-m-d H:i:s');
        $borrow->return_at = null;
        $borrow->save();
        return $borrow;
    }

    public function returnBook($book_id, $member_id) {
        $borrow = Borrow::where('book_id', $book_id)
            ->where('member_id', $member_id)
            ->where('return_at', null)
            ->first();

        if($borrow != null) {
            $borrow->return_at = date('Y-m-d H:i:s');
            $borrow->save();
            return $borrow;
        }
    }

    
    public function isBookStillAvailable($book_id) {
        $borrow = Borrow::where('book_id', $book_id)
            ->where('return_at', null)
            ->get();

        $totalStock = Book::find($book_id);
        return ($totalStock->stock - sizeof($borrow)) > 0;
    }

    public function isMemberDidntBorrowBook($member_id) {
        $borrowed = Borrow::where('member_id', $member_id)
            ->where('return_at', null)
            ->first();

        return $borrowed == null;
        
    }

    public function isMemberPenaltilized($member_id) {
        $check = Borrow::where('member_id', $member_id)
        ->whereNotNull('return_at')
        ->orderBy('id', 'desc')
        ->first();

        if($check == null) {
            return false;
        }

        $startDate = new Datetime($check->borrow_at);
        $endDate =  new Datetime($check->return_at);
        $interval = $startDate->diff($endDate);

        if($interval->days <= 7  ) {
            return false;
        }

        $interval = $endDate->diff( new Datetime(date('Y-m-d H:i:s')) );

        if($interval->days > 3) {
            return false;
        }

        return true;
    }

   
}