# Discounts API

Provides a REST API for discount processing.

## Installation

To install the module on a Zend Expressive app, simply hook a reference in your application to this module's 
`ConfigProvider` class. 

### Setup Routes
If you're using FastRoute, you can auto-wire routes by adding `FastRouteDelegator` to your container configuration 
(e.g. at `config/autoload/dependencies.global.php`):

```
'delegators' => [
    \Zend\Expressive\Application::class => [
        \TeamLeader\ProductApi\Factory\FastRouteDelegator::class,
    ],
],
```

If you're using a different router you'll have to set up routes manually – you can use the delagator as a template.


## API

### GET /api/discounts/grant

An RPC endpoint that processes incoming order data by adding discounts to it on both product- and order-level and 
then returns the result.

#### Input
Standard structure ordered data, e.g.: 
```json
{
  "id": "3",
  "customer-id": "3",
  "items": [
    {
      "product-id": "B101",
      "quantity": "10",
      "unit-price": "9.75",
      "total": "97.50"
    }
  ],
  "total": "69.00"
}
```

Instead of `customer-id` and `product-id`, structured `customer` and `product` data may be supplied, respectively.  

#### Output

In the case of success, whether or not discounts were applied, the output will include complete `customer` and 
`product` data (for each order item), in addition to the original input data (potentially modified by discounts). 

Additional information on discount operations may optionally be supplied for each 
order item under the `discounts` key. All discounts applied will also be recorded into the order's top-level 
`discounts_applied` array.

Sample output:
```json
{
  "id": "3",
  "customer-id": "3",
  "items": [
    {
      "product-id": "B101",
      "quantity": "10",
      "unit-price": "9.75",
      "total": "97.50",
      "product": {
        "id": "B101",
        "description": "Basic on-off switch",
        "category": "2",
        "price": "4.99"
      }
    },
    {
      "product-id": "B101",
      "quantity": "2",
      "unit-price": "9.75",
      "total": "0.00",
      "discounts": [
        {
          "description": "Buy 5 get 1 free.",
          "discounted_amount": "19.50"
        }
      ]
    }
  ],
  "total": "69.00",
  "customer": {
    "id": "3",
    "name": "Jeroen De Wit",
    "since": "2016-02-11",
    "revenue": "0.00"
  },
  "discounts_applied": [
    {
      "name": "Buy 5 switches, get 1 free",
      "condition": "items.atLeast(1, product.category==2 \u0026\u0026 quantity\u003E=5)",
      "actions": [
        {
          "description": "Buy 5 get 1 free."
        }
      ],
      "granted": "2017-10-24T22:13:34+00:00"
    }
  ]
}
```

#### Response Statuses

* `200` (OK): processed order data in `JSON` format.
* `500` (Server Error)
