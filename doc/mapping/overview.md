

## Property Access

Three attributes manages read/write access.

- accessable
- getter
- setter


### Public

Is the best for performance

For example:

```php
#[Entity]
class Foo{
	#[Column]
	public ?string $title = null;
}
```

The title property values

- accessable: true
- getter: ignore
- setter: ignore

So Pluf is free to read and writ.

### Private

For example:

```php
#[Entity]
class Foo{
	#[Column]
	private ?string $title = null;
	...
}
```

The title property values

- accessable: false
- getter: true
- setter: true

Class must contains setTitle and getTitle.

### Constractor

In this cas the value is not set.

The constractor may be set title.

Pluf pass all data to constractor first.

for example


```php
#[Entity]
class Foo{
	private ?string $title = null;
	
	public function __constractor(..){
	// TODO
	}
	
	#[Column]
	public function getTitle(){
	 ///
	}
}
```


The title property values

- accessable: false
- getter: true
- setter: false
