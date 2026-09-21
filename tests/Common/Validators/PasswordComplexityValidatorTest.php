<?php

declare(strict_types=1);

class PasswordComplexityValidatorTest extends TestBase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_MINIMUM_LENGTH, 8);
        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_MINIMUM_NUMBERS, 1);
        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_UPPER_AND_LOWER, true);
        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_MINIMUM_SPECIALCHAR, 1);
    }

    /**
     * @dataProvider passwordProvider
     */
    public function testPasswordComplexity(string $password, int $minNumbers, int $minSpecialChars, bool $expectedValid)
    {
        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_MINIMUM_NUMBERS, $minNumbers);
        $this->fakeConfig->SetKey(ConfigKeys::PASSWORD_MINIMUM_SPECIALCHAR, $minSpecialChars);

        $validator = new PasswordComplexityValidator($password);
        $validator->Validate();

        $this->assertSame($expectedValid, $validator->IsValid(), "Failed for password: $password");
    }

    /**
     * @return array<string, array{string, int, int, bool}>
     */
    public static function passwordProvider(): array
    {
        return [
            'fails when special character requirement not met' => ['Password1', 1, 1, false],
            'passes when special character requirement is met' => ['Password1!', 1, 1, true],
            'passes when special character requirement is disabled' => ['Password1', 1, 0, true],
            'fails when number requirement not met by special character alone' => ['Password!', 1, 1, false],
            'passes when numbers and special characters are separated by other characters' => ['Pass1hello2#word%', 2, 2, true],
        ];
    }

    public function testFailsWhenSpecialCharacterRequirementNotMetIncludesAllThresholdsInMessage()
    {
        $validator = new PasswordComplexityValidator('Password1');
        $validator->Validate();

        $this->assertFalse($validator->IsValid());
        $this->assertEquals(['PasswordErrorRequirements8,1,1'], $validator->Messages());
    }
}
