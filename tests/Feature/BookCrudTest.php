<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $user;

    /**
     * @var \Illuminate\Testing\TestResponse
     */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }


    public function testStatus201WithMessageCreatedWhenCreateABookWhenAuthenticated()
    {
        $response = $this->actingAs($this->user)->post("/books", $this->data());
        $response->assertCreated();
        $response->assertJson(["message" => "created"]);
    }

    public function testRedirectToLoginIfNotAuthenticated()
    {
        $response = $this->post('/books', $this->data());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testCountOfDatabaseInBooksTableIs1()
    {
        $this->actingAs($this->user)->post("/books", $this->data());

        $this->assertDatabaseCount("books", 1);
    }

    private function data($data = [])
    {
        $author = Author::factory()->create();
        $default = [
            "title" => "Gone with the Wind",
            "description" => "Bestseller of New York Times",
            "author_id" => $author->id,
            "ISBN" => "12b-422-24ff"
        ];
        return array_merge($default, $data);
    }

    public function testAssertValidatedCookieExistsAfterVisitingBooksRoute()
    {
        $response = $this->actingAs($this->user)->post('books',$this->data());
        $response->assertCookie('validated', 'yes');
    }


}
