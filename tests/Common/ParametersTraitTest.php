<?php

declare(strict_types=1);

use Omniship\Common\Exception\InvalidRequestException;
use Omniship\Common\ParametersTrait;

// Test class that uses the trait
class ParametersTraitStub
{
    use ParametersTrait;

    /** @return array<string, mixed> */
    public function getDefaultParameters(): array
    {
        return [
            'username' => '',
            'password' => '',
            'testMode' => false,
        ];
    }

    public function getUsername(): ?string
    {
        return $this->getParameter('username');
    }

    public function setUsername(string $value): static
    {
        return $this->setParameter('username', $value);
    }
}

it('initializes with default parameters', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize();

    expect($stub->getParameters())->toHaveKey('username', '');
    expect($stub->getParameters())->toHaveKey('password', '');
    expect($stub->getParameters())->toHaveKey('testMode', false);
});

it('merges provided parameters over defaults', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize(['username' => 'test-user', 'password' => 'secret']);

    expect($stub->getUsername())->toBe('test-user');
    expect($stub->getParameters()['password'])->toBe('secret');
});

it('uses setter methods when available', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize(['username' => 'via-setter']);

    expect($stub->getUsername())->toBe('via-setter');
});

it('validates required parameters', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize();

    expect(fn () => $stub->validate('username'))->toThrow(
        InvalidRequestException::class,
        'The username parameter is required',
    );
});

it('passes validation when parameters are set', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize(['username' => 'test', 'password' => 'test']);

    // Should not throw
    $stub->validate('username', 'password');

    expect(true)->toBeTrue();
});

it('validates empty arrays as missing', function () {
    $stub = new ParametersTraitStub();
    $stub->initialize(['packages' => []]);

    expect(fn () => $stub->validate('packages'))->toThrow(InvalidRequestException::class);
});
