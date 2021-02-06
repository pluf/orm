# Transactions

The most important point about Entity Manager and concurrency control is that it is very easy to understand. Entity Manager directly uses PDO connections without adding any additional locking behavior. We highly recommend you spend some time with the PDO, and transaction isolation specification of your database management system. Entity Manager only adds automatic versioning but does not lock objects in memory or change the isolation level of your database transactions. Basically, use Entity Manager like you would use direct PDO with your database resources.

We start the discussion of concurrency control in Pluf ORM with the granularity of EntityManagerFactory, and EntityManager, as well as database transactions and long units of work..


## Entity manager and transaction scopes

A EntityManagerFactory is an expensive-to-create, threadsafe object intended to be shared by all application threads. It is created once, usually on application startup.

An EntityManager is an inexpensive, non-threadsafe object that should be used once, for a single business process, a single unit of work, and then discarded. An EntityManager will not obtain a PDO Connection (or a Datasource) unless it is needed, so you may safely open and close an EntityManager even if you are not sure that data access will be needed to serve a particular request. (This becomes important as soon as you are implementing some of the following patterns using request interception.)

To complete this picture you also have to think about database transactions. A database transaction has to be as short as possible, to reduce lock contention in the database. Long database transactions will prevent your application from scaling to highly concurrent load.

What is the scope of a unit of work? Can a single EntityManager span several database transactions or is this a one-to-one relationship of scopes? When should you open and close a Session and how do you demarcate the database transaction boundaries?

### Unit of work

First, don't use the entitymanager-per-operation antipattern, that is, don't open and close an EntityManager for every simple database call! Of course, the same is true for database transactions. Database calls in an application are made using a planned sequence, they are grouped into atomic units of work. (Note that this also means that auto-commit after every single SQL statement is useless in an application, this mode is intended for ad-hoc SQL console work.)

The most common pattern in a multi-user client/server application is entitymanager-per-request. In this model, a request from the client is send to the server (where the persistence layer runs), a new EntityManager is opened, and all database operations are executed in this unit of work. Once the work has been completed (and the response for the client has been prepared), the persistence context is flushed and closed, as well as the entity manager object. You would also use a single database transaction to serve the clients request. The relationship between the two is one-to-one and this model is a perfect fit for many applications.


### Long units of work

The entitymanager-per-request pattern is not the only useful concept you can use to design units of work. Many business processes require a whole series of interactions with the user interleaved with database accesses. In web and enterprise applications it is not acceptable for a database transaction to span a user interaction with possibly long waiting time between requests. Consider the following example:

The first screen of a dialog opens, the data seen by the user has been loaded in a particular EntityManager and resource-local transaction. The user is free to modify the detached objects.

The user clicks "Save" after 5 minutes and expects his modifications to be made persistent; he also expects that he was the only person editing this information and that no conflicting modification can occur.

We call this unit of work, from the point of view of the user, a long running application transaction. There are many ways how you can implement this in your application.

A first implementation might keep the EntityManager and database transaction open during user think time, with locks held in the database to prevent concurrent modification, and to guarantee isolation and atomicity. This is of course an anti-pattern, a pessimistic approach, since lock contention would not allow the application to scale with the number of concurrent users.

Clearly, we have to use several database transactions to implement the application transaction. In this case, maintaining isolation of business processes becomes the partial responsibility of the application tier. A single application transaction usually spans several database transactions. It will be atomic if only one of these database transactions (the last one) stores the updated data, all others simply read data (e.g. in a wizard-style dialog spanning several request/response cycles). This is easier to implement than it might sound:

Automatic Versioning - An entity manager can do automatic optimistic concurrency control for you, it can automatically detect if a concurrent modification occurred during user think time (usually by comparing version numbers or timestamps when updating the data in the final resource-local transaction).

Detached Entities - If you decide to use the already discussed entity-per-request pattern, all loaded instances will be in detached state during user think time. The entity manager allows you to merge the detached (modified) state and persist the modifications, the pattern is called entitymanager-per-request-with-detached-entities. Automatic versioning is used to isolate concurrent modifications.

Extended Entity Manager - The Entity Manager may be disconnected from the underlying PDO connection between two client calls and reconnected when a new client request occurs. This pattern is known as entitymanager-per-application-transaction and makes even merging unnecessary. An extend persistence context is responsible to collect and retain any modification (persist, merge, remove) made outside a transaction. The next client call made inside an active transaction (typically the last operation of a user conversation) will execute all queued modifications. Automatic versioning is used to isolate concurrent modifications.

Both entitymanager-per-request-with-detached-objects and entitymanager-per-application-transaction have advantages and disadvantages, we discuss them later.

### Considering object identity

An application may concurrently access the same persistent state in two different persistence contexts. However, an instance of a managed class is never shared between two persistence contexts. Hence there are two different notions of identity:

Database Identity
foo.getId().equals( bar.getId() )

Instance Identity
foo==bar

Then for objects attached to a particular persistence context (i.e. in the scope of an EntityManager) the two notions are equivalent, and JVM identity for database identity is guaranteed by the Hibernate Entity Manager. However, while the application might concurrently access the "same" (persistent identity) business object in two different persistence contexts, the two instances will actually be "different" (object identity). Conflicts are resolved using (automatic versioning) at flush/commit time, using an optimistic approach.


