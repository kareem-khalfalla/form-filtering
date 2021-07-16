<?php

use App\Database\Database;
use App\Database\DatabaseManager;
use App\Migration\MigrationManager;
use App\Migration\Model\Migration;
use App\Models\Book;

class DatabaseTest extends TestCase
{
    use BookStore;

    private Database $db;

    protected function setUp(): void
    {
        parent::setUp();

        // create new pdo instance
        $this->db = DatabaseManager::make();
        (new MigrationManager(new Migration($this->db)))->up();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        (new MigrationManager(new Migration($this->db)))->down();
    }

    /**
     * @test
     */
    public function executes_simple_query()
    {
        $result = $this->db->execute("SELECT MAX(1, 10)");

        $this->assertEquals(10, $result);
    }

    /**
     * @test
     */
    public function inserts_new_record()
    {
        $result = $this->addBook();

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function update_record()
    {
        $this->addBook(['isbn' => 123]);

        $update = $this->db->update(
            'books',
            $this->lastInsertId,
            [
                'isbn' => 456
            ],
        );

        $this->assertTrue($update);

        $book = $this->db->fetchObject(
            "SELECT * FROM `books` WHERE `id` = ?",
            $this->lastInsertId
        );

        $this->assertEquals(456, $book->isbn);
    }

    /**
     * @test
     */
    public function returns_std_class_instance_from_fetch_object_fn()
    {
        $this->addBook(['title' => 'Very cool title']);

        $book = $this->db->fetchObject(
            "SELECT * FROM `books` WHERE `id` = ?",
            $this->lastInsertId
        );
        $this->assertEquals('Very cool title', $book->title);
        $this->assertInstanceOf('stdClass', $book);
    }

    /**
     * @test
     */
    public function returns_book_instance_from_fetch_object_fn()
    {
        $this->addBook(['title' => 'Amazing book title']);

        $book = $this->db->fetchObject(
            "SELECT * FROM `books` WHERE `id` = ?",
            $this->lastInsertId,
            Book::class
        );

        $this->assertEquals('Amazing book title', $book->title);

        $this->assertInstanceOf(Book::class, $book);
    }

    /**
     * @test
     */
    public function removes_record()
    {
        $this->addBook(['title' => 'Amazing book title will be removed :(']);
        $this->addBook();

        $book = $this->db->fetchObject(
            "SELECT * FROM `books` WHERE `id` = ?",
            $this->lastInsertId,
        );

        $this->assertNotEmpty($book);

        $this->db->delete('books', $book->id);

        $book2 = $this->db->fetchObject(
            "SELECT * FROM `books` WHERE `id` = ?",
            $this->lastInsertId,
        );

        $this->assertFalse($book2);
    }

    /**
     * @test
     */
    public function count_returns_correct_number_of_records()
    {
        $this->addBook();
        $this->addBook();
        $this->addBook();


        $this->assertEquals(3, $this->db->count('books'));
        $this->assertEquals(2, $this->db->count('books', 'WHERE `id` < 3'));
    }

    /**
     * @test
     */
    public function returns_the_correct_number_of_records_using_fetch_objects_method()
    {
        $this->addBook(['title' => 'Breaking Bad']);
        $this->addBook(['title' => 'Game of Thrones']);

        $books = $this->db->fetchObjects("SELECT * FROM `books`");

        $this->assertCount(2, $books);
    }

    /**
     * @test
     */
    public function returns_array_of_book_objects()
    {
        $this->addBook(['title' => 'Breaking Bad']);
        $this->addBook(['title' => 'Game of Thrones']);

        $books = $this->db->fetchObjects("SELECT * FROM `books`", className: Book::class);

        $this->assertInstanceOf(Book::class, $books[0]);
    }
}
