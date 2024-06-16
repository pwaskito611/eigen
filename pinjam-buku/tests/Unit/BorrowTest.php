<?php

namespace Tests\Unit;

use Tests\TestCase;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\BorrowService;
use App\Services\BookService;
use App\Services\MemberService;
use App\Models\Borrow;

class BorrowTest extends TestCase
{
    use RefreshDatabase;

    private function createBook() 
    {
        return $book = (new BookService)->create( (object) [
            'code' => 'md-22',
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 1
        ]);
    }

    private function createMember() 
    {
        $service = new MemberService;
        return $service->create('lkok');
    }


    public function test_check_if_book_still_available_in_availbable_book(): void
    {
        $book = $this->createBook();
        $this->assertTrue((new BorrowService)->isBookStillAvailable($book->id));
    }

    public function test_create_borrow_and_fail_if_book_is_not_available() : void 
    {
        $book = $this->createBook();
        $member = $this->createMember();
        $borrow = (new BorrowService)->borrow($book->id, $member->id);
        $this->assertTrue(isset($borrow->id) );

        $anotherMember = $this->createMember();
        $borrow = (new BorrowService)->borrow($book->id, $anotherMember->id);
        $this->assertTrue(!isset($borrow->id));
    }

    public function test_check_isMemberDidntBorrowBook() : void 
    {
        $book = $this->createBook();
        $member1 = $this->createMember();
        $member2 = $this->createMember();
        $borrow = (new BorrowService)->borrow($book->id, $member1->id);
        $this->assertTrue( (new BorrowService)->isMemberDidntBorrowBook($member2->id) );
        $this->assertFalse( (new BorrowService)->isMemberDidntBorrowBook($member1->id) );
    }

    public function test_is_member_is_penaltilized() : void 
    {
        $service = new BorrowService;
        $member1 = $this->createMember();
        $book1 = $this->createBook();
        $this->assertFalse( $service->isMemberPenaltilized($member1->id) );

        $borrow = new Borrow;
        $borrow->book_id =  $book1->id;
        $borrow->member_id = $member1->id;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = new Datetime('2024-01-05 10:10:10');
        $borrow->save();
        $this->assertFalse( $service->isMemberPenaltilized($member1->id) );

        $borrow = new Borrow;
        $borrow->book_id =  $book1->id;
        $borrow->member_id = $member1->id;
        $borrow->borrow_at = new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = new Datetime('2024-01-10 10:10:10');
        $borrow->save();
        $this->assertFalse( $service->isMemberPenaltilized($member1->id) );

        $borrow = new Borrow;
        $borrow->book_id =  $book1->id;
        $borrow->member_id = $member1->id;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = date('Y-m-d H:i:s');
        $borrow->save();
        $this->assertTrue( $service->isMemberPenaltilized($member1->id) );

    }

    public function test_return_borrowed_book() : void 
    {
        $book = $this->createBook();
        $member = $this->createMember();
        $borrow = (new BorrowService)->borrow($book->id, $member->id);
        $return = (new BorrowService)->returnBook($book->id, $member->id);
    
        $this->assertTrue(isset($return->id) );
        $this->assertNotNull($return->return_at);
    }
    
}
