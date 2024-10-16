# PHP Tech Assignment - CliqDigital :computer:

I really liked your test task. I changed the structure, removing the service for generation and making the code as simple and extensible as possible.
Unfortunately, I was not allowed to use any external dependencies to solve the task, so I wrote several libraries that simplified the task.
For example, I implemented a simple version of the serializer for working with JSON/XML, DataMapper and a simple implementation of the file system.

I still installed some additional libraries for dev. Their main purpose is to improve the quality of the code.
* PHPSTAN
* CS-FIXER

To check the operation of the application, you can run the command

```bash
make build dependencies verify
```
After its execution, 2 files (txt & csv) will appear in the `public/report` directory.
In general, all the code needed to complete the task can be found in the file `public/index.php`.

# Code quality

### PHPSTAN 
```bash
make build dependencies linter
```
### PHPUnit
```bash
make build dependencies test
```
with coverage
```bash
make build dependencies coverage
```
The code coverage report will appear in the `./coverage` directory.
The `./src` directory is `100%` covered by tests. 
Note:
```aiignore
There is only 1 place in the code where the @codeCoverageIgnore annotation is used.
The exception will never be thrown, but this check is necessary for the correct operation of static analysis
```

