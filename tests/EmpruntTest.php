<?php
use PHPUnit\Framework\TestCase;

function createLoan($amount, $duration) {
    return ['amount' => $amount, 'duration' => $duration];
}

function getLoanDetails($loan) {
    return $loan;
}

function validateLoan($loan) {
    return $loan['amount'] > 0 && $loan['duration'] > 0;
}

class EmpruntTest extends TestCase
{
    public function testCreateLoan()
    {
        $loan = createLoan(1000, 12);
        $this->assertEquals(1000, $loan['amount']);
        $this->assertEquals(12, $loan['duration']);
    }

    public function testRetrieveLoanDetails()
    {
        $loan = createLoan(2000, 24);
        $details = getLoanDetails($loan);
        $this->assertEquals(['amount' => 2000, 'duration' => 24], $details);
    }

    public function testValidateLoanRules()
    {
        $loan = createLoan(500, 6);
        $this->assertTrue(validateLoan($loan));
        
        $loan = createLoan(0, 6);
        $this->assertFalse(validateLoan($loan));
        
        $loan = createLoan(500, 0);
        $this->assertFalse(validateLoan($loan));
    }
}
