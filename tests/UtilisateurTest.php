<?php

use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testRetrieveUserDetails()
    {
        $user = createUser('John Doe', 'john.doe@example.com', 'password123');
        $details = getUserDetails($user);
        $this->assertEquals(['name' => 'John Doe', 'email' => 'john.doe@example.com'], $details);
    }

    public function testValidateUserRules()
    {
        $user = createUser('John Doe', 'john.doe@example.com', 'password123');
        $this->assertTrue(validateUser($user));
        
        $user = createUser('', 'john.doe@example.com', 'password123');
        $this->assertFalse(validateUser($user));
        
        $user = createUser('John Doe', '', 'password123');
        $this->assertFalse(validateUser($user));
        
        $user = createUser('John Doe', 'john.doe@example.com', '');
        $this->assertFalse(validateUser($user));
    }
}