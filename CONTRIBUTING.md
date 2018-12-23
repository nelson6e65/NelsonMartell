# Contributing guidelines for PHP: Nelson Martell Library

## Global requirements
- Git
- PHP 5.6+
- [Composer](https://getcomposer.org/)
- Node.js
- [Yarn](https://yarnpkg.com)
- [phpDocumentor](https://www.phpdoc.org/)

### Initialization

- Clone the repository:
```bash
git clone git@github.com:nelson6e65/php_nml.git
```

- Install PHP dependencies:
```bash
composer install
```

- Install Node dependencies:
```bash
yarn
```


## Scripts helpers

> **Note:** This scripts should be run from root php_nml directory (where `composer.json` is).


### Development scripts

- **`composer test-code`**: Runs unit-testing tests with PHPUnit. You can pass more phpunit args with `-- <arg>`. For example: `composer test-code -- --verbose`.

- **`composer analize-code`**: Runs coding standards checks (PHP: Code Sniffer).

- **`composer autofix-code`**: Runs coding standard auto-fixes (PHP: Code Sniffer).

- **`composer check-all`**: Runs coding standard analisis (PHP: Code Sniffer) + tests (PHPUnit).

- **`composer build`**: Run this sub-scripts:
  1. **`composer build-code-coverage`**: Runs tests and build code coverage reports (XML and HTML formats) in `output/code-coverage/` directory.
    - For XML format only (`output/code-coverage/clover.xml`): **`composer build-code-coverage-clover`** or **`composer build-code-coverage-xml`** (alias).
    - For HTML format only: **`composer build-code-coverage-html`**.

- **`composer build-api`**: Generates API documentation in `output/api/` directory using [ApiGen](https://github.com/ApiGen/ApiGen).


- **`phpdoc`**: Generates the API documentation files (`*.md`) compatible with VuePress.

- **`yarn docs:dev`**: Generates VuePress documentation in development mode to check changes while writing.

- **`yarn docs:build`**: Build the VuePress documentation to be published.




### Deployment scripts
[DEPRECATED]
- `.travis/deploy-documentation`: Generates documentation and publish it in `gh-pages` brach. **Note:** _This script is used by [Travis CI](travis-ci.org) to publish documentation and **should not be run in local development**_.
