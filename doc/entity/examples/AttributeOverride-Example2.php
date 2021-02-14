 <?php
 
 
 
 
 @Embeddable public class Address {
     protected String street;
     protected String city;
     protected String state;
     @Embedded protected Zipcode zipcode;
 }
 
 @Embeddable public class Zipcode {
     protected String zip;
     protected String plusFour;
 }
 
 @Entity public class Customer {
     @Id protected Integer id;
     protected String name;
     @AttributeOverrides({
         @AttributeOverride(name="state",
             column=@Column(name="ADDR_STATE")),
             @AttributeOverride(name="zipcode.zip",
                 column=@Column(name="ADDR_ZIP"))
     })
     @Embedded protected Address address;
     ...
 }
     
 