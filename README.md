# microblog

The first assignment is to create a microblog application.

You can use [Silex](http://silex.sensiolabs.org) if you want, but it's not a requirement.

## Objectives

- Display the latest 5 articles on the homepage
- Create an authentication section where someone can
  - login (email + password)
  - register (name + email + password)
  - logout
- Create a page to manage articles (authenticated)
  - create/edit new content (just text for now)
  - list created content
  - delete an article

## Bonus

- Adhere to the [PSR-2](http://www.php-fig.org/psr/psr-2/) coding standards
- Write tests for the authentication and article components

## What I've done so far...

Based on the [gist you shared with me some time ago about creating a domain approach to connect to a db](https://gist.github.com/DragonBe/b6f709bd6d1863a07846) and the [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial), I've built a basic app displaying pages saved in a database.

I learned to create an authentication system in another tutorial (from the Youtube channel phpacademy) and I'm combining it with that, but I have a few problem with keeping things neat and decoupled, especially having the authentication controller not too bloated, separating the validation etc...

## Questions

I've put the question in `@todo` comments along the code, because I thought it may be easier like this for you to read / answer.

