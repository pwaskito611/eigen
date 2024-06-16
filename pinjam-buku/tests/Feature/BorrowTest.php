<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\BookService;
use App\Services\MemberService;
use App\Services\BorrowService;
use Datetime;
use App\Models\Borrow;

class BorrowTest extends TestCase
{
    use RefreshDatabase;

    private function createBook() {
        return  (new BookService)->create( (object) [
            'code' => random_int(0,999).'',
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 1
        ]);
    }

    private function createMember() {
        $service = new MemberService;
        return $service->create('lkok');
    }


    public function test_borrow_book() : void 
    {
        $book = $this->createBook();
        $member = $this->createMember();

        $formData = [
            'member_id' => $member->id,
            'book_id' =>  $book->id,
        ];

        $response = $this->postJson('/borrow/borrow', $formData);
      
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'member_id',
            'book_id',
            'borrow_at',
            'return_at'
        ]);

   }

   public function test_member_only_can_borrow_one_book() : void 
   {
    $book = $this->createBook();
    $member = $this->createMember();

    $formData = [
        'member_id' => $member->id,
        'book_id' =>  $book->id,
    ];
    $response = $this->postJson('/borrow/borrow', $formData);

    $book2 = $this->createBook();
    $formData = [
        'member_id' => $member->id,
        'book_id' =>  $book2->id,
    ];
    $response = $this->postJson('/borrow/borrow', $formData);

  
    $response->assertStatus(422);
    $response->assertJsonStructure([]);
   }

   public function test_return_book() : void 
   {
       $book = $this->createBook();
       $member = $this->createMember();
       $borrow = (new BorrowService)->borrow($book->id, $member->id);

       $formData = [
           'member_id' => $member->id,
           'book_id' =>  $book->id,
       ];

       $response = $this->postJson('/borrow/return', $formData);
     
        $response->assertStatus(201);
        $response->assertJsonStructure([
           'id',
           'member_id',
           'book_id',
           'borrow_at',
           'return_at'
        ]);
      
        $this->assertNotNull($response['return_at']);
  }

  public function test_penaltilized_member_cant_borrow_book() : void 
  {
        $service = new BorrowService;
        $member = $this->createMember();
        $book = $this->createBook();

        $borrow = new Borrow;
        $borrow->book_id =  $book->id;
        $borrow->member_id = $member->id;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = date('Y-m-d H:i:s');
        $borrow->save();

        $formData = [
            'member_id' => $member->id,
            'book_id' =>  $book->id,
        ];
        $response = $this->postJson('/borrow/borrow', $formData);
    
      
        $response->assertStatus(422);
        $response->assertJsonStructure([]);
  }

}
