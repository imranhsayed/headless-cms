# Headless CMS

[![Project Status: Active.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)


A WordPress plugin that adds features to use WordPress as a headless CMS with any front-end environment using REST API.
This plugin provides multiple features and you can use the one's that are relevant to your front-end application.
You don't necessarily need to use all.


<a href="https://youtu.be/nYXL1KKjKrc" target="_blank">
<img src="https://codeytek.com/wp-content/uploads/2020/07/banner-1544x500-1.png" alt="Headless CMS WordPress Plugin" />
</a>

## Install via Composer

```shell script
composer require imranhsayed/headless-cms
```

- [https://packagist.org/packages/imranhsayed/headless-cms](https://packagist.org/packages/imranhsayed/headless-cms)


## Maintainer

| Name                                                   | Github Username |
|--------------------------------------------------------|-----------------|
| [Imran Sayed](mailto:codeytek.academy@gmail.com)       |  @imranhsayed   |

## Assets

Assets folder contains webpack setup and can be used for creating blocks or adding any other custom scripts like javascript for admin.

- Run `npm i` from `assets` folder to install required npm packages.
- Use `npm run dev` during development for assets.
- Use `npm run prod` for production.
- Use `npm run eslint:fix js/fileName.js` for fixing and linting eslint errors and warning.

# REST API ENDPOINT

> This plugin provides you different endpoints using WordPress REST API.

## Getting Started :clipboard:

These instructions will get you a copy of the project up and running on your local machine for development purposes.

## Prerequisites :door:

You need to have any WordPress theme activated on your WordPress project, which has REST API enabled.

## Installation :wrench:

1. Clone the plugin directory in the `/wp-content/plugins/` directory, or install a zipped directory of this plugin through the [WordPress plugins](https://wordpress.org/plugins/headless-cms/) screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## Example Frontend applications

Example of front-end applications where this plugin can be used:

1. [Gatsby WordPress Theme](https://github.com/imranhsayed/gatsby-wordpress-themes)
2. [React Wordpress Theme](https://github.com/imranhsayed/react-wordpress-theme)

## Features

1. Custom REST API Endpoints.
2. Social links in customizer.
3. Image uploads for categories.
4. Custom header and footer menus.
5. Custom Widgets.
6. Custom Header and Footer GraphQL fields when using [wp-graphql](https://github.com/wp-graphql/wp-graphql) plugin
7. Adds coAuthors data in the GraphQL Api.

* Adds option to add social links in customizer
* Registers two custom menus for header ( menu location = hcms-menu-header ) and for footer ( menu location = hcms-menu-footer )
* Registers the following sidebars

1. HCMS Footer #1 with sidebar id 'hcms-sidebar-1'

![](assets/images/screenshot-4.png)

2. HCMS Footer #2 with sidebar id 'hcms-sidebar-2'

![](assets/images/screenshot-5.png)

* Adds frontend preview link option in the editor.
For Preview to work , you also need the [wp-graphql-jwt-authentication](https://github.com/wp-graphql/wp-graphql-jwt-authentication) plugin as well

### More Features
1. Registers the sections for socials icons in the customizer

* Social icons urls for 'facebook', 'twitter', 'instagram', 'youtube'

![](assets/images/screenshot-6.png)

2. Image upload features for categories

* Provides Image upload features for categories.

![](assets/images/screenshot-3.png)

3. Plugin Settings Page

* Settings for getting data for a custom page like Hero section, Search section, Featured post section, latest posts heading.

* Registers custom end points

4. Following fields when using [wp-graphql](https://github.com/wp-graphql/wp-graphql) plugin.

* Custom Header and Footer GraphQL
![](assets/images/screenshot-2.png)

* WooCommerce Countries and States

```javascript
{
  wooCountries {
    billingCountries {
      countryCode
      countryName
    }
    shippingCountries {
      countryCode
      countryName
    }
  }
  wooStates(countryCode: "in") {
    states {
      stateCode
      stateName
    }
  }
}
```

* WooCommerce Shipping Zones.

```javascript
{
  shippingInfo {
    shippingZones
    storePostCode
  }
}
```

* Add Product to Wishlist Mutation ( Authenticated request )

```javascript
mutation ADD_ITEM {
  addToWishlist(input: {clientMutationId: "example", productId: 340}) {
    clientMutationId
    error
    added
    productId
    wishlistProductIds
  }
}
```

* Remove Product from Wishlist Mutation( Authenticated request )

```javascript
mutation REMOVE_ITEM{
  removeFromWishlist(input: {clientMutationId: "example", productId: 340}) {
    error
    productId
    removed
    wishlistProductIds
  }
}
```

* Get Products from Wishlist Mutation ( Authenticated request )
```javascript
query GET_WISHLIST {
  getWishList {
    productIds
    error
    products {
      databaseId
      name
      slug
      buttonText
      image {
        alt
        src
        attachmentId
      }
      priceHtml
      productUrl
      stockQuantity
      stockStatus
      typename
    }
  }
}
```

* Schema Details

```javascript
  posts {
    nodes {
      seo {
        schemaDetails
      }
    }
  }
```

## Available Endpoints:

### Get single post ( GET request )
* `http://example.com/wp-json/rae/v1/post?post_id=1`

### Get posts by page no: ( GET Request )
* `http://example.com/wp-json/rae/v1/posts?page_no=1`

### Get header and footer date: ( GET Request )
* Get the header data ( site title, site description , site logo URL, menu items ) and footer data ( footer menu items, social icons )
* `http://example.com/wp-json/rae/v1/header-footer?header_location_id=hcms-menu-header&footer_location_id=hcms-menu-footer`

## Get WooCommerce Country and states ( GET Request )
- `/wp-json/rae/v1/wc/countries/`
- `/wp-json/rae/v1/wc/states?countryCode=IN`

## Contributing :busts_in_silhouette:

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

I use [Git](https://github.com/) for versioning. 

## Author :pencil:

* **[Imran Sayed](https://codeytek.com)**

## License :scroll:

![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)

- **[GPLv2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)**

## Custom Enhancements

- Added a secure REST API endpoint to fetch posts
- Implemented role-based access control using permission_callback
- Endpoint: /wp-json/headless/v1/secure-posts

