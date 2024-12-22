<?php

use Symfony\Component\Process\Process;

beforeEach(function () {
    // Define reusable variables for the test
    $this->controllerName = 'TestController';
    $this->controllerPath = __DIR__ . '/../../../src/Controllers/' . $this->controllerName . '.php';

    // Clean up before the test
    if (file_exists($this->controllerPath)) {
        unlink($this->controllerPath);
    }
});

afterEach(function () {
    // Clean up after the test
    if (file_exists($this->controllerPath)) {
        unlink($this->controllerPath);
    }
});

it('can create a new controller', function () {
    // Act: Run the skip make:controller command
    $process = new Process(['php', './skip', 'make:controller', $this->controllerName]);
    $process->run();

    // Assert: Verify the command was successful
    expect($process->isSuccessful())->toBeTrue();
    expect(file_exists($this->controllerPath))->toBeTrue();

    // Assert: Verify the file content
    $expectedContent = <<<PHP
    <?php

    namespace App\Controllers;

    class {$this->controllerName}
    {
        // Controller logic
    }
    PHP;

    $actualContent = file_get_contents($this->controllerPath);
    expect(trim($actualContent))->toBe(trim($expectedContent));
});

it('fails if the controller already exists', function () {
    // Arrange: Create the controller file manually
    file_put_contents($this->controllerPath, '<?php // Existing controller');

    // Act: Run the skip make:controller command
    $process = new Process(['php', './skip', 'make:controller', $this->controllerName]);
    $process->run();

    // Assert: Verify the command failed
    expect($process->isSuccessful())->toBeFalse();

    // Assert: Verify the output contains an error message
    expect($process->getOutput())->toContain('Controller already exists!');
});