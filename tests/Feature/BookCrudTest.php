<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
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
    private $book;
    private $author;

    /**
     * @var \Illuminate\Testing\TestResponse
     */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->book = Book::factory()->create();
        $this->author = Author::factory()->create();

    }


    public function testStatus201WithMessageCreatedWhenCreateABookWhenAuthenticated()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->post("/books", ['title' => $this->book->title, 'description' => $this->book->description,'author_id' => $this->book->author_id, 'ISBN' => $this->book->ISBN,
            ]);
        $response->assertCreated();
        $response->assertJson(["message" => "created"]);
    }

    public function testRedirectToLoginIfNotAuthenticated()
    {
        $response = $this->post('/books', $this->data());
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    private function data($data = [])
    {
        $default = [
            "title" => "Gone with the Wind",
            "description" => "Bestseller of New York Times",
            "author_id" => $this->author->id,
            "ISBN" => "12b-422-24ff"
        ];
        return array_merge($default, $data);
    }

    public function testAssertValidatedCookieExistsAfterVisitingBooksRoute()
    {
        $response = $this->actingAs($this->user)->post('books',$this->data());
        $response->assertCookie('validated', 'yes');
    }

    public function testLibrarianCanSeeBookCreationForm(){
        $user = $this->user;
        $user->role = "Librarian";
        $res = $this->actingAs($user)->get('/books/create');
        $res->assertOk();
        $res->assertViewIs('book_creation');
    }

    public function testNonLibrarianCannotSeeBookCreationForm(){
        $user = $this->user;
        $user->role = "Non-Librarian";
        $res = $this->actingAs($user)->get('/books/create');
        $res->assertForbidden();
    }

    public function testBookCanBeDeleted()
    {
        $this->withoutExceptionHandling();
        $res = $this->actingAs($this->user)->delete(route('books.delete',['id' => $this->book->id]));
        $this->assertDatabaseMissing('books',['title' => $this->book->title]);
    }
}
