<?php
declare(strict_types=1);

class Bot
{
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();

        # set error handler
        register_shutdown_function([$this, 'FatalHandler']);
        set_error_handler([$this, 'ErrorHandler'], E_ALL);
        set_exception_handler([$this, 'ExceptionHandler']);
    }

    # set error handler
    public function ErrorHandler(int $num, string $str, string $file, int $line, $context = null): void
    {
        $this->ExceptionHandler(
            new ErrorException($str, 0, $num, $file, $line)
        );
    }

    # set exception handler
    public function ExceptionHandler(Throwable $error): void
    {
        # set variables
        $type = get_class($error);
        $message = $error->getMessage();
        $file = $error->getFile();
        $line = $error->getLine();

        # notify
        $this->Notify([
            'file' => $file,
            'type' => $type,
            'message' => $message,
            'line' => $line,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'date' => date('d-m-Y'),
            'time' => date('G:i:s')
        ]);
    }

    # set fatal handler
    public function FatalHandler(): void
    {
        $error = error_get_last();

        # throw new exception
        if (isset($error['type']) && $error['type'] === E_ERROR) {
            $this->ErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }

        # redirect
        if (!headers_sent()) {
            # header('location: /error/', true, 303);
        }
    }

    # notify on error
    public function Notify(array $data): void
    {
        print_r($data);
    }
}
