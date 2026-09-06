# Contributing

Thank you for considering contributing to the Laravel SweetAlert package!

## Code of Conduct

This project follows a code of conduct to ensure a welcoming community.

## How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Write your code with tests
4. Ensure all tests pass (`composer test`)
5. Ensure code style is correct (`composer format`)
6. Commit with a descriptive message
7. Push to your branch
8. Open a Pull Request

## Development Setup

```bash
# Clone the repository
git clone https://github.com/realrashid/sweet-alert.git

# Install dependencies
composer install

# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Format code
composer format

# Static analysis
composer analyse
```

## Coding Standards

- Follow PSR-12 coding standards
- Use Laravel Pint for automated code style enforcement
- Write Pest PHP tests for all new features
- Use PHP 8.3+ features (enums, readonly properties, etc.)
- Follow the fluent builder pattern for API methods
- All builder methods must return `$this` for chaining

## Pull Request Process

1. Update the CHANGELOG.md with your changes
2. Add tests for any new functionality
3. Ensure all existing tests still pass
4. Update documentation if applicable

## Security Vulnerabilities

If you discover a security vulnerability, please email realrashid05@gmail.com instead of opening a public issue.

Made with ❤️ from Pakistan
