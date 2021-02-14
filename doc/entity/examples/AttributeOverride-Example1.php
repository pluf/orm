 <?php

#[MappedSuperclass]
class Employee
{

    #[Id]
    protected int $id;

    #[Version]
    protected int $version;

    protected ?string $address;

    public function getId(): int
    {
        /* TODO */
    }

    public function setId(int $id)
    {
        /* TODO */
    }

    public function getAddress(): ?string
    {
        /* TODO */
    }

    public function setAddress(?string $address)
    {
        /* TODO */
    }
}

#[Entity]
#[AttributeOverride(name:"address", column:new Column(name="ADDR"))]
class PartTimeEmployee extends Employee
{

    // address field mapping overridden to ADDR
    protected float $wage;

    public function getHourlyWage()
    {
        /* TODO */
    }

    public function setHourlyWage(float $wage)
    {
        /* TODO */
    }
}