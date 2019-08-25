# Coffee Machine
## Softia PHP Challenge

### Requirements

Implement a CLI-based application that emulates a coffee machine. The software should meet the following requirements:
 - it presents an interface that allows the following operations:
   - list available products (id, name, price, content, availability/quantity)
   - take an order (product ID and quantity)
   - receive payment for order, either cash or card
 - once a customer starts using the vending machine, the machine is locked for other customers until the customer leaves
 - the machine keeps track of products in a database
 - the machine keeps a history of transactions in a database
 - *) for payments, the machine keeps track of available monetary units in when accepts cash and returns the change
 - *) the products inventory is managed through the available quantities of the necessary ingredients (e.q. 1 x 40ml espresso is actually (5g coffee, 2g sugar, 0g milk, 30ml water)
 
_*) nice to have_


### Implementation 

 - extend without editting the code of this project
 - the code should be unit-tested
