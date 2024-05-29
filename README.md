# PHP Tech Assignment - CliqDigital :computer:

Welcome to the PHP Tech Assignment! For this task, you'll be continuing the implementation of a webhook handler responsible for processing payment webhook notifications from three different fake payment gateways: `NIRVANA`, `RHCP`, and `SOAD`.

Your mission, should you choose to accept it, is to develop a report feature. Each of these gateways provides the necessary data, but they do so in different formats and with different attributes. :wink:

## Goals

- The idea is simple! You need to collect the notifications (3 gateways, 3 different files) from [this link](https://github.com/cliq-bv/php-tech-assignment/tree/main/payment-notifications), compile them into a single output file in the [public folder](https://github.com/cliq-bv/php-tech-assignment/tree/main/public), and make all the tests `GREEN`!
  - This output file can be either `CSV` or `TXT` like `output.csv` and `output.txt` with 4 lines (1 header and 3 data lines).
  
- You can find [examples here](https://github.com/cliq-bv/php-tech-assignment/tree/main/tests/fixtures) of the exact files you need to generate. As mentioned, each notification file has its own properties, but the output has specific ones.

- To generate the output file, you need to extract only 3 properties from each notification file. Here's a matrix describing the fields you need to use:

| Description            | Output File | Nirvana       | RHCP           | SOAD               |
|------------------------|-------------|---------------|----------------|--------------------|
| Transaction datetime   | date_time   | created_at    | created        | timestamp          |
| Transaction status     | status      | status        | charge_status  | transaction_status |
| Transaction amount     | total       | amount_received | total_amount   | amount             |

- To continue the work, implement the handle method in [NotificationHandlerService.php](https://github.com/cliq-bv/php-tech-assignment/blob/main/src/Services/NotificationHandlerService.php). The class constructor specifies the type of output file you need to generate (`CSV` or `TXT`).

- Also, you can find the tests [here](https://github.com/cliq-bv/php-tech-assignment/tree/main/tests/Feature) to ensure the output results, but of course, they are broken. :nail_care:

## Cool, now some rules to make it more fun

- You are not allowed to use any external libraries besides PHPUnit and the PHP-FIG (if you want) interfaces.
- No databases (okay, only the required files).
- No REST API structure.
- External calls? Nope. =D

## What we are looking for in this assignment

- Project structure
- PHP >= 8
- Composer 2
- OOP principles
- Automated Tests
- SOLID principles
- KISS principle (Keep It Simple, Stupid)
- Scalability
  - Make it easy to implement more gateways
  - Make it painless to add more output file formats
- Reusability
- Git skills
- Documentation skills
- And more! =)

Extra points for:

- Docker
- CI/CD
- TDD
- Coverage >= 99%
- Code smells tools

## Tips!

- Don’t overengineer your code.
- Pay special attention to the S, O, and I principles from SOLID.
- `composer test` might be useful

### Good Luck! 🍀
