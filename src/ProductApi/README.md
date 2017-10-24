# Products Api

This module is a "mock" for an external "Product" API on a different micro-service.

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

### GET /api/products

Responses:
* `200` (OK): all available product data in `JSON` format (array of objects).
* `500` (Server Error)
