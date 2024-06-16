<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Borrow;
use Datetime;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_book(): void
    {
        $book = (new BookService)->create( (object) [
            'code' => 'md-22',
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 22
        ]);

        $this->assertTrue($book->id > 0);
    }

    public function test_check_availabble_book() : void 
    {
        $book1 = (new BookService)->create( (object) [
            'code' =>  rand(1,999),
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 3
        ]);

        $book2 = (new BookService)->create( (object) [
            'code' =>  rand(1,999),
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 3
        ]);

        $book3 = (new BookService)->create( (object) [
            'code' =>  rand(1,999),
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 3
        ]);

        $borrow = new Borrow;
        $borrow->book_id =  $book1->id;
        $borrow->member_id = 1;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = null;
        $borrow->save();

        $borrow = new Borrow;
        $borrow->book_id =  $book2->id;
        $borrow->member_id = 2;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = null;
        $borrow->save();

        $borrow = new Borrow;
        $borrow->book_id =  $book2->id;
        $borrow->member_id = 3;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = null;
        $borrow->save();

        $borrow = new Borrow;
        $borrow->book_id =  $book2->id;
        $borrow->member_id = 4;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = null;
        $borrow->save();

        $check = (new BookService)->checkAvailableBook();
        
        $this->assertTrue($check[0]->stock == 2);
        $this->assertTrue($check[0]->code == $book1->code);
        $this->assertTrue($check[1]->stock == 3);
        $this->assertTrue($check[1]->code == $book3->code);
    }
}
