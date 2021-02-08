# Contributing

## Guidelines

1. Pluf DB utilizes PSR-1, PSR-2, PSR-4, and PSR-7.
2. Pluf DB is meant to be lean and fast with very few dependencies. This means that not every feature request will be accepted.
3. All pull requests must include unit tests to ensure the change works as expected and to prevent regressions.
4. All pull requests must include relevant documentation or amend the existing documentation if necessary.

## Review and Approval

1. All code must be submitted through pull requests on GitHub
2. Any of the project managers may Merge your pull request, but it must not be the same person who initiated the pull request.

## Running the tests

In order to contribute, you'll need to checkout the source from GitHub and install Pluf DB dependencies using Composer:

    git clone https://github.com/pluf/db.git
    cd Pluf DB && curl -s http://getcomposer.org/installer | php && ./composer.phar install --dev

Pluf DB is unit tested with PHPUnit. Run the tests using the Makefile:

    make tests

There are also vendor-specific test-scripts which will require you to
set database. To run them:

    # All unit tests including SQLite database engine tests
    vendor/bin/phpunit --config phpunit.xml

    # MySQL database engine tests
    vendor/bin/phpunit --config phpunit-mysql.xml

Look inside these the .xml files for further information and connection details.

## Reporting a security vulnerability

We want to ensure that Pluf DB is a secure library for everyone. If you've discovered a security vulnerability in Pluf DB, we appreciate your help in disclosing it to us in a [responsible manner](http://en.wikipedia.org/wiki/Responsible_disclosure).

Publicly disclosing a vulnerability can put the entire community at risk. If you've discovered a security concern, please email us at security@agiletoolkit.org. We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. We consider correspondence sent to security@agiletoolkit.org our highest priority, and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a security hot-fix release will be deployed as soon as possible.

## Coding style

The code follows PSR0, PSR1 and [PSR2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

Also, do not hesitate to add your name to the author list of a class in the docblock if you improve it.

## License

Add the following note on the top of all source codes:


    Pluf, the light and fast PHP SaaS framework
    Copyright (C) 2020 pluf.ir

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.

