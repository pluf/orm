 <?php
 
 
 
 @Entity public class PropertyRecord {
     @EmbeddedId PropertyOwner owner;
     @AttributeOverrides({
         @AttributeOverride(name="key.street",
             column=@Column(name="STREET_NAME")),
             @AttributeOverride(name="value.size",
                 column=@Column(name="SQUARE_FEET")),
                 @AttributeOverride(name="value.tax",
                     column=@Column(name="ASSESSMENT"))
     })
     @ElementCollection
     Map<Address, PropertyInfo> parcels;
 }
 
 @Embeddable public class PropertyInfo {
     Integer parcelNumber;
     Integer size;
     BigDecimal tax;
 }