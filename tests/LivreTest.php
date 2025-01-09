<?php

use PHPUnit\Framework\TestCase;

class LivresTest extends TestCase
{
    public function testRetrieveBookDetails()
    {
        $book = createBook('1984', 'George Orwell', 'Dystopian');
        $details = getBookDetails($book);
        $this->assertEquals(['title' => '1984', 'author' => 'George Orwell', 'genre' => 'Dystopian'], $details);
    }

    public function testValidateBookRules()
    {
        $book = createBook('1984', 'George Orwell', 'Dystopian');
        $this->assertTrue(validateBook($book));
        
        $book = createBook('', 'George Orwell', 'Dystopian');
        $this->assertFalse(validateBook($book));
        
        $book = createBook('1984', '', 'Dystopian');
        $this->assertFalse(validateBook($book));
        
        $book = createBook('1984', 'George Orwell', '');
        $this->assertFalse(validateBook($book));
    }
}