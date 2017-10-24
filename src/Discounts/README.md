# Discounts Domain Model

This module would be it's own repo and distributed into this app via Private Packagist / Satis. It's "inline" here only 
to simplify things.

## Installation

To install the module on a Zend Expressive app, simply hook a reference in your application to this module's 
`ConfigProvider` class.

## Configuration

Discounts (currently of type `ExpressionDiscount` only) are configured in `./src/Container/ConfigProvider.php`.
In the future, discounts could be loaded from a database instead of from static config. Administrators would then be 
able to build their own discounts against virtually unlimited criteria. 

The `webmozart/expression` package is being used to power two aspects of an `ExpressionDiscount`:

##### Criteria
Determines whether the discount is applicable on an order. Custom criteria classes may be built to aid in communicating 
criteria in a more understandable and re-usable way.

##### Actions
One or more mutations to apply to the order when the criteria is met. Actions may have an expression as their own 
"filter", which can be used to limit the effects of the action e.g. to a subset of order items.

Each action need NOT be atomic. In the future this COULD be enforced, for example, by assigning each action a UUID. 

Each action MUST return the entire order data it received, optionally with modifications. Modifications should ideally 
be limited to the order item data only (e.g. add new items, update the `total` for an item, update the `total` for an 
order, etc.)

## Usage
An instance of the `DiscountGranterService` can be used to apply discounts to an order. 

