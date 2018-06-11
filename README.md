# Statamic ❤️ APIs

## This repo's contents

This repo is organized into two directories:

- `statamic` holds the Statamic site
- `laravel` holds the Laravel app that will be acting as the API

The Laravel site contains a [ProductController](laravel/app/Http/Controllers/ProductController.php) that contains the basic read, storing, and updating logic. The `/product*` CSRF verification has been disabled just to make things simpler.

## Connect the two

Make sure you can access both sites from their own URLs.

In the `.env` file for the Statamic site, add the Laravel site's URL.

```
LARAVEL_URL=http://the-laravel-app.test
```

## Set up the database

Create a database for the Laravel app and add the credentials to its `.env` file.

```
DB_HOST=localhost
DB_DATABASE=statamic-api
DB_USERNAME=root
DB_PASSWORD=
```

[We have included a migration in this repo](laravel/database/migrations/2018_05_31_200716_create_products_table.php), run it.

```
php artisan migrate
```


## Sidebar Navigation

We have a [Listener file](statamic/site/addons/Products/ProductsListener.php) that takes care of adding an item to the navigation. Click the `Products` item in the sidebar to view the listing.


## Fieldset

When creating or editing a product, the publish component will be rendered according to the provided [product fieldset](statamic/site/settings/fieldsets/product.yaml).

To add more fields, you should create a migration (or update the original one) in the Laravel app to add the columns to the table, and also add fields to the fieldset so they are rendered on the publish page.


## Validation

This repo assumes you will be doing the validation on the Laravel side.

This repo passes the validation errors back into the [ProductRepository class](statamic/site/addons/Products/ProductRepository.php). It prefixes all the fields with a `fields.` string. This is because the Publish component will bring the first erroring field into focus and is expecting them all to be nested within `fields`.


## Front-end

The `/products` route is a [page](statamic/site/content/pages/6.products/index.md) that loads a [template](statamic/site/themes/redwood/templates/products/index.html) containing a [simple custom tag](statamic/site/addons/Products/ProductsTags.php#L9-L14).

The `/products/{slug}` route is a [wildcard route](site/settings/routes.yaml#L24) that loads a [template](site/themes/redwood/templates/products/product.html) with another [simple custom tag](site/addons/Products/ProductsTags.php#L16-L21).
