<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\BookService;
use App\Models\Borrow;
use Datetime;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $formData = [
            'code' => 'md-22',
            'title' => 'meditation',
            'author' => 'Marcus Aurelius',
            'stock' => 22
        ];

        $response = $this->postJson('/book/create', $formData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'code',
            'title',
            'author',
            'stock'
        ]);
    }

    public function test_available_book() : void 
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

        $response = $this->get('/book/available');
        $response->assertStatus(200);
        $return =  $response->json();
        
        $this->assertTrue($return[0]['stock'] == 2);
        $this->assertTrue($return[0]['code'] == $book1->code);
        $this->assertTrue($return[1]['stock'] == 3);
        $this->assertTrue($return[1]['code'] == $book3->code);
    }

}
