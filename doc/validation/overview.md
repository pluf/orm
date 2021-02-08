# Object Validation

In this quick tutorial, we cover the basics of validating an object with the Pluf ORM data validation framework, also known as Pluf Object Validation 2.0.

Validating user input is a super common requirement in most applications. And the Pluf Object Validation framework has become the de facto standard for handling this kind of logic.

## Using Validation Annotations

Here, we'll take a User bean and work on adding some simple validation to it:

```php
class User {

    #[NotNull(message = "Name cannot be null")]
    private string $name;

    #[AssertTrue]
    private bool $working;

    #[Size(min = 10, max = 200, message = "About Me must be between 10 and 200 characters")]
    private string $aboutMe;

    #[Min(value = 18, message = "Age should not be less than 18")]
    #[Max(value = 150, message = "Age should not be greater than 150")]
    private int $age;

    #[Email(message = "Email should be valid")
    private string $email;

    // standard setters and getters 
}
```
Here is list of common PHP attributes

- NotNull validates that the annotated property value is not null.
- AssertTrue validates that the annotated property value is true.
- Size validates that the annotated property value has a size between the attributes min and max; can be applied to String, Collection, Map, and array properties.
- Min validates that the annotated property has a value no smaller than the value attribute.
- Max validates that the annotated property has a value no larger than the value attribute.
- Email validates that the annotated property is a valid email address.

Some annotations accept additional attributes, but the message attribute is common to all of them. This is the message that will usually be rendered when the value of the respective property fails validation.

And some additional annotations that can be found in the JSR:

- NotEmpty validates that the property is not null or empty; can be applied to String, Collection, Map or Array values.
- NotBlank can be applied only to text values and validates that the property is not null or whitespace.
- Positive and PositiveOrZero apply to numeric values and validate that they are strictly positive, or positive including 0.
- Negative and NegativeOrZero apply to numeric values and validate that they are strictly negative, or negative including 0.
- Past and @PastOrPresent validate that a date value is in the past or the past including the present; can be applied to date types including those added in Java 8.
- Future and @FutureOrPresent validate that a date value is in the future, or in the future including the present.

## Validate the Object

Now that we have a Validator, we can validate our bean by passing it to the validate method.

Any violations of the constraints defined in the User object will be throw as a Excetpiont:

```php
try{
	$validator.validate($user);
} catch (Exception $ex){
	foreach($exp in $ex->getParams()){
		echo $exp;
	}
}
```

By iterating over the exception parameters, we can get all the violation messages using the getMessage method:

```php
for ($param in $ex->getParams()) {
    $logger.error($param->getMessage()); 
}
```

## Conclusion

This article focused on a simple pass through the standard Validation API. We showed the basics of bean validation using Pluf Object Validation and APIs.

As usual, an implementation of the concepts in this article and all code snippets can be found over on GitHub.

