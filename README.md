# AMNL \ Router Unslash Bundle
[![Latest Stable Version](https://poser.pugx.org/amnl/router-unslash/v/stable.png)](https://packagist.org/packages/amnl/router-unslash)
[![Total Downloads](https://poser.pugx.org/amnl/router-unslash/downloads.png)](https://packagist.org/packages/amnl/router-unslash)

When I tested one of my websites with [Nibbler](http://nibbler.silktide.com/) it gave the following useful advice:
> Adding or removing a trailing slash to all URLs of this site returns an error page
> (404). This is OK as no content is being duplicated, but visitors might try browsing to
> the pages of this website with or without a trailing slash. Ideally, the alternate URL
> should redirect to the correct page.

So I created this easy bundle that automates these kind of redirects.

## Configuration
Below you will find the default configuration
```yml
# Router Unslash
amnl_router_unslash:
  permanent: false   # True = 301, False = 302
  public: true		 # Public cache?
  maxage: 1800       # Cache-Control max-age
  smaxage: 21600     # Cache-Control s-maxage
```